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

    public function getAccounts()
    {
        $request = $this->client->get('accounts');
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

    public function getBuilds($repository)
    {
        $request = $this->client->get(sprintf('repos/%s/builds', $repository));
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
}
