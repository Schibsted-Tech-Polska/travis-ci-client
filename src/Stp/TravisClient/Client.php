<?php

namespace Stp\TravisClient;

use Guzzle\Http\Client as GuzzleClient;
use Stp\TravisClient\Entities\Account;
use Stp\TravisClient\Entities\Branch;
use Stp\TravisClient\Entities\Build;
use Stp\TravisClient\Entities\Commit;

class Client
{
    protected $apiUrl = 'https://api.travis-ci.org';
    protected $githubToken;
    protected $token;
    protected $client;

    /**
     * Client constructor.
     * @param string $githubToken
     * @param string $apiUrl
     */
    public function __construct($githubToken, $apiUrl = null)
    {
        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }
        $this->githubToken = $githubToken;
        $this->client = new GuzzleClient();
        $this->client->setBaseUrl($this->apiUrl);
        $this->client->setDefaultOption('headers/Content-Type', 'application/json');
        $this->client->setDefaultOption('headers/Accept', 'application/vnd.travis-ci.2+json');

        $this->githubAuth($githubToken);

        $this->client->setDefaultOption('headers/Authorization', sprintf('token %s', $this->token));
    }

    private function githubAuth($githubToken)
    {
        $request = $this->client->post('auth/github');
        $request->setBody(sprintf('{"github_token": "%s"}', $githubToken));
        $response = $request->send();

        if ($response->getStatusCode() == 200) {
            $responseJson = $response->json();
            $this->token = $responseJson['access_token'];
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
        $request = $this->client->get(sprintf('accounts%s', $paramsUrl));
        $response = $request->send();

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

    /**
     * Returns list of branches.
     * @see http://docs.travis-ci.com/api/#branches
     *
     * @param string $repository
     * @return array
     */
    public function getBranches($repository)
    {
        $request = $this->client->get(sprintf('repos/%s/branches', $repository));
        $response = $request->send();

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
        $request = $this->client->get(sprintf('repos/%s/branches/%s', $repository, $branch));
        $response = $request->send();

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

        $request = $this->client->get(sprintf('repos/%s/builds%s', $repository, $paramsUrl));
        $response = $request->send();

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
