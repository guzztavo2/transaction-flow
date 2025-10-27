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
    }

    private static function eventAccountListeners(): void
    {
        Event::listen(
            \App\Domain\Events\AccountCreated::class,
            [\App\Domain\Listeners\Account\SendWelcomeEmail::class, 'handle']
        );

        Event::listen(
            \App\Domain\Events\AccountCreated::class,
            [\App\Domain\Listeners\Account\CreatedLog::class, 'handle']
        );
    }
}
