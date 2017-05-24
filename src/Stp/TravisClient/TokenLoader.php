<?php

namespace Stp\TravisClient;

use Guzzle\Http\ClientInterface;
use Stp\TravisClient\Exception\TravisClientException;

class TokenLoader implements TokenLoaderInterface
{
    private $githubToken;

    public function __construct(string $githubToken)
    {
        $this->githubToken = $githubToken;
    }

    public function load(ClientInterface $client): string
    {
        $request = $client->post('auth/github');
        $request->setBody(sprintf('{"github_token": "%s"}', $this->githubToken));
        $response = $request->send();

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();
            $token = $responseJson['access_token'];
            return $token;
        }

        throw new TravisClientException('Can not get Travis API token, response code: ' . $response->getStatusCode());
    }

    public function reload(ClientInterface $client): string
    {
        return $this->load($client);
    }
}
