<?php

namespace Tests\Feature;

use App\Repositories\Contracts\SettingRepositoryContract;
use App\Services\SettingService;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class SettingRequestCacheTest extends TestCase
{
    public function test_repeated_helpers_read_shared_cache_only_once_per_request(): void
    {
        Cache::flush();
        $repository = Mockery::mock(SettingRepositoryContract::class);
        $repository->shouldReceive('allKeyed')->once()->andReturn(collect([
            'title' => 'Original',
            'header_submenus' => '{"menu":["Food"]}',
        ]));
        $this->app->instance(SettingRepositoryContract::class, $repository);

        $reads = 0;
        Event::listen([CacheHit::class, CacheMissed::class], function () use (&$reads) {
            $reads++;
        });

        $this->assertSame('Original', setting('title'));
        $firstReadCount = $reads;
        $this->assertGreaterThan(0, $firstReadCount);
        for ($i = 0; $i < 50; $i++) {
            $this->assertSame('Original', setting('title'));
        }
        $settings = app(SettingService::class)->all();
        $settings->put('title', 'Modified copy');
        $this->assertSame('Original', setting('title'));
        $this->assertSame(['menu' => ['Food']], setting('header_submenus'));
        $this->assertSame($firstReadCount, $reads);
    }

    public function test_updates_invalidate_the_request_snapshot_and_shared_cache(): void
    {
        Cache::flush();
        $repository = Mockery::mock(SettingRepositoryContract::class);
        $repository->shouldReceive('allKeyed')->twice()->andReturn(
            collect(['title' => 'Before']),
            collect(['title' => 'After']),
        );
        $repository->shouldReceive('updateValue')->once()->with('title', 'After', null);
        $this->app->instance(SettingRepositoryContract::class, $repository);

        $this->assertSame('Before', setting('title'));
        app(SettingService::class)->updateMany(['title' => 'After']);
        $this->assertSame('After', setting('title'));

        $previous = app(SettingService::class);
        $this->app->forgetScopedInstances();
        $this->assertNotSame($previous, app(SettingService::class));
        $this->assertSame('After', setting('title'));
    }
}
