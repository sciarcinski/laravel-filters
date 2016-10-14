<?php

namespace Sciarcinski\LaravelFilters;

use Illuminate\Support\ServiceProvider;
use Sciarcinski\LaravelFilters\Generators\FilterMakeCommand;

class FiltersServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    public function boot()
    {
        $this->commands(FilterMakeCommand::class);
    }
}
