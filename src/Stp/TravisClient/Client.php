<?php

namespace Stp\TravisClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\Response;
use Stp\TravisClient\Entities\Account;
use Stp\TravisClient\Entities\Branch;
use Stp\TravisClient\Entities\Build;
use Stp\TravisClient\Entities\Commit;

class Client
{
    const DEFAULT_TIMEOUT = 10; // in seconds

    const USER_AGENT = 'STP Travis API Client';

    protected $apiUrl = 'https://api.travis-ci.org';

    protected $client;

    protected $tokenLoader;

    public function __construct(
        TokenLoaderInterface $tokenLoader,
        ?string $apiUrl = null,
        float $timeout = self::DEFAULT_TIMEOUT
    ) {
        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }

        $this->tokenLoader = $tokenLoader;

        $this->client = new GuzzleClient($this->apiUrl, ['timeout' => $timeout]);
        $this->client->setDefaultOption('headers/Content-Type', 'application/json');
        $this->client->setDefaultOption('headers/Accept', 'application/vnd.travis-ci.2+json');
        $this->client->setUserAgent(self::USER_AGENT);

        $this->loadToken();
    }

    private function loadToken()
    {
        try {
            $token = $this->tokenLoader->load($this->client);
            $this->client->setDefaultOption('headers/Authorization', sprintf('token %s', $token));
        } catch (ClientErrorResponseException $e) {
            if ($e->getCode() == 403) {
                $this->tokenLoader->reload($this->client);
            }

            throw $e;
        }
    }

    /**
     * Returns accounts.
     * @see http://docs.travis-ci.com/api/#accounts
     *
     * @param array $options
     * @return array
     */
    public function getAccounts($options = [])
    {
        $paramsUrl = $this->prepareParametersUrl($options, ['all']);
        $response = $this->sendRequest(sprintf('accounts%s', $paramsUrl));

        $result = [];

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();

            foreach ($responseJson['accounts'] as $item) {
                $account = new Account();
                Account::hydrate($item, $account);

                $result[] = $account;
            }
        }

        return $result;
    }

    private function sendRequest(string $uri): Response
    {
        $request = $this->client->get($uri);
        return $request->send();
    }

    /**
     * Returns list of branches.
     * @see http://docs.travis-ci.com/api/#branches
     *
     * @param string $repository
     * @return array
     */
    public function getBranches($repository)
    {
        $response = $this->sendRequest(sprintf('repos/%s/branches', $repository));

        $result = [];

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();
            $commits = [];

            foreach ($responseJson['commits'] as $item) {
                $commit = new Commit();
                Commit::hydrate($item, $commit);

                $commits[$commit->getId()] = $commit;
            }

            foreach ($responseJson['branches'] as $item) {
                $branch = new Branch();
                $item['commit'] = $commits[$item['commit_id']];
                Branch::hydrate($item, $branch);

                $result[] = $branch;
            }
        }

        return $result;
    }

    /**
     * Returns branch.
     * @see http://docs.travis-ci.com/api/#branches
     *
     * @param string $repository
     * @param string $branch
     * @return null|Branch
     */
    public function getBranch($repository, $branch)
    {
        $response = $this->sendRequest(sprintf('repos/%s/branches/%s', $repository, $branch));

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();

            $item = $responseJson['commit'];
            $commit = new Commit();
            Commit::hydrate($item, $commit);

            $item = $responseJson['branch'];
            $branch = new Branch();
            $item['commit'] = $commit;
            Branch::hydrate($item, $branch);

            return $branch;
        }

        return null;
    }

    /**
     * Returns list of builds
     * @see http://docs.travis-ci.com/api/#builds
     *
     * @param string $repository
     * @param array $options
     * @return array
     */
    public function getBuilds($repository, $options = [])
    {
        $paramsUrl = $this->prepareParametersUrl($options, ['number', 'after_number', 'event_type']);
        $response = $this->sendRequest(sprintf('repos/%s/builds%s', $repository, $paramsUrl));

        $result = [];

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();
            $commits = [];

            foreach ($responseJson['commits'] as $item) {
                $commit = new Commit();
                Commit::hydrate($item, $commit);

                $commits[$commit->getId()] = $commit;
            }

            foreach ($responseJson['builds'] as $item) {
                $build = new Build();
                $item['commit'] = $commits[$item['commit_id']];
                Build::hydrate($item, $build);

                $result[] = $build;
            }
        }

        return $result;
    }

    /**
     * Prepares params url that can be appended to request.
     *
     * @param array $params
     * @param array $allowedParams
     * @return string
     */
    private function prepareParametersUrl(array $params, array $allowedParams)
    {
        $allowedParams = array_flip($allowedParams);
        $params = array_intersect_key($params, $allowedParams);

        $paramsUrlItems = [];
        foreach ($params as $key => $param) {
            $paramsUrlItems[] = $key . '=' . urlencode($param);
        }

        $paramsUrl = join('&', $paramsUrlItems);
        if (strlen($paramsUrl) > 0) {
            $paramsUrl = '?' . $paramsUrl;
        }

        return $paramsUrl;
    }
}
