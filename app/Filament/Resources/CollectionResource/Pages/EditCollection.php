<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use Filament\Actions;
use App\Models\Collection;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CollectionResource;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
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
