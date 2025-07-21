<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter; // Import Filter
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    // (BARU) Fungsi ini memastikan pengguna hanya melihat datanya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Tugas')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Belum dikerjakan' => 'Belum dikerjakan',
                        'Sedang dikerjakan' => 'Sedang dikerjakan',
                        'Selesai' => 'Selesai',
                    ])
                    ->required()
                    ->default('Belum dikerjakan'),
                
                Forms\Components\DatePicker::make('due_date')
                    ->label('Tenggat Waktu'),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpan('full'),
                
                // user_id tetap di-handle secara otomatis di halaman Create
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Belum dikerjakan' => 'gray',
                        'Sedang dikerjakan' => 'warning',
                        'Selesai' => 'success',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tenggat Waktu')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Belum dikerjakan' => 'Belum dikerjakan',
                        'Sedang dikerjakan' => 'Sedang dikerjakan',
                        'Selesai' => 'Selesai',
                    ]),

                // (BARU) Filter berdasarkan tenggat waktu
                Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('due_date_filter')->label('Tugas dengan tenggat'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_date_filter'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    // Pastikan halaman 'create' mengisi user_id secara otomatis
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}