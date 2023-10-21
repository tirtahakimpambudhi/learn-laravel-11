<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

beforeEach(function (){
    $this->basePath = '/example/controller';
});

it('should successfully index method',function (){
   $response = $this->get($this->basePath);
    $response->assertStatus(200)->assertSeeText('Hello World');
});

it('should successfully indexQuery method',function (){
    $name = 'johndoe';
    $response = $this->get($this->basePath . "/query?name=$name");
    $response->assertStatus(200)->assertSeeText("Hello $name");

});

it('should successfully redirect method',function (){
        $this->get($this->basePath . "/redirect/name/example.controller.index")->assertStatus(ResponseAlias::HTTP_FOUND)->assertRedirect($this->basePath);
});

it('should failed redirect method',function (){
    $this->get($this->basePath . "/redirect/name/example.controller.notfound")->assertStatus(ResponseAlias::HTTP_NOT_FOUND)->assertSeeText("not defined");
});

it('should successfully redirectMethod method',function (){
    $this->get($this->basePath . "/redirect/method/index")->assertStatus(ResponseAlias::HTTP_FOUND)->assertRedirect($this->basePath);
});

it('should failed redirectMethod method',function (){
    $this->get($this->basePath . "/redirect/method/notfound")->assertStatus(ResponseAlias::HTTP_NOT_FOUND)->assertSeeText("not defined");
});

it('should successfully redirectGoogle method',function (){
    $this->get($this->basePath . "/redirect/google")->assertStatus(ResponseAlias::HTTP_FOUND)->assertRedirect("https://google.com");
});

it('should successfully store method', function () {
    $data = [
        'name' => 'Successfully Demo',
        'address' => [
            'street' => 'streetJS',
            'city' => 'cityPHP'
        ]
    ];

    $response = $this->post($this->basePath, $data);
    $responseBody = json_decode( $response->getContent(),true);
    $response->assertStatus(200)->assertJson($responseBody);
    expect($data)->toBe($responseBody);
});

it('should successfully getCookie method', function () {
    $data = [
        'name' => 'Successfully Demo',
        'address' => [
            'street' => 'streetJS',
            'city' => 'cityPHP'
        ]
    ];
    $this->withCookie('user_data',serialize($data))->get($this->basePath . "/cookies/get")->assertStatus(200)->assertJson($data);
});

it('should failed getCookie method', function () {
    $this->get($this->basePath . "/cookies/get")->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
});

it('should successfully clearCookie method', function () {
    $data = [
        'name' => 'Successfully Demo',
        'address' => [
            'street' => 'streetJS',
            'city' => 'cityPHP'
        ]
    ];
    $this->withCookie('user_data',serialize($data))->get($this->basePath . "/cookies/clear")->assertStatus(200)->assertCookieExpired('user_data');
});

it('should failed clearCookie method', function () {
    $this->get($this->basePath . "/cookies/clear")->assertStatus(200)->assertCookieExpired('user_data');
});

it('should successfully setCookie method', function () {
    $data = [
        'name' => 'Successfully Demo',
        'address' => [
            'street' => 'streetJS',
            'city' => 'cityPHP'
        ]
    ];
    $this->post($this->basePath . "/cookies/set",$data)->assertStatus(200)->assertCookie('user_data',serialize($data));
});

it('should failed setCookie method', function () {
    $data = [
        'name' => ''
    ];

    $response = $this->post($this->basePath . "/cookies/set", $data);
    $response->assertStatus(422);
});

it('should failed store method', function () {
    $data = [
        'name' => ''
    ];

    $response = $this->post($this->basePath, $data);
    $response->assertStatus(422);
});

it('should successfully storeTask method', function () {
    $data = [
        'title' => 'New Title',
        'image' => UploadedFile::fake()->create('image.jpg', 1000)
    ];

    $response = $this->post($this->basePath . "/store/upload", $data);
    $response->assertStatus(200);
    $responseBody = json_decode($response->getContent(), true);
    $this->assertStringContainsString('image.jpg',$responseBody['message']);
    $filesystem = Storage::disk('local');
});

it('should failed storeTask method', function () {
    $data = [
    ];

    $response = $this->post($this->basePath . "/store/upload", $data);
    $response->assertStatus(422);
});

it('should successfully stores method', function () {
    $data = [
        'friends' => [
            ['name' => 'name one', 'address' => 'address one'],
            ['name' => 'name two', 'address' => 'address two']
        ]
    ];

    $response = $this->post($this->basePath . '/stores', $data);
    $responseBody = json_decode($response->getContent(), true);

    $response->assertStatus(200)->assertJson($responseBody);

    // Extract all names from the data
    $names = array_column($data['friends'], 'name');

    // Check if the names in the response match the expected names
    expect($names)->toBe($responseBody['data']);
});

it('should successfully storesOnly method', function () {
    $data = [
        'friends' => [
            ['name' => 'name one', 'address' => 'address one'],
            ['name' => 'name two', 'address' => 'address two']
        ],
        'enemy' => [
            ['name' => 'enemy one', 'address' => 'address of enemy one']
        ]
    ];

    $response = $this->post($this->basePath . '/stores/only', $data);
    $responseBody = json_decode($response->getContent(), true);

    $response->assertStatus(200)->assertJson($responseBody);

    // Check if the names in the response match the expected names
    expect(array_diff_key($data,array_flip(['enemy'])))->toBe($responseBody['data']);
    $this->assertNotEquals($data['enemy'],$responseBody['data']);
});

it('should successfully storesMerge method', function () {
    $data = [
        'friends' => [
            ['name' => 'name one', 'address' => 'address one'],
            ['name' => 'name two', 'address' => 'address two']
        ],
        'enemy' => [
            ['name' => 'enemy one', 'address' => 'address of enemy one']
        ]
    ];

    $response = $this->post($this->basePath . '/stores/merge', $data);
    $responseBody = json_decode($response->getContent(), true);

    $response->assertStatus(200)->assertJson($responseBody);

    // Check if the names in the response match the expected names
    expect($data['friends'])->toBe($responseBody['data']['friends']);
    $this->assertNull($responseBody['data']['enemy']);
});

it('should successfully indexResponse method', function () {
    $response = $this->get($this->basePath . "/response", [
        'Accept' => 'application/json'
    ]);

    $responseBody = json_decode($response->getContent(), true);
    $response->assertStatus(200)->assertJson($responseBody);
});

it('should successfully indexJSONResponse method', function () {
    $response = $this->get($this->basePath . "/response/json", [
        'Accept' => 'application/json'
    ]);

    $responseBody = json_decode($response->getContent(), true);
    $response->assertStatus(200)->assertJson($responseBody);
});

it('should successfully indexViewResponse method', function () {
    $response = $this->get($this->basePath . "/response/view")->assertSeeText('Demo View Page');
});

it('should successfully indexBinaryFileResponse method', function () {
    $this->get($this->basePath . "/response/file?image=image.jpg")->assertStatus(200)->assertHeader('Content-Type', 'image/jpeg');
});

it('should failed indexBinaryFileResponse method', function () {
    $this->get($this->basePath . "/response/file?image=notfound.jpg")->assertStatus(404);
});

it('should successfully indexBinaryDownloadResponse method', function () {
    $this->get($this->basePath . "/response/download?image=image.jpg")->assertStatus(200)->assertDownload('image.jpg');
});

it('should failed indexBinaryDownloadResponse method', function () {
    $this->get($this->basePath . "/response/download?image=notfound.jpg")->assertStatus(404);
});

it('should failed indexJSONResponse method', function () {
    $this->get($this->basePath . "/response/json", [
        'Accept' => 'application/xml'
    ])->assertStatus(400);
});


it('should failed stores method', function () {
    $data = [

    ];
    $response = $this->post($this->basePath . "/stores", $data);
    $response->assertStatus(422);
});

it('should failed storesOnly method', function () {
    $data = [

    ];
    $response = $this->post($this->basePath . "/stores/only", $data);
    $response->assertStatus(422);
});

it('should failed storesMerge method', function () {
    $data = [

    ];
    $response = $this->post($this->basePath . "/stores/merge", $data);
    $response->assertStatus(422);
});

