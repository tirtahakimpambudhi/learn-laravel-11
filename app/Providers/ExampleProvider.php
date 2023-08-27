<?php

namespace App\Providers;

use App\Demos\Contracts\Example;
use App\Demos\Contracts\ExampleSingleton;
use App\Demos\Implements\IExample;
use App\Demos\Implements\IExampleSingleton;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class ExampleProvider extends ServiceProvider implements DeferrableProvider
{

    /** binding interface to implement class ( concrete )
     * @var array|\class-string[]
     */
    public array $bindings = [
        Example::class => IExample::class
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ExampleSingleton::class, function ($app) {
            return new IExampleSingleton(name: "Example Singleton");
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides(): array {
        return [
            Example::class,ExampleSingleton::class
        ];
    }
}
