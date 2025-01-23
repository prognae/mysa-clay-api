<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\Collection;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->is_featured == 1) {
            $collections = Collection::where('is_featured', 1)
                ->where('id', '!=', $record->id)
                ->first();

            if (isset($collections)) {
                $collections->update([
                    'is_featured' => 0
                ]);
            }
        }
    }
}
