<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail(mixed $notifiable): MailMessage
    {
        $template = EmailTemplate::findByKey('password_reset');

        if (! $template) {
            return parent::toMail($notifiable);
        }

        $resetUrl = $this->resetUrl($notifiable);

        $variables = [
            'customer_name' => $notifiable->name,
            'reset_url' => $resetUrl,
            'expiry_minutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
            'app_name' => config('app.name'),
        ];

        $renderedSubject = $template->renderSubject($variables);
        $renderedBody = $template->renderBody($variables);

        return (new MailMessage)
            ->subject($renderedSubject)
            ->view('mail.template', [
                'renderedSubject' => $renderedSubject,
                'renderedBody' => $renderedBody,
            ]);
    }
}
