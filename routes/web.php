<?php

use App\Demos\Contracts\Example;
use App\Exceptions\CustomException;
use App\Http\Controllers\DemoController;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Middleware\DemoMiddlewareParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

Route::get('/', function () {
    return view('welcome');
});

$this->router->controller(DemoController::class)->prefix('/example/controller')->name('example.controller.')->group(function () {
    $this->router->get('/','index')->name('index');
    $this->router->get('/query','indexQuery')->name('index.query');
    $this->router->post('/','store')->name('store');
    $this->router->post('/store/upload','storeTask')->name('stores.task');


//    Group of route with prefix '/stores'
    $this->router->prefix('/stores')->name('stores')->group(function () {
        $this->router->post('/','stores')->name('stores');
        $this->router->post('/merge','storesMerge')->name('stores.merge');
        $this->router->post('/only','storesOnly')->name('stores.only');
    });
    $this->router->prefix('/response')->name('response')->group(function () {
        $this->router->get('/','indexResponse')->name('index');
        $this->router->get('/json','indexJSONResponse')->name('json');
        $this->router->get('/view','indexViewResponse')->name('view');
        $this->router->get('/file','indexBinaryFileResponse')->name('file');
        $this->router->get('/download','indexBinaryDownloadResponse')->name('download');
    });
    $this->router->prefix('/cookies')->name('cookies')->group(function () {
        $this->router->post('/set','setCookie')->name('set');
        $this->router->get('/get','getCookie')->name('get');
        $this->router->get('/clear','clearCookie')->name('clear');
    });

    $this->router->middleware('demoGroup')->prefix('/redirect')->name('redirect')->group(function () {
        $this->router->get('/name/{name}','redirect')->name('index')->withoutMiddleware([DemoMiddlewareParameter::class . ':admin']);
        $this->router->get('/method/{method}','redirectMethod')->name('indexMethod')->withoutMiddleware([DemoMiddlewareParameter::class . ':admin']);
        $this->router->get('/google','redirectGoogle')->name('indexGoogle')->withoutMiddleware([DemoMiddlewareParameter::class . ':admin']);
    });

});

$this->router->get('/example/uuid/{id}',function (string $id){
    return "UserId: '$id'";
})->whereUuid('id')->name('example.uuid');


$this->router->get('/example/ulid/{id}',function (string $id){
    return "UserId: '$id'";
})->whereUlid('id')->name('example.ulid');

$this->router->get('/example/username/{username?}',function (?string $username = 'guest'){
    return "Username: '$username'";
})->name('example.username');

$this->router->get('/example/route-redirect',function (){
    return redirect()->route('example.username', [
        'username'=>'username'
    ]);
});

$this->router->view('/example-view','index',[
    'title' => 'Home Page',
])->name('example.view');

$this->router->get('/example-get', function () {
    return 'Hello World';
})->name('example.get');

$this->router->get('/example-redirect', function () {
    return redirect()->route('example.controller.index');
})->name('example.redirect');

$this->router->prefix('/example-url')->name('example.url.')->group(function () {

    $this->router->get('/current', function () {
        return URL::current();
    })->name('current');

    $this->router->get('/full', function () {
        return URL::full();
    })->name('full');

    $this->router->get('/name', function () {
        return URL::route('example.get');
    })->name('name');

    $this->router->get('/method', function () {
        return URL::action(action: [DemoController::class, 'index']);
    })->name('method');

});

$this->router->get('/providers', function () {
    $exampleSingleton = App::make(Example::class);
    $exampleSingleton->example();
})->name('example.providers');

$this->router->get('/report', function () {
    try {
        throw new CustomException("Something went wrong", 500);
    } catch (\Exception $e) {
        report($e);
        throw new HttpResponseException($e->getMessage(), $e->getCode());
    }
});

$this->router->get('/error/{code}', function (int $code, Request $request) {
    $code == 400 ? abort(400, 'Bad Request') : abort(500, 'Something went wrong');
});

$this->router->fallback(function () {
    return "404 Not Found";
});
