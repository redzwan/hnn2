<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StorageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cloud';

    protected static ?string $navigationLabel = 'Storage Settings';

    protected static ?string $title = 'Storage Settings';

    protected static ?string $slug = 'storage-settings';

    protected static string $view = 'filament.pages.storage-settings';

    public ?string $s3Endpoint = '';

    public ?string $s3Region = '';

    public ?string $s3Bucket = '';

    public ?string $s3Key = '';

    public ?string $s3Secret = '';

    public bool $useS3 = false;

    public function mount(): void
    {
        $this->useS3 = (bool) Setting::get('storage.use_s3', false);
        $this->s3Endpoint = Setting::get('storage.s3_endpoint', '');
        $this->s3Region = Setting::get('storage.s3_region', 'us-east-1');
        $this->s3Bucket = Setting::get('storage.s3_bucket', '');
        $this->s3Key = Setting::get('storage.s3_key', '');
        $this->s3Secret = Setting::get('storage.s3_secret', '');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Storage Provider')
                    ->description('Choose where to store files')
                    ->schema([
                        Toggle::make('useS3')
                            ->label('Use S3/Minio Storage')
                            ->helperText('Enable this to use S3-compatible storage (Minio, AWS S3, etc.)'),
                    ]),
                Section::make('S3/Minio Configuration')
                    ->description('Configure your S3-compatible storage settings')
                    ->schema([
                        TextInput::make('s3Endpoint')
                            ->label('Endpoint URL')
                            ->placeholder('https://play.minio.io:9000')
                            ->url(),
                        TextInput::make('s3Region')
                            ->label('Region')
                            ->placeholder('us-east-1'),
                        TextInput::make('s3Bucket')
                            ->label('Bucket Name')
                            ->required(),
                        TextInput::make('s3Key')
                            ->label('Access Key')
                            ->required(),
                        TextInput::make('s3Secret')
                            ->label('Secret Key')
                            ->password()->revealable(),
                    ]),
            ])
            ->statePath('');
    }

    public function save(): void
    {
        $this->validate([
            's3Bucket' => $this->useS3 ? ['required', 'string'] : ['nullable'],
            's3Key' => $this->useS3 ? ['required', 'string'] : ['nullable'],
            's3Secret' => $this->useS3 ? ['required', 'string'] : ['nullable'],
        ]);

        Setting::set('storage.use_s3', $this->useS3);
        Setting::set('storage.s3_endpoint', $this->s3Endpoint);
        Setting::set('storage.s3_region', $this->s3Region);
        Setting::set('storage.s3_bucket', $this->s3Bucket);
        Setting::set('storage.s3_key', $this->s3Key);
        Setting::set('storage.s3_secret', $this->s3Secret);

        Notification::make()
            ->title('Storage settings saved successfully!')
            ->success()
            ->send();
    }
}
