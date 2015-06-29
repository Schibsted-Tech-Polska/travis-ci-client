<?php

namespace Stp\TravisClient\Entities;

use Stp\TravisClient\Hydrate;

/**
 * Class Build
 * @package Stp\TravisClient\Entities
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Build
{
    use Hydrate;

    /**
     * @var int Build id
     */
    protected $id;

    /**
     * @var int Repository id
     */
    protected $repositoryId;

    /**
     * @var int Commit id
     */
    protected $commitId;

    /**
     * @var Commit
     */
    protected $commit;

    /**
     * @var string Build number
     */
    protected $number;

    /**
     * @var boolean True or false
     */
    protected $pullRequest;

    /**
     * @var string PR title if pull_request is true
     */
    protected $pullRequestTitle;

    /**
     * @var string PR number if pull_request is true
     */
    protected $pullRequestNumber;

    /**
     * @var array Build config (secure values and ssh key removed)
     */
    protected $config;

    /**
     * @var string Build state
     */
    protected $state;

    /**
     * @var string Time the build was started
     */
    protected $startedAt;

    /**
     * @var string Time the build finished
     */
    protected $finishedAt;

    /**
     * @var integer Build duration
     */
    protected $duration;

    /**
     * @var array List of job ids in the build matrix
     */
    protected $jobIds;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRepositoryId()
    {
        return $this->repositoryId;
    }

    /**
     * @param int $repositoryId
     */
    public function setRepositoryId($repositoryId)
    {
        $this->repositoryId = $repositoryId;
    }

    /**
     * @return int
     */
    public function getCommitId()
    {
        return $this->commitId;
    }

    /**
     * @param int $commitId
     */
    public function setCommitId($commitId)
    {
        $this->commitId = $commitId;
    }

    /**
     * @return Commit
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * @param Commit $commit
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return boolean
     */
    public function isPullRequest()
    {
        return $this->pullRequest;
    }

    /**
     * @param boolean $pullRequest
     */
    public function setPullRequest($pullRequest)
    {
        $this->pullRequest = $pullRequest;
    }

    /**
     * @return string
     */
    public function getPullRequestTitle()
    {
        return $this->pullRequestTitle;
    }

    /**
     * @param string $pullRequestTitle
     */
    public function setPullRequestTitle($pullRequestTitle)
    {
        $this->pullRequestTitle = $pullRequestTitle;
    }

    /**
     * @return string
     */
    public function getPullRequestNumber()
    {
        return $this->pullRequestNumber;
    }

    /**
     * @param string $pullRequestNumber
     */
    public function setPullRequestNumber($pullRequestNumber)
    {
        $this->pullRequestNumber = $pullRequestNumber;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param string $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return string
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param string $finishedAt
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return array
     */
    public function getJobIds()
    {
        return $this->jobIds;
    }

    /**
     * @param array $jobIds
     */
    public function setJobIds($jobIds)
    {
        $this->jobIds = $jobIds;
    }
}
