<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Filament\Resources\JurusanResource;
use Filament\Resources\Pages\Page;

class DetailJurusan extends Page
{
    protected static string $resource = JurusanResource::class;

    protected static string $view = 'filament.resources.jurusan-resource.pages.detail-jurusan';

    public const VIEW_PATH = 'filament.resources.jurusan-resource.pages.detail-jurusan';

}
