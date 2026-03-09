<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\STBC4866Entry;
use App\Models\STBC4966Entry;
use App\Models\STBC4886Entry;
use App\Policies\UserPolicy;
use App\Policies\LogEntryPolicy;
use App\Policies\ProjectEntryPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(STBC4866Entry::class, LogEntryPolicy::class);
        Gate::policy(STBC4966Entry::class, ProjectEntryPolicy::class);
        Gate::policy(STBC4886Entry::class, LogEntryPolicy::class);
    }
}
