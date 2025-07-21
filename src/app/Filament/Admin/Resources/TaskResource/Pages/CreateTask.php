<?php

namespace App\Filament\Admin\Resources\TaskResource\Pages;

use App\Filament\Admin\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    // Tambahkan method ini
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Secara paksa menambahkan id pengguna yang login ke data
        $data['user_id'] = auth()->id();
 
        return $data;
    }
}