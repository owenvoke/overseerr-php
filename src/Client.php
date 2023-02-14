<?php

declare(strict_types=1);

namespace OwenVoke\Overseerr;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use OwenVoke\Overseerr\Api\AbstractApi;
use OwenVoke\Overseerr\Api\Request;
use OwenVoke\Overseerr\Api\Status;
use OwenVoke\Overseerr\Api\User;
use OwenVoke\Overseerr\Enums\AuthMethod;
use OwenVoke\Overseerr\Exception\BadMethodCallException;
use OwenVoke\Overseerr\Exception\InvalidArgumentException;
use OwenVoke\Overseerr\HttpClient\Builder;
use OwenVoke\Overseerr\HttpClient\Plugin\Authentication;
use OwenVoke\Overseerr\HttpClient\Plugin\PathPrepend;
use Psr\Http\Client\ClientInterface;

/**
 * @method Status status()
 * @method User auth()
 * @method User user()
 * @method User users()
 * @method Request request()
 * @method Request requests()
 */
final class Client
{
    private Builder $httpClientBuilder;

    public function __construct(string $url, Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();

        $builder->addPlugin(new RedirectPlugin());
        $builder->addPlugin(new AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($url)));
        $builder->addPlugin(new PathPrepend('/api/v1'));
        $builder->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => 'overseerr-php (https://github.com/owenvoke/overseerr-php)',
        ]));

        $builder->addHeaderValue('Accept', 'application/json');
        $builder->addHeaderValue('Content-Type', 'application/json');
    }

    public static function createWithHttpClient(string $url, ClientInterface $httpClient): self
    {
        $builder = new Builder($httpClient);

        return new self($url, $builder);
    }

    /** @throws InvalidArgumentException */
    public function api(string $name): AbstractApi
    {
        return match ($name) {
            'request', 'requests' => new Request($this),
            'status' => new Status($this),
            'auth', 'user', 'users' => new User($this),
            default => throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name)),
        };
    }

    public function authenticate(string $tokenOrLogin, AuthMethod $authMethod = AuthMethod::AccessToken): void
    {
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($tokenOrLogin, $authMethod));
    }

    public function __call(string $name, array $args): AbstractApi
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name), $e->getCode(), $e);
        }
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    public function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }
}
