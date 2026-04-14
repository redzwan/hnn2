<?php

namespace App\Providers;

use App\Listeners\SendOrderPlacedEmail;
use App\Listeners\SendRegistrationConfirmationEmail;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Vanilo\Order\Events\OrderWasCreated;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->applyEmailSettings();
        $this->registerEmailListeners();
        $this->shareLayoutData();
    }

    private function shareLayoutData(): void
    {
        View::composer('layouts.app', function ($view) {
            try {
                if (! Schema::hasTable('categories')) {
                    return;
                }

                $view->with('categories', Category::active()->orderBy('sort_order')->get());
            } catch (\Throwable) {
                // Silently fail if DB is not ready
            }
        });
    }

    private function registerEmailListeners(): void
    {
        Event::listen(Registered::class, SendRegistrationConfirmationEmail::class);
        Event::listen(OrderWasCreated::class, SendOrderPlacedEmail::class);
    }

    private function applyEmailSettings(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $mailer = Setting::get('mail_mailer');

            if (! $mailer) {
                return;
            }

            config([
                'mail.default' => $mailer,
                'mail.mailers.smtp.host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
                'mail.mailers.smtp.port' => (int) Setting::get('mail_port', config('mail.mailers.smtp.port')),
                'mail.mailers.smtp.username' => Setting::get('mail_username'),
                'mail.mailers.smtp.password' => Setting::get('mail_password') ? decrypt(Setting::get('mail_password')) : null,
                'mail.mailers.smtp.encryption' => Setting::get('mail_encryption', 'tls'),
                'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
                'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
            ]);
        } catch (\Throwable) {
            // Silently fail if DB is not ready (e.g. during migrations)
        }
    }
}
