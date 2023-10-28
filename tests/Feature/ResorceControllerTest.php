<?php

it('has resorcecontroller page', function () {
    $response = $this->get('/resorcecontroller');

    $response->assertStatus(200);
});
