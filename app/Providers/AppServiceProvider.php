<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('role-policy', RolePolicy::class);
        $this->app->bind('permission-policy', PermissionPolicy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
