<?php

namespace Etcpasswd\OAuthBundle\Provider\Token;

/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class GoogleToken implements TokenResponseInterface
{

    private $json;
    private $accessToken;
    private $expiresAt;

    /**
     * Constructs a new token
     *
     * @param object $jsonObject  Json object
     * @param string $accessToken Api access token
     *
     * @return void
     */
    public function __construct($jsonObject, $accessToken, $expiresAt)
    {
        $this->json = $jsonObject;
        $this->accessToken = $accessToken;
        $this->expiresAt = $expiresAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpires()
    {
        return $this->expiresAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->json->data->displayName;
    }

    /**
     * {@inheritDoc}
     */
    public function isLongLived()
    {
        return false;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

}