<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PelangganResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use App\Filament\Resources\PelangganResource\RelationManagers;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                Textarea::make('alamat')->required(),
                TextInput::make('no_hp'),
                TextInput::make('tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('alamat')->limit(20),
                TextColumn::make('no_hp'),
                TextColumn::make('tagihan')->money('IDR'),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('status_bayar')
                    ->label('Status Bayar')
                    ->formatStateUsing(fn($state) => $state ? 'LUNAS' : 'BELUM BAYAR')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('Jatuh Tempo Hari Ini')
                    ->query(
                        fn($query) => $query
                            ->where('tanggal_jatuh_tempo', Carbon::today())
                            ->where('status_bayar', false)
                    ),
            ])
            ->headerActions([
                Action::make('Reset Semua')
                    ->label('Reset Semua Status Bayar')
                    ->icon('heroicon-m-arrow-path')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        $pelanggans = Pelanggan::all();

                        foreach ($pelanggans as $pelanggan) {
                            $jatuhTempo = Carbon::parse($pelanggan->tanggal_jatuh_tempo);
                            $today = now();

                            if ($jatuhTempo->lt($today)) {
                                $jatuhTempo->addMonthNoOverflow()->day(25);
                            }

                            $pelanggan->update([
                                'status_bayar' => false,
                                'tanggal_jatuh_tempo' => $jatuhTempo,
                            ]);
                        }

                        Notification::make()
                            ->title('Berhasil Reset Semua')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('Bayar')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->visible(fn($record) => !$record->status_bayar)
                    ->action(fn($record) => $record->update(['status_bayar' => true]))
                    ->requiresConfirmation()
                    ->successNotification(
                        Notification::make()
                            ->title('Status Bayar Diubah')
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
