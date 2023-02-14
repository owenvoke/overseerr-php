<?php

namespace OwenVoke\Overseerr\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\VersionBridgePlugin;
use Http\Promise\Promise;
use OwenVoke\Overseerr\Enums\AuthMethod;
use Psr\Http\Message\RequestInterface;

final class Authentication implements Plugin
{
    use VersionBridgePlugin;

    public function __construct(private readonly string $tokenOrLogin, private readonly AuthMethod $method)
    {
    }

    public function doHandleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($this->getAuthorizationHeader($request));
    }

    private function getAuthorizationHeader(RequestInterface $request): RequestInterface
    {
        return match ($this->method) {
            AuthMethod::AccessToken => $request->withHeader('X-Api-Key', $this->tokenOrLogin),
        };
    }
}
