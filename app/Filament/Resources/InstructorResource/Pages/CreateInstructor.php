<?php

namespace App\Filament\Resources\InstructorResource\Pages;

use App\Filament\Resources\InstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CreateInstructor extends CreateRecord
{
    protected static string $resource = InstructorResource::class;

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return Notification::make()
            ->title('Instructor creado correctamente')
            ->success()
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::where('email', $data['email'])->first();          // Obtener email si existe
        $emailCount = User::where('email', $data['email'])->count();    // Verificar que el email sea unico

        if ($emailCount > 0) {
            Notification::make()
                ->title('Ya existe un instructor con este correo')
                ->danger()
                ->send();

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Ya existe un instructor registrado con este correo.',
            ]);
        }

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('instructor');

            Notification::make()
                ->title('Usuario creado correctamente')
                ->success()
                ->send();
        }

        $data['user_id'] = $user->id;
        return $data;
    }
}
