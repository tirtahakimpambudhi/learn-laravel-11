<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
beforeEach(function () {
    $this->basePath = '/api/v1/sessions';
});


it('can get all session data', function () {
    $response = $this->get($this->basePath);
    $response->assertStatus(Response::HTTP_OK);
    $data = collect(json_decode($response->getContent(), true));
    $this->assertEmpty($data->get('data'));
});

it('can get session data', function () {
    $id = Str::uuid()->toString();
    $data = [$id => [
        'name' => fake()->name,
        'age' => fake()->randomDigitNotZero()
    ]];
    $response = $this->withSession($data)->get($this->basePath . "/$id");
    $response->assertStatus(Response::HTTP_OK);
    $actualData = collect(json_decode($response->getContent(), true));
    $this->assertNotEmpty($actualData->get('data'));
});

it('cannot get session data', function () {
    $id = Str::uuid()->toString();
    $response = $this->get($this->basePath . "/$id");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can store session data', function () {
    $id = Str::uuid()->toString();
    $data = [
        $id => [
            'name' => fake()->name,
            'age' => fake()->randomDigitNotZero()
        ]
    ];
    $response = $this->withSession($data)->post($this->basePath . "/", [
        'name' => fake()->name,
        'age' => fake()->randomDigitNotZero()
    ]);
    $response->assertStatus(Response::HTTP_OK);
    $actualData = collect(json_decode($response->getContent(), true));
    $this->assertNotEmpty($actualData->get('data'));
});

it('cannot store session data', function () {
    $id = Str::uuid()->toString();
    $data = [
        $id => [
            'name' => fake()->name,
            'age' => fake()->randomDigitNotZero()
        ]
    ];
    $response = $this->withSession($data)->post($this->basePath . "/", [
    ]);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('can update session data', function () {
    $id = Str::uuid()->toString();
    $data = [
        $id => [
            'name' => fake()->name,
            'age' => fake()->randomDigitNotZero()
        ]
    ];
    $response = $this->withSession($data)->put($this->basePath . "/$id", [
        'name' => fake()->name,
        'age' => fake()->randomDigitNotZero()
    ]);
    $response->assertStatus(Response::HTTP_OK);
    $actualData = collect(json_decode($response->getContent(), true));
    $this->assertNotEmpty($actualData->get('data'));
});

it('cannot update session data', function () {
    $id = Str::uuid()->toString();
    $response = $this->withSession([])->put($this->basePath . "/$id", [
        'name' => fake()->name,
        'age' => fake()->randomDigitNotZero()
    ]);
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});


it('can delete session data', function () {
    $id = Str::uuid()->toString();
    $data = [
        $id => [
            'name' => fake()->name,
            'age' => fake()->randomDigitNotZero()
        ]
    ];
    $response = $this->withSession($data)->delete($this->basePath . "/$id");
    $response->assertStatus(Response::HTTP_OK);
    $actualData = collect(json_decode($response->getContent(), true));
    $this->assertEmpty($actualData->get('data'));
});

it('cannot delete session data', function () {
    $id = Str::uuid()->toString();
    $response = $this->withSession([])->delete($this->basePath . "/$id");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});


