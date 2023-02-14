<?php

declare(strict_types=1);

use OwenVoke\Overseerr\Api\Request;

beforeEach(fn () => $this->apiClass = Request::class);

it('should be able to view all requests', function () {
    /** @var Request $api */
    $api = $this->getApi();

    expect($api->all())->toBeArray();
});

it('should be able to view a specific request', function () {
    /** @var Request $api */
    $api = $this->getApi();

    expect($api->show(1))->toBeArray();
});

it('should be able to view the number of requests', function () {
    /** @var Request $api */
    $api = $this->getApi();

    expect($api->count())->toBeArray();
});
