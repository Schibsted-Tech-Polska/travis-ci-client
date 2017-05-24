<?php

namespace Stp\TravisClient;

use Guzzle\Http\ClientInterface;
use Redis;

class RedisTokenLoader implements TokenLoaderInterface
{
    const TRAVIS_API_TOKEN_KEY = 'TRAVIS_API_TOKEN';

    const TRAVIS_API_TOKEN_TTL = 86400;

    private $redis;

    private $tokenLoader;

    public function __construct(Redis $redis, TokenLoaderInterface $tokenLoader)
    {
        $this->redis = $redis;
        $this->tokenLoader = $tokenLoader;
    }

    public function load(ClientInterface $client): string
    {
        if ($this->redis->exists(static::TRAVIS_API_TOKEN_KEY)) {
            return $this->redis->get(static::TRAVIS_API_TOKEN_KEY);
        }

        $token = $this->tokenLoader->load($client);

        $this->redis->set(static::TRAVIS_API_TOKEN_KEY, $token, static::TRAVIS_API_TOKEN_TTL);

        return $token;
    }

    public function reload(ClientInterface $client): string
    {
        $this->invalidate();

        return $this->load($client);
    }

    private function invalidate(): void
    {
        $this->redis->delete(static::TRAVIS_API_TOKEN_KEY);
    }
}
