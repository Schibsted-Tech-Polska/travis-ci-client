<?php

namespace Stp\TravisClient;

use Guzzle\Http\ClientInterface;
use Stp\TravisClient\Exception\TravisClientException;

interface TokenLoaderInterface
{
    /**
     * @throws TravisClientException
     */
    public function load(ClientInterface $client): string;

    /**
     * @throws TravisClientException
     */
    public function reload(ClientInterface $client): string;
}
