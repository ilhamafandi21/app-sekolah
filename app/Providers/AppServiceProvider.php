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
        Model::preventLazyLoading(! app()->isProduction());
    }
}
