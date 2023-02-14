<?php

declare(strict_types=1);

use OwenVoke\Overseerr\Api\Status;
use OwenVoke\Overseerr\Client;

it('gets instances from the client', function () {
    $client = new Client('http://localhost:5055');

    // Retrieves Events instance
    expect($client->status())->toBeInstanceOf(Status::class);
});
