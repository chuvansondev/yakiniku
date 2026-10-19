<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Settings\Schemas\SettingForm;
use App\Services\SettingService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;

class ListSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected string $view = 'filament.resources.settings.pages.list-settings';

    protected static ?string $title = 'Cài đặt giao diện';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(SettingService::class)->all()->toArray();
        $this->form->fill($settings);
    }

    public static function getNavigationLabel(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Cài đặt giao diện';
    }

    public function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema)->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        app(SettingService::class)->updateMany($state);

        Notification::make()
            ->success()
            ->title('Đã lưu cài đặt giao diện')
            ->send();
    }
}
