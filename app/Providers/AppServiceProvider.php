<?php

namespace App\Providers;

use App\Listeners\SendOrderPlacedEmail;
use App\Listeners\SendRegistrationConfirmationEmail;
use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use App\Services\ThemeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Vanilo\Order\Events\OrderWasCreated;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeService::class);
    }

    public function boot(): void
    {
        $this->configureStorage();
        $this->applyEmailSettings();
        $this->registerEmailListeners();
        $this->shareNavData();
    }

    private function shareNavData(): void
    {
        View::composer('*', function ($view) {
            try {
                $view->with('categories', Category::active()->orderBy('sort_order')->get());
                $view->with('pages', Page::active()->orderBy('sort_order')->get());
            } catch (\Throwable) {
                // DB not ready (e.g. during migrations or CLI commands)
            }
        });

        View::composer('layouts.app', function ($view) {
            try {
                $theme = app(ThemeService::class);
                $view->with('theme', $theme->active());
                $view->with('themeKey', $theme->activeKey());
                $view->with('themeCssVars', $theme->cssVariables());
            } catch (\Throwable) {
                // Fallback if DB not ready
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

            Config::set('mail.default', $mailer);
            Config::set('mail.mailers.smtp.host', Setting::get('mail_host', config('mail.mailers.smtp.host')));
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', config('mail.mailers.smtp.port')));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password') ? decrypt(Setting::get('mail_password')) : null);
            Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', 'tls'));
            Config::set('mail.from.address', Setting::get('mail_from_address', config('mail.from.address')));
            Config::set('mail.from.name', Setting::get('mail_from_name', config('mail.from.name')));
        } catch (\Throwable) {
            // Silently fail if DB is not ready
        }
    }

    private function configureStorage(): void
    {
        try {
            if (! Setting::get('storage.use_s3', false)) {
                return;
            }

            Config::set('filesystems.default', 's3');
            Config::set('filesystems.disks.s3.endpoint', Setting::get('storage.s3_endpoint'));
            Config::set('filesystems.disks.s3.region', Setting::get('storage.s3_region', 'us-east-1'));
            Config::set('filesystems.disks.s3.bucket', Setting::get('storage.s3_bucket'));
            Config::set('filesystems.disks.s3.key', Setting::get('storage.s3_key'));
            Config::set('filesystems.disks.s3.secret', Setting::get('storage.s3_secret'));
            Config::set('filesystems.disks.s3.use_path_style_endpoint', true);
            Config::set('media-library.disk_name', 's3');
        } catch (\Throwable) {
            // DB not ready yet
        }
    }
}
