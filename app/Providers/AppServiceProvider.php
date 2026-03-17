<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\STBC4866Entry;
use App\Models\STBC4966Entry;
use App\Models\STBC4886Entry;
use App\Models\STBC4996Entry;
use App\Policies\UserPolicy;
use App\Policies\STBC4866EntryPolicy;
use App\Policies\STBC4966EntryPolicy;
use App\Policies\STBC4886EntryPolicy;
use App\Policies\STBC4996EntryPolicy;

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
        Gate::policy(STBC4866Entry::class, STBC4866EntryPolicy::class);
        Gate::policy(STBC4966Entry::class, STBC4966EntryPolicy::class);
        Gate::policy(STBC4886Entry::class, STBC4886EntryPolicy::class);
        Gate::policy(STBC4996Entry::class, STBC4996EntryPolicy::class);
    }
}
