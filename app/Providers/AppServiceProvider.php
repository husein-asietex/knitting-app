<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;


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
        $this->configureDefaults();
        $this->access();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /*
    role_id
        1 = superadmin
        2 = admin
        3 = user
    */


    public function access()
    {
        Gate::define('access-dashboard', function (User $user) {
            return in_array($user->role_id, ['2', '1']);
        });

        Gate::define('access-reports-create', function (User $user) {
            return strtolower($user->position ?? '') === 'wakaru';
        });

        Gate::define('access-reports', function (User $user) {
            return in_array($user->role, ['user', 'admin', 'superadmin']);
        });

        Gate::define('access-number-englishes', function (User $user) {
            return in_array($user->role, ['user', 'admin', 'superadmin']);
        });

        Gate::define('access-lots', function (User $user) {
            return in_array($user->role, ['user', 'admin', 'superadmin']);
        });

        Gate::define('access-blocks', function (User $user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('access-machines', function (User $user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('access-machine-users', function (User $user) {
            return in_array($user->role, ['user', 'admin', 'superadmin']);
        });

        Gate::define('access-shifts', function (User $user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('access-teams', function (User $user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('access-users', function (User $user) {
            return $user->role === 'superadmin';
        });
    }
}
