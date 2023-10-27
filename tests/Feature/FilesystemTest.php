<?php

use Illuminate\Support\Facades\Storage;

it('should successfully add file txt', function () {
    $filesystem = Storage::disk('local');
    $content = "demo storage put method";
    $result = $filesystem->put("demo.txt",$content);
    $this->assertTrue($result);
    $this->assertEquals($content,$filesystem->get("demo.txt"));
});
