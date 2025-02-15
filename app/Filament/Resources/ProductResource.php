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
use Illuminate\Validation\Rule;

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
                            ->label('Original Price')
                            ->columnSpan(2)
                            ->afterStateUpdated(fn ($set, $state, $get) => 
                                $set('final_price', $get('discounted_price') != null ? $get('discounted_price') : $state - ($state * ($get('markdown') != null ? $get('markdown') * 0.01 : 0)))
                            )
                            ->reactive()
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->columnSpan(1)
                            ->required(),

                        Select::make('is_discounted')  
                            ->label('Discounted')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->default(0)
                            ->searchable() 
                            ->required()
                            ->reactive()
                            ->columnSpan(1),

                        TextInput::make('discounted_price')
                            ->numeric()
                            ->prefix('₱')
                            ->label('Discounted Price')
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('is_discounted') == 1)
                            ->disabled(fn ($get) => $get('markdown') != null)
                            ->dehydrated(true)
                            ->afterStateUpdated(fn ($set, $state, $get) => 
                                $set('final_price', $state != null ? $state : $get('price') - ($get('price') * ($get('markdown') != null ? $get('markdown') * 0.01 : 0)))
                            )                            
                            ->reactive()
                            ->rule(function ($state, $get) {
                                return Rule::when(
                                    $state !== null,
                                    fn () => 'lt:' . ($get('price') ?? 0) 
                                );
                            })
                            ->helperText('Must be lower than the original price.'),

                        // TextInput::make('markup')
                        //     ->numeric()
                        //     ->suffix('%')
                        //     ->label('Markup')
                        //     ->columnSpan(1)
                        //     ->visible(fn ($get) => $get('is_discounted') == 1)
                        //     ->reactive()
                        //     ->afterStateUpdated(fn ($set, $state, $get) => 
                        //         $set('final_price', $get('price') + ($get('price') * ($state * .01)))
                        //     )
                        //     ->disabled(fn ($get) => $get('discounted_price') != null || $get('markdown') != null),
                        //     // ->dehydrated(fn ($get) => $get('discounted_price') != null),

                        TextInput::make('markdown')
                            ->numeric()
                            ->suffix('%')
                            ->label('Markdown')
                            ->columnSpan(1)
                            ->visible(fn ($get) => $get('is_discounted') == 1)
                            ->reactive()
                            ->afterStateUpdated(fn ($set, $state, $get) => 
                                $set('final_price', $get('price') - ($get('price') * ($state * 0.01)))
                            )
                            ->disabled(fn ($get) => $get('discounted_price') != null)
                            ->dehydrated(true),

                        TextInput::make('final_price')
                            ->numeric()
                            ->prefix('₱')
                            ->label('Final Price')
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('is_discounted') == 1)
                            ->disabled()
                            ->dehydrated(true)
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
