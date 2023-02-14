<?php

namespace OwenVoke\Overseerr\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\VersionBridgePlugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final class PathPrepend implements Plugin
{
    use VersionBridgePlugin;

    public function __construct(private readonly string $path)
    {
    }

    public function doHandleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $currentPath = $request->getUri()->getPath();
        if (! str_starts_with($currentPath, $this->path)) {
            $uri = $request->getUri()->withPath($this->path.$currentPath);
            $request = $request->withUri($uri);
        }

        return $next($request);
    }
}
