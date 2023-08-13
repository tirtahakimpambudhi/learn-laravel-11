<?php

namespace App\Demos\Implements;

use App\Demos\Contracts\ExampleSingleton;

class IExampleSingleton implements ExampleSingleton
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $name)
    {
        //
    }

    public function printSomething() :void
    {
        echo "Hello My name is {$this->name}\n";
    }
}
