<?php
use Illuminate\Support\Str;

it(' GET "/example-get" has successfully', function () {
    $response = $this->get('/example-get');
    $response->assertStatus(200)->assertSeeText('Hello World');
});

it(' GET "/example-redirect" has successfully', function () {
    $response = $this->get('/example-redirect');
    $response->assertStatus(302)->assertRedirect('/example-get');
});

it(' GET "/example-view" has successfully', function () {
    $response = $this->get('/example-view');
    $response->assertStatus(200)->assertSeeText('Hello World');
});

it('GET "/example/uuid/{id}" has successfully', function () {
    $uuid = Str::uuid()->toString();
    $response = $this->get('/example/uuid/'.$uuid);
    $response->assertStatus(200)->assertSeeText($uuid);
});

it(' GET "/example/uuid/{id}" has failed', function () {
    $uuid = Str::ulid()->toString();
    $response = $this->get('/example/uuid/'.$uuid);
    $response->assertSeeText('404 Not Found');
});

it('GET "/example/ulid/{id}" has successfully', function () {
    $uuid = Str::ulid()->toString();
    $response = $this->get('/example/ulid/'.$uuid);
    $response->assertStatus(200)->assertSeeText($uuid);
});

it(' GET "/example/ulid/{id}" has failed', function () {
    $uuid = Str::uuid()->toString();
    $response = $this->get('/example/ulid/'.$uuid);
    $response->assertSeeText('404 Not Found');
});

it('GET "/example/username/{username?}" has successfully', function () {
    $response = $this->get('/example/username/username');
    $response->assertStatus(200)->assertSeeText('username');
});

it(' GET "/example/route-redirect" has successfully', function () {
    $response = $this->get('/example/route-redirect');
    $response->assertStatus(302)->assertRedirect('/example/username/username');
});

it('GET "/example/username/{username?}" has default value', function () {
    $response = $this->get('/example/username/');
    $response->assertStatus(200)->assertSeeText('guest');
});

it('Rendering View has successfully', function () {
    $this->view('index',['title' => 'Test Page'])->assertSeeText('Hello World');
});

it(' GET "/example-notfound" has successfully', function () {
    $response = $this->get('/example-notfound');
    $response->assertSeeText('404 Not Found');
});


it('GET "/example-url" group has successfully', function () {
    $basePath = '/example-url';
    $this->get($basePath . '/current')->assertStatus(200)->assertSeeText('/example-url/current');
    $this->get($basePath . '/full')->assertStatus(200)->assertSeeText('/example-url/full');
    $this->get($basePath . '/name')->assertStatus(200)->assertSeeText('/example-get');
    $this->get($basePath . '/method')->assertStatus(200)->assertSeeText('/example/controller');
});

