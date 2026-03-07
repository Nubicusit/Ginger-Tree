<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DepartmentMiddleware;
use App\Http\Middleware\HrMiddleware;
use App\Http\Middleware\AccountsMiddleware;
use App\Http\Middleware\DesignerMiddleware;
use App\Http\Middleware\EstimateMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => AdminMiddleware::class,
        'department' => DepartmentMiddleware::class,
        'hr'    => HrMiddleware::class,
        'accounts'   => AccountsMiddleware::class,
        'designer' => DesignerMiddleware::class,
        'estimator' => EstimateMiddleware::class,

    ]);
  })
    ->withExceptions(function (Exceptions $exceptions) {
          //
    })->create();
