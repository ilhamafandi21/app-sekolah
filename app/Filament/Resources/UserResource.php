<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers\RoleRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SiswaRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Akses';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Lengkap')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\Select::make('role')
                ->label('Role')
                ->options(Role::pluck('name', 'name'))
                ->visible(fn (string $context) => $context === 'create'),

            Forms\Components\DateTimePicker::make('email_verified_at')
                ->label('Email Verified At'),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->visible(fn (string $context) => $context === 'create'),
        ]);
    }

    /**
     * Pastikan selalu mengembalikan Eloquent\Builder dengan model yang benar.
     * Sekalian eager load roles untuk mencegah N+1.
     */
    public static function getEloquentQuery(): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = static::getModel()::query();

        return $query->with('roles');
        // Jika ingin menyertakan soft deletes yang terhapus, tambah:
        // ->withoutGlobalScopes([ \Illuminate\Database\Eloquent\SoftDeletingScope::class ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Tidak wajib set query di sini karena sudah dijamin di getEloquentQuery().
            // ->query(fn (Builder $query) => $query->with('roles'))
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10,])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                // Kolom relasi roles — aman untuk many-to-many
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('success')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Verified At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter jika perlu. Hindari filter yang menyentuh relasi tak ada.
                // \Filament\Tables\Filters\TrashedFilter::make(), // aktifkan jika perlu
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Pastikan hanya daftarkan RelationManager yang FILE-nya benar-benar ada.
            RoleRelationManager::class,
            SiswaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}
