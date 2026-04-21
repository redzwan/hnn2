<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PaymentSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payment Settings';

    protected static ?string $title = 'Payment Gateway Configuration';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'settings/payment';

    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'filament.pages.settings.payment';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'billplz_enabled' => (bool) Setting::get('billplz.enabled', false),
            'billplz_api_key' => Setting::get('billplz.api_key', ''),
            'billplz_collection_id' => Setting::get('billplz.collection_id', ''),
            'billplz_x_signature_key' => Setting::get('billplz.x_signature_key', ''),
            'billplz_mode' => Setting::get('billplz.mode', 'sandbox'),
            'billplz_payment_methods' => json_decode(Setting::get('billplz.payment_methods', '["fpx","credit_card"]'), true),
            'paypal_enabled' => (bool) Setting::get('paypal.enabled', false),
            'paypal_client_id' => Setting::get('paypal.client_id', ''),
            'paypal_client_secret' => Setting::get('paypal.client_secret', ''),
            'paypal_mode' => Setting::get('paypal.mode', 'sandbox'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('BillPlz Configuration')
                    ->description('Configure your BillPlz payment gateway for FPX and credit card payments.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Toggle::make('billplz_enabled')
                            ->label('Enable BillPlz'),
                        Forms\Components\TextInput::make('billplz_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->helperText('Get your API key from the BillPlz dashboard.'),
                        Forms\Components\TextInput::make('billplz_collection_id')
                            ->label('Collection ID')
                            ->helperText('Create a collection in the BillPlz dashboard.'),
                        Forms\Components\TextInput::make('billplz_x_signature_key')
                            ->label('xSignature Key')
                            ->password()
                            ->revealable()
                            ->helperText('Webhook signature key from BillPlz.'),
                        Forms\Components\Select::make('billplz_mode')
                            ->label('Mode')
                            ->options([
                                'sandbox' => 'Sandbox (Testing)',
                                'production' => 'Production (Live)',
                            ]),
                        Forms\Components\Placeholder::make('webhook_url')
                            ->label('Webhook / Callback URL')
                            ->content(fn () => url('/api/billplz/webhook'))
                            ->helperText('Enter this URL in your BillPlz collection callback settings.'),
                        Forms\Components\CheckboxList::make('billplz_payment_methods')
                            ->label('Payment Methods')
                            ->options([
                                'fpx' => 'FPX (Online Banking)',
                                'credit_card' => 'Credit / Debit Card',
                            ])
                            ->descriptions([
                                'fpx' => 'Accept online banking payments via FPX.',
                                'credit_card' => 'Accept Visa and Mastercard payments.',
                            ]),
                    ]),
                Forms\Components\Section::make('PayPal (Australia)')
                    ->description('Accept PayPal payments in Australian Dollars (AUD).')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Toggle::make('paypal_enabled')
                            ->label('Enable PayPal'),
                        Forms\Components\TextInput::make('paypal_client_id')
                            ->label('Client ID')
                            ->helperText('Your PayPal app Client ID from the PayPal Developer Dashboard.'),
                        Forms\Components\TextInput::make('paypal_client_secret')
                            ->label('Client Secret')
                            ->password()
                            ->revealable()
                            ->helperText('Your PayPal app Client Secret from the PayPal Developer Dashboard.'),
                        Forms\Components\Select::make('paypal_mode')
                            ->label('Mode')
                            ->options([
                                'sandbox' => 'Sandbox (Testing)',
                                'production' => 'Production (Live)',
                            ]),
                        Forms\Components\Placeholder::make('paypal_return_url')
                            ->label('Return URL')
                            ->content(fn () => url('/checkout/paypal/return/{order}'))
                            ->helperText('Configure this as the return URL in your PayPal app settings.'),
                        Forms\Components\Placeholder::make('paypal_currency_info')
                            ->label('Currency')
                            ->content('AUD (Australian Dollar)')
                            ->helperText('PayPal payments are processed in Australian Dollars.'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action(function () {
                    $data = $this->form->getState();

                    Setting::setMany([
                        'billplz.enabled' => $data['billplz_enabled'] ? '1' : '0',
                        'billplz.api_key' => $data['billplz_api_key'] ?? '',
                        'billplz.collection_id' => $data['billplz_collection_id'] ?? '',
                        'billplz.x_signature_key' => $data['billplz_x_signature_key'] ?? '',
                        'billplz.mode' => $data['billplz_mode'] ?? 'sandbox',
                        'billplz.payment_methods' => json_encode($data['billplz_payment_methods'] ?? []),
                        'paypal.enabled' => $data['paypal_enabled'] ? '1' : '0',
                        'paypal.client_id' => $data['paypal_client_id'] ?? '',
                        'paypal.client_secret' => $data['paypal_client_secret'] ?? '',
                        'paypal.mode' => $data['paypal_mode'] ?? 'sandbox',
                    ]);

                    Notification::make()
                        ->title('Payment settings saved successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
