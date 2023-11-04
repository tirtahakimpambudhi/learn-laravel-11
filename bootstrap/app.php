<?php

use App\Exceptions\CustomException;
use App\Http\Middleware\DemoMiddleware;
use App\Http\Middleware\DemoMiddlewareParameter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->alias(aliases: ['demo' => DemoMiddleware::class]);
//        $middleware->alias(aliases: ['demoParam:admin' => DemoMiddlewareParameter::class]);
        $middleware->appendToGroup('api', StartSession::class);
        $middleware->appendToGroup('demoGroup',[DemoMiddleware::class,DemoMiddlewareParameter::class . ':admin']);
        $middleware->encryptCookies(except: [
            'unencrypted',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->report(function (CustomException $e) {
            var_dump($e->getMessage());
        })->stop();
    })->create();
