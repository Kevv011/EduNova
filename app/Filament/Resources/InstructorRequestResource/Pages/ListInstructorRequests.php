<?php

namespace App\Filament\Resources\InstructorRequestResource\Pages;

use App\Filament\Resources\InstructorRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstructorRequests extends ListRecords
{
    protected static string $resource = InstructorRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
