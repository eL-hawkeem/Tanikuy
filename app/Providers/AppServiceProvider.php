<?php

namespace App\Providers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $notificationService = app(NotificationService::class);
            $notificationsData = $notificationService->getNotifications($user);

            $view->with([
                'notifications' => $notificationsData['items'],
                'notificationCount' => $notificationsData['total_count'],
                'urgentNotificationCount' => $notificationsData['urgent_count'],
                'lowStockCount' => $notificationsData['low_stock_count'],
            ]);
        });
    }
}
