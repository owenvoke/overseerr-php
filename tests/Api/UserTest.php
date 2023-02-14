<?php

declare(strict_types=1);

use OwenVoke\Overseerr\Api\User;

beforeEach(fn () => $this->apiClass = User::class);

it('should be able to view the currently authenticated user', function () {
    /** @var User $api */
    $api = $this->getApi();

    expect($api->me())->toBeArray();
});

it('should be able to view all users', function () {
    /** @var User $api */
    $api = $this->getApi();

    expect($api->all())->toBeArray();
});

it('should be able to view a specific user', function () {
    /** @var User $api */
    $api = $this->getApi();

    expect($api->show(1))->toBeArray();
});

it("should be able to view a specific user's requests", function () {
    /** @var User $api */
    $api = $this->getApi();

    expect($api->requests(1))->toBeArray();
});

it("should be able to view a specific user's watchlist", function () {
    /** @var User $api */
    $api = $this->getApi();

    expect($api->watchlist(1))->toBeArray();
});
