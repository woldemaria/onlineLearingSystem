<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        $policies = config('auth.policies', []);
        foreach ($policies as $model => $policy) {
            \Gate::policy($model, $policy);
        }
    }
}