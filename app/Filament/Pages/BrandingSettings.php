<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form as FormComponent;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class BrandingSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Branding';
    protected static ?int $navigationSort = 99;

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::instance();

        $this->form->fill([
            'app_name' => $settings->app_name,
            'logo'     => $settings->logo,
            'favicon'  => $settings->favicon,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $settings = Setting::instance();

        return $schema->components([
            Section::make('App Name')
                ->schema([
                    TextInput::make('app_name')
                        ->label('Application name')
                        ->required()
                        ->maxLength(100),
                ]),

            Section::make('Logo')
                ->description('Displayed in the nav bar and panel headers. Recommended: SVG or PNG, max 2 MB.')
                ->schema([
                    FileUpload::make('logo')
                        ->label('')
                        ->disk('r2')
                        ->directory('settings/logo')
                        ->visibility('public')
                        ->image()
                        ->maxSize(2048)
                        ->imagePreviewHeight('80')
                        ->reorderable(false)
                        ->deletable(true)
                        ->maxFiles(1),

                    Placeholder::make('logo_preview')
                        ->label('Current logo')
                        ->content(fn () => $settings->logo_url
                            ? new HtmlString('<img src="' . e($settings->logo_url) . '" alt="Logo" class="h-12 max-w-xs object-contain rounded border border-gray-200 bg-white p-1">')
                            : new HtmlString('<span class="text-sm text-gray-400">No logo uploaded</span>')
                        )
                        ->visible(fn () => (bool) $settings->logo_url),
                ]),

            Section::make('Favicon')
                ->description('Browser tab icon. Recommended: 32×32 ICO or PNG, max 1 MB.')
                ->schema([
                    FileUpload::make('favicon')
                        ->label('')
                        ->disk('r2')
                        ->directory('settings/favicon')
                        ->visibility('public')
                        ->image()
                        ->maxSize(1024)
                        ->imagePreviewHeight('48')
                        ->reorderable(false)
                        ->deletable(true)
                        ->maxFiles(1),

                    Placeholder::make('favicon_preview')
                        ->label('Current favicon')
                        ->content(fn () => $settings->favicon_url
                            ? new HtmlString('<img src="' . e($settings->favicon_url) . '" alt="Favicon" class="h-8 w-8 object-contain rounded border border-gray-200 bg-white p-1">')
                            : new HtmlString('<span class="text-sm text-gray-400">No favicon uploaded</span>')
                        )
                        ->visible(fn () => (bool) $settings->favicon_url),
                ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            FormComponent::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    SchemaActions::make($this->getFormActions())
                        ->key('form-actions'),
                ]),
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = Setting::instance();

        $logo    = is_array($data['logo']) ? ($data['logo'][0] ?? null) : ($data['logo'] ?? null);
        $favicon = is_array($data['favicon']) ? ($data['favicon'][0] ?? null) : ($data['favicon'] ?? null);

        if ($logo !== $settings->logo && $settings->logo) {
            Storage::disk('r2')->delete($settings->logo);
        }
        if ($favicon !== $settings->favicon && $settings->favicon) {
            Storage::disk('r2')->delete($settings->favicon);
        }

        $settings->update([
            'app_name' => $data['app_name'],
            'logo'     => $logo,
            'favicon'  => $favicon,
        ]);

        Cache::forget('app_settings');

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
