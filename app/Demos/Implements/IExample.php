<?php

namespace App\Demos\Implements;

use App\Demos\Contracts\Example;

class IExample implements Example
{
    public static int $increment = 0;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
        self::$increment++;
    }

    public function example(): void
    {

        echo "Class ". __CLASS__ . " has implement " . Example::class . PHP_EOL . "is the ". self::$increment . (match (self::$increment) {
                1 => "st",
                2 => "nd",
                3 => "rd",
                default => "th"
        }) ." initialization";
    }
}
