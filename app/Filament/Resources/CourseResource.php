<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Cursos';
    }

    public static function getModelLabel(): string
    {
        return 'Curso';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cursos';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del curso')
                            ->required(),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->required()
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre de la categoría')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->rows(3),
                            ]),
                        Select::make('instructor_id')
                            ->label('Instructor')
                            ->required()
                            ->relationship(
                                'instructor',
                                'id',
                                modifyQueryUsing: fn(Builder $query) =>
                                $query->whereHas('user.roles', function ($q) {
                                    $q->where('name', 'instructor');
                                })
                            )
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name ?? 'Sin nombre'),
                    ]),
                Textarea::make('description')
                    ->label('Descripcion')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TagsInput::make('areas')
                    ->placeholder('Cinematica, aritmetica, fisica...')
                    ->label('Areas academicas del curso')
                    ->helperText('Al agregar un area, presiona ENTER para guardarlo en el registro')
                    ->columnSpanFull()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('category.name')
                    ->label('Categoria'),
                TextColumn::make('instructor.user.name')
                    ->label('Intructor')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->sortable()
                    ->date('d/m/Y h:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\viewCourse::route('/{record}'),
        ];
    }
}
