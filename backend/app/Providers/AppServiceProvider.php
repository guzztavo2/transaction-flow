<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(
            \App\Domain\Repositories\Account\AccountRepositoryInterface::class,
            \App\Domain\Repositories\Account\EloquentAccountRepository::class
        );

        $this->app->bind(
            \App\Domain\Repositories\User\UserRepositoryInterface::class,
            \App\Domain\Repositories\User\EloquentUserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        self::eventAccountListeners();
        self::eventUserListeners();
    }

    private static function eventAccountListeners(): void
    {
        Event::listen(
            \App\Domain\Events\AccountCreated::class,
            [\App\Domain\Listeners\Account\SendWelcomeNotification::class, 'handle']
        );

        Event::listen(
            \App\Domain\Events\AccountCreated::class,
            [\App\Domain\Listeners\Account\CreatedLog::class, 'handle']
        );
    }

    private static function eventUserListeners(): void
    {
        Event::listen(
            \App\Domain\Events\UserPasswordUpdated::class,
            [\App\Domain\Listeners\User\SendUpdatedPasswordNotification::class, 'handle']
        );
        Event::listen(
            \App\Domain\Events\UserPasswordUpdated::class,
            [\App\Domain\Listeners\User\LogUserUpdatedPassword::class, 'handle']
        );
    }
}
