<?php

$testCases = [
    [
        'name' => 'Successfully Get APP_ENV',
        'key' => 'APP_ENV',
        'expectedValue' => 'testing',
    ],
    [
        'name' => 'Get ENV_APP Not Found Environment',
        'key' => 'ENV_APP',
        'expectedValue' => '',
    ]
];

test('getting environment', function (string $name, string $key, string $expectedValue) {
    $this->assertEquals($expectedValue,env($key));
})->with($testCases);


it('should successfully get config application', function () {
    $this->assertEquals('Laravel',config('app.name'));
    $this->assertEquals('testing',config('app.env'));
    // Mocking Facades::Config
    Config::shouldReceive('get')->once()->with('app.name')->andReturn('Laravels');
    $result = Config::get('app.name');
    $this->assertEquals('Laravels',$result);
});
