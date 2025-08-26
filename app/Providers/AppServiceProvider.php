<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Model::preventLazyLoading(! app()->isProduction());
        // Model::preventLazyLoading(true);

        // if (!app()->isProduction()) {
        //     Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
        //         logger()->warning("Lazy loading detected: {$relation} on " . get_class($model));
        //     });
        // }

        FileUpload::configureUsing(fn (FileUpload $c) => $c->disk('public')->visibility('public'));
        ImageColumn::configureUsing(fn (ImageColumn $c) => $c->disk('public')->visibility('public'));
        ImageEntry::configureUsing(fn (ImageEntry $c) => $c->disk('public')->visibility('public'));


       Model::preventLazyLoading(! app()->isProduction());
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            logger()->warning("⚠️ Lazy loading detected: {$relation} on " . get_class($model));
        });

    }
}
