<?php



it('should successfully binding classes that depend other classes', function () {
    class Example {

    }

    class DependExample {
        public function __construct(public Example $example)
        {
        }
    }

    $this->app->bind(Example::class,function ($app) {
        return new Example();
    });

    $dependExample = $this->app->make(DependExample::class);
    expect($dependExample->example::class)->toBe(Example::class);
});

it('should successfully binding classes that depend other classes with singleton', function () {
    class Example {

    }

    class DependExample {
        public function __construct(public Example $example)
        {
        }
    }

    $this->app->singleton(Example::class,function ($app) {
        return new Example();
    });
    $example = $this->app->make(Example::class);
    $dependExample = $this->app->make(DependExample::class);
    expect($dependExample->example)->toBe($example);
});

it('should successfully binding classes singleton that depend other classes with singleton', function () {
    class Example {

    }

    class DependExample {
        public function __construct(public Example $example)
        {
        }
    }

    $this->app->singleton(Example::class,function ($app) {
        return new Example();
    });
    $this->app->singleton(DependExample::class,function ($app) {
        return new DependExample($this->app->make(Example::class));
    });

    $example = $this->app->make(Example::class);
    $dependExample = $this->app->make(DependExample::class);
    $dependExampleTwo = $this->app->make(DependExample::class);
    expect($dependExample->example)->toBe($example);
    expect($dependExample)->toBe($dependExampleTwo);
});

it('should successfully binding classes that depend other classes with instance singleton', function () {
    class Example {

    }
    $example = new Example();
    class DependExample {
        public function __construct(public Example $example)
        {
        }
    }

    $this->app->instance(Example::class,$example);
    $dependExample = $this->app->make(DependExample::class);
    expect($dependExample->example)->toBe($example);
});

it('should successfully binding classes that depend other interface', function () {
    interface Example
    {
        public function example() :void;
    }
    class IExample implements Example{
        public function example() :void
        {
            echo "Example Implementation";
        }
    }
    class DependExample {
        public function __construct(public Example $example)
        {
        }
    }
    $this->app->bind(Example::class,function () {
        return new IExample();
    });
    $example = $this->app->make(Example::class);
    $dependExample = $this->app->make(DependExample::class);
    expect($dependExample->example->example())->toBe($example->example());
});
