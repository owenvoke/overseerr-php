<?php

namespace OwenVoke\Overseerr\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;

final class ResponseMediator
{
    /**
     * @param  ResponseInterface  $response
     * @return array
     */
    public static function getContent(ResponseInterface $response): array
    {
        $body = $response->getBody()->__toString();

        return (array) json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function getHeader(ResponseInterface $response, string $name): string|null
    {
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
