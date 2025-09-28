<?php
// app/Filament/Resources/InstructorRequestResource/Pages/ViewInstructorRequest.php

namespace App\Filament\Resources\InstructorRequestResource\Pages;

use App\Filament\Resources\InstructorRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Instructor;

class ViewInstructorRequest extends ViewRecord
{
    protected static string $resource = InstructorRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción para aprobar
            Actions\Action::make('approve')
                ->label('Aprobar Solicitud')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => $this->record->status !== 'approved')
                ->requiresConfirmation()
                ->modalHeading('Aprobar Solicitud de Instructor')
                ->modalDescription('¿Estás seguro de que quieres aprobar esta solicitud?')
                ->action(function () {
                    try {
                        DB::transaction(function () {
                            // Crear el User
                            $user = User::create([
                                'name' => $this->record->name,
                                'email' => $this->record->email,
                                'password' => Hash::make('password'),
                            ]);

                            // Asignar rol de instructor (Laravel Permission)
                            $user->assignRole('instructor');

                            // Crear el registro en tabla instructors
                            $instructor = Instructor::create([
                                'user_id' => $user->id,
                                'biography' => $this->record->biography,
                                'academic_level' => $this->record->academic_level,
                                'subject_specialties' => $this->record->subject_specialties,
                                'schedule_availability' => $this->record->schedule_availability,
                                'subjects' => $this->record->subjects,
                                'level_education' => $this->record->level_education,
                                'category_id' => 1,
                            ]);

                            // Copiar certificaciones de Media Library
                            // $this->copyMediaToInstructor($instructor);

                            // Actualizar estado de la solicitud
                            $this->record->update([
                                'status' => 'approved',
                                'admin_notes' => 'Solicitud aprobada el ' . now()->format('d/m/Y H:i') .
                                    '. Usuario creado con ID: ' . $user->id .
                                    '. Instructor creado con ID: ' . $instructor->id,
                            ]);
                        });

                        Notification::make()
                            ->title('¡Solicitud aprobada exitosamente!')
                            ->body('Se creó el usuario y perfil de instructor correctamente.')
                            ->success()
                            ->duration(5000)
                            ->send();
                    } catch (\Exception $e) {
                        \Log::error('Error aprobando solicitud de instructor: ' . $e->getMessage(), [
                            'instructor_request_id' => $this->record->id,
                            'trace' => $e->getTraceAsString()
                        ]);

                        Notification::make()
                            ->title('Error al aprobar solicitud')
                            ->body('Ocurrió un error al procesar la solicitud.')
                            ->danger()
                            ->send();
                    }
                }),

            // Acción para rechazar
            Actions\Action::make('reject')
                ->label('Rechazar Solicitud')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn() => $this->record->status !== 'rejected')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Motivo del rechazo')
                        ->required()
                        ->rows(3)
                        ->placeholder('Explica brevemente por qué se rechaza esta solicitud...'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'admin_notes' => 'Solicitud rechazada el ' . now()->format('d/m/Y H:i')
                    ]);

                    Notification::make()
                        ->title('Solicitud rechazada')
                        ->warning()
                        ->send();
                }),

            // Acción para agregar notas administrativas
            Actions\Action::make('add_admin_notes')
                ->label('Notas Administrativas')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->form([
                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Notas administrativas')
                        ->default($this->record->admin_notes)
                        ->rows(4)
                        ->placeholder('Agrega notas internas sobre esta solicitud...'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'admin_notes' => $data['admin_notes'],
                    ]);

                    Notification::make()
                        ->title('Notas administrativas actualizadas')
                        ->success()
                        ->send();
                }),

            // Actions\EditAction::make(),
        ];
    }

    // /**
    //  * Copiar certificaciones de InstructorRequest a Instructor
    //  */
    // private function copyMediaToInstructor(Instructor $instructor): void
    // {
    //     $certificates = $this->record->getMedia('instructor_certificates');

    //     foreach ($certificates as $media) {
    //         // Copiar archivo a la nueva colección del instructor
    //         $instructor
    //             ->addMediaFromUrl($media->getUrl())
    //             ->usingName($media->name)
    //             ->usingFileName($media->file_name)
    //             ->toMediaCollection('instructor_certificates');
    //     }
    // }
}
