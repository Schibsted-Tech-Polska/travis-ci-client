<?php

namespace Stp\TravisClient\Entities;

use Stp\TravisClient\Hydrate;

/**
 * Class Commit
 * @package Stp\TravisClient\Entities
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Commit
{
    use Hydrate;

    /**
     * @var int Commit id
     */
    protected $id;
    /**
     * @var string Commit sha
     */
    protected $sha;
    /**
     * @var string Branch the commit is on
     */
    protected $branch;
    /**
     * @var string Commit message
     */
    protected $message;
    /**
     * @var string Commit date
     */
    protected $commitedAt;
    /**
     * @var string Author name
     */
    protected $authorName;
    /**
     * @var string Author email
     */
    protected $authorEmail;
    /**
     * @var string Committer name
     */
    protected $committerName;
    /**
     * @var string Committer email
     */
    protected $committerEmail;
    /**
     * @var string Link to diff on GitHub
     */
    protected $compareUrl;
    /**
     * @var int Pull request number
     */
    protected $pullRequestNumber;

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
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * @param string $sha
     */
    public function setSha($sha)
    {
        $this->sha = $sha;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCommitedAt()
    {
        return $this->commitedAt;
    }

    /**
     * @param string $commitedAt
     */
    public function setCommitedAt($commitedAt)
    {
        $this->commitedAt = $commitedAt;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @param string $authorName
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @param string $authorEmail
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * @return string
     */
    public function getCommitterName()
    {
        return $this->committerName;
    }

    /**
     * @param string $committerName
     */
    public function setCommitterName($committerName)
    {
        $this->committerName = $committerName;
    }

    /**
     * @return string
     */
    public function getCommitterEmail()
    {
        return $this->committerEmail;
    }

    /**
     * @param string $committerEmail
     */
    public function setCommitterEmail($committerEmail)
    {
        $this->committerEmail = $committerEmail;
    }

    /**
     * @return string
     */
    public function getCompareUrl()
    {
        return $this->compareUrl;
    }

    /**
     * @param string $compareUrl
     */
    public function setCompareUrl($compareUrl)
    {
        $this->compareUrl = $compareUrl;
    }

    /**
     * @return int
     */
    public function getPullRequestNumber()
    {
        return $this->pullRequestNumber;
    }

    /**
     * @param int $pullRequestNumber
     */
    public function setPullRequestNumber($pullRequestNumber)
    {
        $this->pullRequestNumber = $pullRequestNumber;
    }
}
