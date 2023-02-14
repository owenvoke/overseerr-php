<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr\Api;

use OwenVoke\Overseerr\Client;
use OwenVoke\Overseerr\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{
    /** The per page parameter. */
    protected int|null $perPage = null;

    public function __construct(private readonly Client $client)
    {
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    public function configure(): self
    {
        return $this;
    }

    protected function get(string $path, array $parameters = [], array $requestHeaders = []): array
    {
        if (null !== $this->perPage && ! isset($parameters['limit'])) {
            $parameters['limit'] = $this->perPage;
        }

        if (array_key_exists('ref', $parameters) && null === $parameters['ref']) {
            unset($parameters['ref']);
        }

        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
        }

        $response = $this->client->getHttpClient()->get($path, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    protected function head(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        if (array_key_exists('ref', $parameters) && null === $parameters['ref']) {
            unset($parameters['ref']);
        }

        return $this->client->getHttpClient()->head($path.'?'.http_build_query($parameters, '', '&', PHP_QUERY_RFC3986), $requestHeaders);
    }

    protected function post(string $path, array $parameters = [], array $requestHeaders = []): array|string
    {
        return $this->postRaw(
            $path,
            $this->createJsonBody($parameters),
            $requestHeaders
        );
    }

    protected function postRaw(string $path, string|null $body, array $requestHeaders = []): array|string
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            $requestHeaders,
            $body
        );

        return ResponseMediator::getContent($response);
    }

    protected function patch(string $path, array $parameters = [], array $requestHeaders = []): array|string
    {
        $response = $this->client->getHttpClient()->patch(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    protected function put(string $path, array $parameters = [], array $requestHeaders = []): array|string
    {
        $response = $this->client->getHttpClient()->put(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    protected function delete(string $path, array $parameters = [], array $requestHeaders = []): array|string
    {
        $response = $this->client->getHttpClient()->delete(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    protected function createJsonBody(array $parameters): string|null
    {
        return count($parameters) === 0 ? null : json_encode($parameters, JSON_THROW_ON_ERROR);
    }
}
