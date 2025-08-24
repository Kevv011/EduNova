<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationLabel(): string
    {
        return 'Instructores';
    }

    public static function getModelLabel(): string
    {
        return 'Instructor';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Instructores';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('user_id')
                            ->label('Nombre del instructor')
                            ->options(\App\Models\User::all()->pluck('name', 'id'))
                            ->visibleOn('edit')
                            ->disabled()
                            ->dehydrated(false)
                            ->required()
                            ->helperText('Editar nombre desde el usuario del instructor'),
                        TextInput::make('name')
                            ->label('Nombre del instructor')
                            ->required()
                            ->visibleOn('create'),
                        Select::make('category_id')
                            ->label('Especialidad de enseñanza')
                            ->required()
                            ->searchable()
                            ->relationship('category', 'name')
                            ->preload(),
                        Toggle::make('status')
                            ->label('Activo')
                            ->default(true),
                    ]),
                TextArea::make('biography')
                    ->label('Biografia')
                    ->columnSpanFull()
                    ->rows(3),
                Select::make('user_id')
                    ->label('Correo electrónico registrado')
                    ->options(\App\Models\User::all()->pluck('email', 'id'))
                    ->required()
                    ->disabled()
                    ->visibleOn('edit')
                    ->dehydrated(false)
                    ->helperText('Editar correo desde el usuario del estudiante'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->visibleOn('create')
                    ->helperText('En caso de que existe el usuario del instructor, utilizar el correo con el que se registro.'),

                Repeater::make('social_links')
                    ->label('Redes Sociales')
                    ->columnSpanFull()
                    ->default([])
                    ->schema([
                        Select::make('platform')
                            ->label('Red Social')
                            ->options([
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'instagram' => 'Instagram',
                                'youtube' => 'YouTube',
                            ])
                            ->required(),
                        TextInput::make('url')
                            ->label('Enlace')
                            ->required()
                            ->placeholder('https://www.linkedin.com/usuario'),
                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('user.name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Especialidad'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state == 1 ? 'Activo' : 'Inactivo'),
                TextColumn::make('created_at')
                    ->date('d/m/Y H:i')
                    ->label('Creado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
