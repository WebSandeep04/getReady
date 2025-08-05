<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\FabricType;
use App\Models\Color;
use App\Models\Size;
use App\Models\BottomType;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share filter data with all views
        View::composer('layouts.header', function ($view) {
            $filters = [
                'categories' => Category::orderBy('name')->get(),
                'fabric_types' => FabricType::orderBy('name')->get(),
                'colors' => Color::orderBy('name')->get(),
                'sizes' => Size::orderBy('name')->get(),
                'bottom_types' => BottomType::orderBy('name')->get(),
            ];
            
            $view->with('filters', $filters);
        });
    }
}
