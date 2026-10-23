<?php

namespace App\Services;

use App\Enums\AppLocale;
use App\Repositories\Contracts\SettingRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SettingService
{
    private ?Collection $requestSettings = null;

    public function __construct(private readonly SettingRepositoryContract $settings) {}

    private function settingsForRequest(): Collection
    {
        return $this->requestSettings ??= PublicDataCache::remember(
            PublicDataCache::SETTINGS,
            'all',
            fn () => $this->settings->allKeyed(),
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->settingsForRequest();

        if ($key === 'email' && ! $settings->has('email') && $settings->has('Email')) {
            return $settings->get('Email') ?? $default;
        }

        $value = $settings->get($key) ?? $default;

        if ($key === 'header_submenus' && is_string($value)) {
            return json_decode($value, true) ?: [];
        }

        return $value;
    }

    public function all(): Collection
    {
        $settings = clone $this->settingsForRequest();

        // Older settings may have been stored with a display label as the key.
        // Normalize the legacy Email key so the form and frontend use `email`.
        if (! $settings->has('email') && $settings->has('Email')) {
            $settings->put('email', $settings->get('Email'));
        }
        $submenus = $settings->get('header_submenus');
        if (is_string($submenus)) {
            $settings->put('header_submenus', json_decode($submenus, true) ?: []);
        }

        return $settings;
    }

    public function updateMany(array $settings): void
    {
        $userId = Auth::id();

        foreach ($settings as $key => $value) {
            if ($key === 'header_submenus' && is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $this->settings->updateValue((string) $key, $value, $userId);
        }

        PublicDataCache::clear(PublicDataCache::SETTINGS);
        $this->requestSettings = null;
    }

    public function localized(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        if (app()->getLocale() !== AppLocale::English->value) return $value;

        return $this->get($key.'_en') ?: __($value ?? '');
    }
}
