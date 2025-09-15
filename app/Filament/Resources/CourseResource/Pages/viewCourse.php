<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Card; // ← Cambiar Box por Card
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TagsEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Textarea;

class viewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Actualizar información del curso')
                ->color('primary'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 6
                ])->schema([
                    Card::make()
                        ->columnSpan(4)
                        ->schema([
                            Section::make('Información del curso')
                                ->columns(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('name')
                                            ->label('Nombre del curso'),
                                        TextEntry::make('description')
                                            ->label('Descripción')
                                            ->columnSpanFull(),
                                    ])->columnSpan(1),
                                    TextEntry::make('category.name')
                                        ->label('Categoría')
                                        ->badge()
                                        ->color('info'),
                                ]),
                        ]),

                    Card::make()
                        ->columnSpan(2)
                        ->schema([
                            Section::make('Información General')
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->label('Fecha de creación')
                                        ->date('d/m/Y H:i'),
                                    TextEntry::make('updated_at')
                                        ->label('Última actualización')
                                        ->date('d/m/Y H:i'),
                                ]),
                        ]),
                ]),

                Grid::make(1)
                    ->schema([
                        Card::make() // ← Cambiar Box::make() por Card::make()
                            ->schema([
                                Section::make('Información del instructor')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('instructor.name')
                                            ->label('Nombre completo')
                                            ->icon('heroicon-o-user'),
                                        TextEntry::make('instructor.email')
                                            ->label('Correo electrónico')
                                            ->icon('heroicon-o-envelope'),
                                        TextEntry::make('category_id')
                                            ->label('Especialidad de enseñanza')
                                            ->formatStateUsing(function ($state) {
                                                return \App\Models\Category::find($state)?->name ?? 'Sin especialidad';
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
