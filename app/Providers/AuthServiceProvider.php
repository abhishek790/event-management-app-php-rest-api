<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */

    //  this method is only called once when laravel starts
    public function boot(): void
    {
        // while using this gate, it is certain that user is authenticated as we have added that auth in EventController ,which is why $user model is not checked whether it is null or not
        Gate::define('update-event', function ($user, Event $event) {
            return $user->id === $event->user_id;
        });
    }
}
