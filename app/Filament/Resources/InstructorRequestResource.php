<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorRequestResource\Pages;
use App\Filament\Resources\InstructorRequestResource\RelationManagers;
use App\Models\InstructorRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class InstructorRequestResource extends Resource
{
    protected static ?string $model = InstructorRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return 'Solicitud de instructores';
    }

    public static function getModelLabel(): string
    {
        return 'Solicitud de instructor';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Solicitud de instructores';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ImageColumn::make('profile_photo_path')
                    ->label('Perfil')
                    ->circular()
                    ->size(50)
                    ->disk('public')
                    ->alignCenter()
                    ->defaultImageUrl(asset('images/user-default.jpeg')),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('academic_level')
                    ->label('Nivel academico')
                    ->searchable(),
                TextColumn::make('level_education')
                    ->label('Educacion que imparte')
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')

            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListInstructorRequests::route('/'),
            'create' => Pages\CreateInstructorRequest::route('/create'),
            'edit' => Pages\EditInstructorRequest::route('/{record}/edit'),
            'view' => Pages\ViewInstructorRequest::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Sección de Información Personal
                Infolists\Components\Section::make('Información Personal')
                    ->description('Datos básicos del solicitante')
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Group::make([
                                Infolists\Components\ImageEntry::make('profile_photo_path')
                                    ->label('Foto de perfil')
                                    ->disk('public')
                                    ->height(150)
                                    ->width(150)
                                    ->circular()
                                    ->defaultImageUrl(asset('images/user-default.jpeg')),

                                Infolists\Components\TextEntry::make('status')
                                    ->label('Estado de la solicitud')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'pending' => 'Pendiente',
                                        'approved' => 'Aprobada',
                                        'rejected' => 'Rechazada',
                                        default => 'Sin estado',
                                    }),
                            ])->columnSpan(1),

                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nombre completo')
                                    ->weight(FontWeight::Bold)
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->icon('heroicon-o-user'),

                                Infolists\Components\TextEntry::make('email')
                                    ->label('Correo electrónico')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessage('Email copiado')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('phone')
                                    ->label('Teléfono')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->placeholder('No proporcionado'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Fecha de solicitud')
                                    ->icon('heroicon-o-calendar')
                                    ->dateTime('d/m/Y H:i'),
                            ])->columnSpan(2),
                        ])->columnSpanFull(),
                    ]),

                // Sección de biografía
                Infolists\Components\Section::make('Biografía Profesional')
                    ->description('Presentación y experiencia del instructor')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\TextEntry::make('biography')
                            ->label('Biografía')
                            ->prose()
                            ->columnSpanFull()
                            ->placeholder('No se proporcionó biografía'),
                    ]),

                // Seccion de formación Académica
                Infolists\Components\Section::make('Formación Académica')
                    ->description('Nivel educativo y especialidades')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('academic_level')
                                    ->label('Nivel académico')
                                    ->badge()
                                    ->color('info')
                                    ->formatStateUsing(
                                        fn(?string $state): string =>
                                        $state ? ucfirst($state) : 'No especificado'
                                    ),

                                Infolists\Components\TextEntry::make('subject_specialties')
                                    ->label('Áreas de especialización')
                                    ->badge()
                                    ->separator(',')
                                    ->color('success')
                                    ->placeholder('No especificadas')
                                    ->formatStateUsing(function ($state): string {
                                        if (is_array($state)) {
                                            return implode(', ', $state);
                                        }

                                        if (is_string($state) && !empty($state)) {
                                            return $state;
                                        }

                                        return 'No especificadas';
                                    }),
                            ]),

                        Infolists\Components\TextEntry::make('subjects')
                            ->label('Materias que puede impartir')
                            ->prose()
                            ->columnSpanFull()
                            ->placeholder('No especificadas')
                            ->formatStateUsing(function ($state): string {
                                if (is_array($state)) {
                                    return implode(', ', $state);
                                }
                                return $state ?? 'No especificadas';
                            }),

                        Infolists\Components\TextEntry::make('level_education')
                            ->label('Niveles educativos que puede enseñar')
                            ->badge()
                            ->separator(',')
                            ->color('warning')
                            ->placeholder('No especificados')
                            ->formatStateUsing(function ($state): string {
                                if (is_array($state)) {
                                    return implode(', ', $state);
                                }

                                if (is_string($state) && !empty($state)) {
                                    return $state;
                                }

                                return 'No especificados';
                            }),
                    ]),

                // Seccion de disponibilidad
                Infolists\Components\Section::make('Disponibilidad de Horarios')
                    ->description('Días disponibles para atender')
                    ->icon('heroicon-o-clock')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\TextEntry::make('schedule_availability')
                            ->label('Días disponibles')
                            ->badge()
                            ->separator(',')
                            ->color('purple')
                            ->placeholder('No especificados')
                            ->formatStateUsing(function ($state): string {
                                if (!$state) return 'No especificados';

                                $days = [
                                    'lunes' => 'Lunes',
                                    'martes' => 'Martes',
                                    'miercoles' => 'Miércoles',
                                    'jueves' => 'Jueves',
                                    'viernes' => 'Viernes',
                                    'sabado' => 'Sábado',
                                    'domingo' => 'Domingo',
                                ];

                                if (is_array($state)) {
                                    $translatedDays = array_map(fn($day) => $days[$day] ?? ucfirst($day), $state);
                                    return implode(', ', $translatedDays);
                                }

                                if (is_string($state)) {
                                    $daysArray = explode(',', $state);
                                    $translatedDays = array_map(fn($day) => $days[trim($day)] ?? ucfirst(trim($day)), $daysArray);
                                    return implode(', ', $translatedDays);
                                }

                                return 'No especificados';
                            }),
                    ]),

                // Seccion de certificaciones
                Infolists\Components\Section::make('Certificaciones y Documentos')
                    ->description('Títulos y certificados subidos')
                    ->icon('heroicon-o-document-check')
                    ->collapsible()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('instructor_certificates')
                            ->collection('instructor_certificates')
                            ->label('Certificaciones')
                            ->height(200)
                            ->width(200)
                            ->placeholder('No hay certificaciones subidas')
                            ->columnSpanFull(),
                    ]),

                // Seccion de notas Administrativas
                Infolists\Components\Section::make('Información Administrativa')
                    ->description('Notas internas y estado de la solicitud')
                    ->icon('heroicon-o-shield-check')
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn($record) => $record->admin_notes || $record->rejection_reason)
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Notas administrativas')
                            ->prose()
                            ->placeholder('Sin notas administrativas')
                            ->visible(fn($record) => $record->admin_notes),

                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->label('Motivo de rechazo')
                            ->prose()
                            ->color('danger')
                            ->icon('heroicon-o-x-circle')
                            ->visible(fn($record) => $record->rejection_reason),
                    ]),
            ]);
    }
}
