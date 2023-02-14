<?php

namespace OwenVoke\Overseerr\Tests;

use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use OwenVoke\Overseerr\Api\AbstractApi;
use OwenVoke\Overseerr\Client;
use OwenVoke\Overseerr\HttpClient\Builder;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var class-string<AbstractApi> */
    protected string $apiClass;

    protected function getApi(): AbstractApi
    {
        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__.'/__SNAPSHOTS__');

        $httpBuilder = new Builder();
        $httpBuilder->addPlugin(
            in_array('--update-snapshots', $_SERVER['argv']) || getenv('UPDATE_SNAPSHOTS') === 'true' ?
                new RecordPlugin($namingStrategy, $recorder) :
                new ReplayPlugin($namingStrategy, $recorder)
        );

        $client = new Client(
            (string) (getenv('OVERSEERR_URL') ?: 'http://localhost:5055'),
            $httpBuilder
        );

        $client->authenticate((string) getenv('OVERSEERR_TOKEN'));

        return new ($this->apiClass)($client);
    }
}
