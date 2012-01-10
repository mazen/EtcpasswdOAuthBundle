<?php

namespace Etcpasswd\OAuthBundle\Security\Core\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

use Etcpasswd\OAuthBundle\Provider\Token\TokenResponseInterface;
/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class OAuthToken extends AbstractToken
{
    private $response;

    public function __construct($roles = array(), TokenResponseInterface $response)
    {
        parent::__construct($roles);
        $this->response = $response;
        $this->setAttribute('access_token', $response->getAccessToken());
        $this->setAttribute('via', $response->getProviderKey());
        $this->setAttribute('data', $response->getJson());
    }

    public function getCredentials()
    {
        return $this->response->getAccessToken();
    }

    public function eraseCredentials()
    {
        unset($this->response);
        parent::eraseCredentials();
    }

    public function getResponse()
    {
        return $this->response;
    }

}