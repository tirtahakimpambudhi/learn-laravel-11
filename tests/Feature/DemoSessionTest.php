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

it('');
