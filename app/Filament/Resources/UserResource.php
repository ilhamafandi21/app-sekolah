<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Manajemen';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Lengkap')
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Select::make('role')
                ->label('Role')
                ->options(Role::pluck('name', 'name'))
                ->visible(fn (string $context) => $context === 'create'),

            DateTimePicker::make('email_verified_at')
                ->label('Email Verified At'),

            TextInput::make('password')
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
        /** @var Builder $query */
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
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                // Kolom relasi roles — aman untuk many-to-many
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('success')
                    ->listWithLineBreaks(),

                TextColumn::make('email_verified_at')
                    ->label('Email Verified At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter jika perlu. Hindari filter yang menyentuh relasi tak ada.
                // \Filament\Tables\Filters\TrashedFilter::make(), // aktifkan jika perlu
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
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
