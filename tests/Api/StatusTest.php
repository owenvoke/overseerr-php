<?php

declare(strict_types=1);

use OwenVoke\Overseerr\Api\Status;

beforeEach(fn () => $this->apiClass = Status::class);

it('should be able to view the status', function () {
    /** @var Status $api */
    $api = $this->getApi();

    expect($api->status())->toBeArray();
});

it('should be able to view the appdata status', function () {
    /** @var Status $api */
    $api = $this->getApi();

    expect($api->appdata())->toBeArray();
});
