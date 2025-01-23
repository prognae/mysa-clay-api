<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Collection;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        FileUpload::make('thumbnail_url')
                            ->label('Display Image')
                            ->image() // Specifies that the upload is for an image
                            ->required() // Makes the field mandatory
                            ->directory('uploads/images') // Specifies the storage directory
                            ->maxSize(1024) // Maximum file size in KB
                            // ->imageCropAspectRatio('16:9') // Optional: Crop the image with an aspect ratio
                            // ->imageResizeTargetWidth(1920) // Optional: Resize the image width
                            // ->imageResizeTargetHeight(1080) // Optional: Resize the image height
                            ->placeholder('Upload an Image here')
                            ->columnSpan(2),
                        FileUpload::make('thumbnail_banner_url')
                            ->label('Display Banner')
                            ->image() // Specifies that the upload is for an image
                            // ->required() // Makes the field mandatory
                            ->directory('uploads/images') // Specifies the storage directory
                            ->maxSize(1024) // Maximum file size in KB
                            // ->imageCropAspectRatio('16:9') // Optional: Crop the image with an aspect ratio
                            // ->imageResizeTargetWidth(1920) // Optional: Resize the image width
                            // ->imageResizeTargetHeight(1080) // Optional: Resize the image height
                            ->placeholder('Upload an Image here')
                            ->columnSpan(2),
                    ])
                    ->columns(4),

                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(2),

                        Select::make('is_featured')
                            ->label('Set as Featured?')
                            ->options([
                                1 => 'Yes',
                                0 => 'No'
                            ])
                            ->default(0)
                            ->selectablePlaceholder(false)
                            ->required(),

                        MarkdownEditor::make('description')
                            ->required()
                            ->columnSpan('full'),
                    ])
                    ->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex()
                    ->sortable(),
                ImageColumn::make('thumbnail_url')
                    ->label('Image')
                    ->width(50)
                    ->height(50),
                TextColumn::make('name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->status === 1 ? 'Activated' : 'Deactivated')
                    ->color(fn($record) => $record->status === 1 ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label('Date Posted')
                    ->date()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
