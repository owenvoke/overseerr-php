<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr\Api;

class Status extends AbstractApi
{
    public function status(): array
    {
        return $this->get('/status');
    }

    public function appdata(): array
    {
        return $this->get('/status/appdata');
    }
}
