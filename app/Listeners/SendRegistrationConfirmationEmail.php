<?php

namespace App\Listeners;

use App\Mail\TemplateMail;
use App\Models\EmailTemplate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegistrationConfirmationEmail
{
    public function handle(Registered $event): void
    {
        try {
            $template = EmailTemplate::findByKey('registration_confirmation');

            if (! $template) {
                return;
            }

            $user = $event->user;

            Mail::to($user->email)->send(new TemplateMail($template, [
                'customer_name' => $user->name,
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'login_url' => url('/login'),
            ]));
        } catch (\Throwable $e) {
            Log::error('Failed to send registration email', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
