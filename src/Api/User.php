<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr\Api;

class User extends AbstractApi
{
    public function me(): array
    {
        return $this->get('/auth/me');
    }

    public function all(): array
    {
        return $this->get('/user');
    }

    public function show(int $id): array
    {
        return $this->get(sprintf('/user/%s', $id));
    }

    public function requests(int $id): array
    {
        return $this->get(sprintf('/user/%s/requests', $id));
    }

    public function watchlist(int $id): array
    {
        return $this->get(sprintf('/user/%s/watchlist', $id));
    }
}
