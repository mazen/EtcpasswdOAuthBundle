<?php

namespace Etcpasswd\OAuthBundle\Provider\Token;

/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class GithubToken implements TokenResponseInterface
{

    private $json;
    private $accessToken;

    /**
     * Constructs a new token
     *
     * @param object $jsonObject  Json object
     * @param string $accessToken Api access token
     *
     * @return void
     */
    public function __construct($jsonObject, $accessToken)
    {
        $this->json = $jsonObject;
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpires()
    {
        throw new \LogicException('Token does not expire');
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->json->login;
    }

    /**
     * {@inheritDoc}
     */
    public function isLongLived()
    {
        return true;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

}