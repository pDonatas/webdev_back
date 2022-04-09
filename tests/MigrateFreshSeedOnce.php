<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;

trait MigrateFreshSeedOnce
{
    public function migrateFreshSeedOnce(array $seeds = []): void
    {
        $this->artisan('migrate:fresh');
        $this->artisan('migrate', ['--seed' => true]);

        foreach ($seeds as $seed) {
            $this->artisan('db:seed', ['--class' => $seed]);
        }
    }
}
