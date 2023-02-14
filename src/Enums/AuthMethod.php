<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr\Enums;

enum AuthMethod: string
{
    case AccessToken = 'access_token_header';
}
