<?php

use \App\Demos\Contracts\Example;
use \App\Demos\Contracts\ExampleSingleton;
use \App\Demos\Implements\IExample;
use App\Demos\Implements\IExampleSingleton;

it('should successfully create class has been binding', function () {
    $example = $this->app->make(Example::class);
    $this->assertInstanceOf(IExample::class, $example);
});

it('should successfully create class singleton',function(){
    $example = $this->app->make(ExampleSingleton::class);
    $exampleTwo = $this->app->make(ExampleSingleton::class);

    $this->assertInstanceOf(IExampleSingleton::class, $example);
    $this->assertInstanceOf(IExampleSingleton::class, $exampleTwo);
    $this->assertEquals($example, $exampleTwo);
});
