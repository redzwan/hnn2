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
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('BillPlz Configuration')
                    ->description('Configure your BillPlz payment gateway for FPX and credit card payments.')
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
                    ]),
                Forms\Components\Section::make('Payment Methods')
                    ->description('Choose which payment methods to accept via BillPlz.')
                    ->schema([
                        Forms\Components\CheckboxList::make('billplz_payment_methods')
                            ->label('Enabled Payment Methods')
                            ->options([
                                'fpx' => 'FPX (Online Banking)',
                                'credit_card' => 'Credit / Debit Card',
                            ])
                            ->descriptions([
                                'fpx' => 'Accept online banking payments via FPX.',
                                'credit_card' => 'Accept Visa and Mastercard payments.',
                            ]),
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
                    ]);

                    Notification::make()
                        ->title('Payment settings saved successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
