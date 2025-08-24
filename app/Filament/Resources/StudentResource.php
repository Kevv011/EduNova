<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
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

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function getNavigationLabel(): string
    {
        return 'Estudiantes';
    }

    public static function getModelLabel(): string
    {
        return 'Estudiante';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Estudiantes';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Nombre del estudiante')
                    ->options(\App\Models\User::all()->pluck('name', 'id'))
                    ->visibleOn('edit')
                    ->disabled()
                    ->dehydrated(false)
                    ->required()
                    ->helperText('Editar nombre desde el usuario del estudiante'),
                TextInput::make('name')
                    ->label('Nombre del estudiante')
                    ->required()
                    ->visibleOn('create'),
                Select::make('academic_level')
                    ->label('Nivel academico')
                    ->required()
                    ->options(function ($record) {
                        $options = [
                            'tercer ciclo' => 'Tercer ciclo',
                            'bachillerato' => 'Bachillerato',
                            'universitario' => 'Universitario',
                        ];
                        if ($record && $record->academic_level && !array_key_exists($record->academic_level, $options)) {
                            $options[$record->academic_level] = $record->academic_level;
                        }
                        return $options;
                    }),
                TextInput::make('institution')
                    ->label('Institucion')
                    ->required(),
                DatePicker::make('date_birth')
                    ->label('Fecha de nacimiento:')
                    ->native(false)
                    ->maxDate(now()),
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
                    ->helperText('En caso de que existe el usuario del estudiante, utilizar el correo con el que se registro.'),
                Toggle::make('status')
                    ->label('Activo')
                    ->default(true)
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
                TextColumn::make('academic_level')
                    ->label('Nivel academico')
                    ->searchable(),
                TextColumn::make('institution')
                    ->label('Institucion')
                    ->searchable(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
