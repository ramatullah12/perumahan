<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
        View::composer('layout.customer', function ($view) {
            if (Auth::check()) {
                $unreadCount = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with('unreadNotificationsCount', $unreadCount);
            }
        });

        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}