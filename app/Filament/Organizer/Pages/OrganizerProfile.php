<?php

namespace App\Filament\Organizer\Pages;

use App\Models\Organizer;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form as FormComponent;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use BackedEnum;
use Livewire\Attributes\Locked;

class OrganizerProfile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?int $navigationSort = -1;

    protected static ?string $title = 'My Profile';

    // ── State ──────────────────────────────────────────────────────────────────

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    #[Locked]
    public int $organizerId;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $organizer = Organizer::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name, 'email' => $user->email]
        );

        $this->organizerId = $organizer->id;

        $this->form->fill($organizer->toArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->operation('edit')
            ->model(Organizer::class)
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('display_name')
                ->label('Display Name')
                ->required(),

            TextInput::make('slug')
                ->label('Slug')
                ->live(debounce: 500)
                ->unique(
                    table: 'organizers',
                    column: 'slug',
                    ignorable: fn ($livewire) => Organizer::find($livewire->organizerId),
                )
                ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ->helperText('Lowercase letters, numbers and hyphens only.')
                ->hint(function (Get $get, $livewire): ?string {
                    $slug = $get('slug');
                    if (blank($slug)) {
                        return null;
                    }
                    $taken = Organizer::where('slug', $slug)
                        ->where('id', '!=', $livewire->organizerId)
                        ->exists();

                    return $taken ? 'Already taken' : 'Available';
                })
                ->hintColor(function (Get $get, $livewire): string {
                    $slug = $get('slug');
                    if (blank($slug)) {
                        return 'gray';
                    }
                    $taken = Organizer::where('slug', $slug)
                        ->where('id', '!=', $livewire->organizerId)
                        ->exists();

                    return $taken ? 'danger' : 'success';
                }),

            Textarea::make('bio')
                ->nullable(),

            FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->imageEditor()
                ->imageAspectRatio('1:1')
                ->automaticallyCropImagesToAspectRatio()
                ->maxSize(2048)
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/organizers/logo' : 'organizers/logo')
                ->visibility('public')
                ->nullable(),

            FileUpload::make('banner')
                ->label('Banner Image')
                ->image()
                ->imageEditor()
                ->maxSize(4096)
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/organizers/banner' : 'organizers/banner')
                ->visibility('public')
                ->nullable(),

            TextInput::make('email')
                ->email()
                ->nullable(),

            TextInput::make('phone')
                ->nullable(),

            TextInput::make('status')
                ->label('Account Status')
                ->disabled()
                ->dehydrated(false),

            Toggle::make('verified')
                ->label('Verified')
                ->disabled()
                ->dehydrated(false),
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
                ->label('Save Profile')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Organizer::findOrFail($this->organizerId)->update($data);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();
    }
}
