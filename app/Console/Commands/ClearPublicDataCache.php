<?php

namespace App\Console\Commands;

use App\Services\PublicDataCache;
use Illuminate\Console\Command;

class ClearPublicDataCache extends Command
{
    protected $signature = 'cache:clear-public-data {group? : menu, kids-items, combos, promotions, banners, settings, restaurants, recipes, or tips}';

    protected $description = 'Invalidate cached public catalog and page data';

    public function handle(): int
    {
        $requestedGroup = $this->argument('group');
        $group = PublicDataCache::normalizeGroup($requestedGroup);

        if ($requestedGroup !== null && $group === null) {
            $this->components->error('Unknown cache group. Available groups: '.implode(', ', PublicDataCache::groups()));

            return self::INVALID;
        }

        PublicDataCache::clear($group);
        $this->components->info($group === null
            ? 'All public data caches have been invalidated.'
            : 'Public data cache invalidated: '.substr($group, strlen('public-data:')).'.');

        return self::SUCCESS;
    }
}
