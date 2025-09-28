<?php

namespace App\Filament\Resources\InstructorRequestResource\Pages;

use App\Filament\Resources\InstructorRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstructorRequest extends EditRecord
{
    protected static string $resource = InstructorRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
