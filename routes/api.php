<?php

use App\Http\Controllers\DemoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

$this->router->controller(DemoSession::class)->prefix('/v1/sessions')->name('example.session')->group(function () {
    $this->router->get('/', 'index')->name('index');
    $this->router->get('/{id}', 'show')->whereUuid('id')->name('show');
    $this->router->post('/', 'store')->name('store');
    $this->router->put('/{id}', 'update')->whereUuid('id')->name('update');
    $this->router->delete('/{id}', 'destroy')->whereUuid('id')->name('destroy');
});
