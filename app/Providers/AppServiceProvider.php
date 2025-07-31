<?php

namespace App\Providers;

use App\Models\Subject;
use App\Observers\SiswaObserver;
use App\Observers\SubjectObserver;
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

       Model::preventLazyLoading(! app()->isProduction());
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            logger()->warning("⚠️ Lazy loading detected: {$relation} on " . get_class($model));
        });

    }
}
