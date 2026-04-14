<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ThemeSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'Theme';

    protected static ?string $title = 'Storefront Theme';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'settings/theme';

    protected static string $view = 'filament.pages.settings.theme';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'active_theme' => Setting::get('theme.active', 'default'),
        ]);
    }

    public function form(Form $form): Form
    {
        $themes = config('themes', []);

        $options = collect($themes)->mapWithKeys(
            fn (array $theme, string $key) => [$key => $theme['name']]
        )->toArray();

        $descriptions = collect($themes)->mapWithKeys(
            fn (array $theme, string $key) => [$key => $theme['description']]
        )->toArray();

        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Choose Theme')
                    ->description('Select a visual theme for your public-facing storefront. The admin panel is not affected.')
                    ->schema([
                        Forms\Components\Radio::make('active_theme')
                            ->label('Active Theme')
                            ->options($options)
                            ->descriptions($descriptions)
                            ->required(),
                        Forms\Components\ViewField::make('theme_previews')
                            ->label('Theme Previews')
                            ->view('filament.pages.settings.theme-previews'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Theme')
                ->action(function () {
                    $data = $this->form->getState();

                    Setting::set('theme.active', $data['active_theme']);

                    Notification::make()
                        ->title('Theme updated successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
