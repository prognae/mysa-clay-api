<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

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
                            ->columnSpanFull(),
                    ]),
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(2),

                        Select::make('collection_id')  
                            ->label('Collection')
                            ->options(
                                Collection::all()->pluck('name', 'id') 
                            )
                            ->searchable() 
                            ->required()
                            ->columnSpan(2),

                        Select::make('category_id')  
                            ->label('Category')
                            ->options(
                                Category::all()->pluck('name', 'id') 
                            )
                            ->searchable() 
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('price')
                            ->numeric()
                            ->prefix('₱')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->required(),

                        MarkdownEditor::make('description')
                            ->required()
                            ->columnSpan('full'),
                    ])     
                    ->columns(8)           
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

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('collection.name')
                    ->label('Collection')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(100),

                TextColumn::make('price')
                    ->label('Price')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('In Stock')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->status === 1 ? 'Activated' : 'Deactivated' )
                    ->color(fn ($record) => $record->status === 1 ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->label('Date Posted')
                    ->date() 
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
