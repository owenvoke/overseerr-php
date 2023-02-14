<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr\Api;

class Request extends AbstractApi
{
    public function all(): array
    {
        return $this->get('/request');
    }

    public function show(int $id): array
    {
        return $this->get(sprintf('/request/%s', $id));
    }

    public function count(): array
    {
        return $this->get('/request/count');
    }
}
