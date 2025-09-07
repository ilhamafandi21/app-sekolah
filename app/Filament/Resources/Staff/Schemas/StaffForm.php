<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('position')
                    ->options([
                        'hrd' => 'HRD',
                        'keuangan' => 'Keuangan',
                        'operasional' => 'Operasional',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->dehydrated()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrated()
                    ->required(),
                TextInput::make('alamat'),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tanggal_lahir'),
                DatePicker::make('join_at'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
