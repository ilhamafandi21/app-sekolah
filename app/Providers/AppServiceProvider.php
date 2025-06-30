<?php

namespace App\Providers;

use App\Models\Siswa;
use App\Models\Subject;
use App\Observers\SiswaObserver;
use App\Observers\SubjectObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Siswa::observe(SiswaObserver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
    }
}
