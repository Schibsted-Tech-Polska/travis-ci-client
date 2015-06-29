<?php

namespace Stp\TravisClient\Entities;

use Stp\TravisClient\Hydrate;

/**
 * Class Account
 * @package Stp\TravisClient\Entities
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Account
{
    use Hydrate;

    /**
     * @var int User or organization id
     */
    protected $id;

    /**
     * @var string Account name on GitHub
     */
    protected $name;

    /**
     * @var string Account login on GitHub
     */
    protected $login;

    /**
     * @var string User or organization
     */
    protected $type;

    /**
     * @var int Number of repositories
     */
    protected $reposCount;

    /**
     * @var bool Whether or not the account has a valid subscription
     */
    protected $subscribed;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getReposCount()
    {
        return $this->reposCount;
    }

    /**
     * @param int $reposCount
     */
    public function setReposCount($reposCount)
    {
        $this->reposCount = $reposCount;
    }

    /**
     * @return boolean
     */
    public function isSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * @param boolean $subscribed
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;
    }
}
