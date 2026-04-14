<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;

class EmailSettings extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Email Settings';

    protected static ?string $title = 'Email (SMTP) Configuration';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'settings/email';

    protected static string $view = 'filament.pages.settings.email';

    public ?string $mail_mailer = 'smtp';

    public ?string $mail_host = '';

    public ?string $mail_port = '587';

    public ?string $mail_username = '';

    public ?string $mail_password = '';

    public ?string $mail_encryption = 'tls';

    public ?string $mail_from_address = '';

    public ?string $mail_from_name = '';

    public ?string $test_email = '';

    public function mount(): void
    {
        $this->mail_mailer = Setting::get('mail_mailer', 'smtp');
        $this->mail_host = Setting::get('mail_host', '');
        $this->mail_port = Setting::get('mail_port', '587');
        $this->mail_username = Setting::get('mail_username', '');
        $this->mail_password = Setting::get('mail_password') ? '********' : '';
        $this->mail_encryption = Setting::get('mail_encryption', 'tls');
        $this->mail_from_address = Setting::get('mail_from_address', '');
        $this->mail_from_name = Setting::get('mail_from_name', config('app.name'));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('SMTP Server')
                    ->description('Configure outgoing mail server settings')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('mail_mailer')
                            ->label('Mail Driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'log' => 'Log (testing only)',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('mail_host')
                            ->label('SMTP Host')
                            ->placeholder('smtp.hostinger.com')
                            ->required(),
                        Forms\Components\TextInput::make('mail_port')
                            ->label('SMTP Port')
                            ->placeholder('587')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('mail_username')
                            ->label('Username / Email')
                            ->placeholder('noreply@yourdomain.com')
                            ->required(),
                        Forms\Components\TextInput::make('mail_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->placeholder('Leave blank to keep existing password'),
                        Forms\Components\Select::make('mail_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS (port 587)',
                                'ssl' => 'SSL (port 465)',
                                '' => 'None',
                            ])
                            ->required(),
                    ]),
                Forms\Components\Section::make('Sender Identity')
                    ->description('The name and address that recipients will see')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('mail_from_address')
                            ->label('From Address')
                            ->email()
                            ->placeholder('noreply@yourdomain.com')
                            ->required(),
                        Forms\Components\TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->placeholder(config('app.name'))
                            ->required(),
                    ]),
                Forms\Components\Section::make('Send Test Email')
                    ->description('Send a test email to verify your configuration after saving')
                    ->schema([
                        Forms\Components\TextInput::make('test_email')
                            ->label('Test Recipient')
                            ->email()
                            ->placeholder('you@example.com'),
                    ]),
            ])
            ->statePath('');
    }

    public function save(): void
    {
        $this->validate([
            'mail_mailer' => ['required', 'string'],
            'mail_host' => ['required_if:mail_mailer,smtp', 'nullable', 'string'],
            'mail_port' => ['required_if:mail_mailer,smtp', 'nullable', 'numeric'],
            'mail_username' => ['required_if:mail_mailer,smtp', 'nullable', 'string'],
            'mail_encryption' => ['nullable', 'string'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
        ]);

        $data = [
            'mail_mailer' => $this->mail_mailer,
            'mail_host' => $this->mail_host,
            'mail_port' => $this->mail_port,
            'mail_username' => $this->mail_username,
            'mail_encryption' => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name' => $this->mail_from_name,
        ];

        // Only update password if a new one was entered
        if ($this->mail_password && $this->mail_password !== '********') {
            $data['mail_password'] = encrypt($this->mail_password);
        }

        Setting::setMany($data);

        Notification::make()
            ->title('Email settings saved successfully')
            ->success()
            ->send();
    }

    public function sendTestEmail(): void
    {
        $this->validate(['test_email' => ['required', 'email']]);

        try {
            Mail::raw('This is a test email from '.config('app.name').'. Your email configuration is working correctly.', function ($message) {
                $message->to($this->test_email)
                    ->subject('Test Email — '.config('app.name'));
            });

            Notification::make()
                ->title('Test email sent to '.$this->test_email)
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Failed to send test email')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTestEmail')
                ->label('Send Test Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('gray')
                ->action('sendTestEmail'),
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
