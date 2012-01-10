<?php

namespace Etcpasswd\OAuthBundle\Provider;

/**
 * Provider interface
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 */
interface ProviderInterface
{
    /**
     * Returns an authorization token which contains data about the given user
     *
     * @param string $clientId    Client Id
     * @param string $secret      Client Secret
     * @param string $code        Authentication code
     * @param string $redirectUrl Redirect url
     *
     * @return TokenResponseInterface|null
     */
    function createTokenResponse($clientId, $secret, $code, $redirectUrl = "");

    /**
    * Returns the URL which is called to authorize a user
    *
    * @param string $clientId    Client Id
    * @param string $scope       Scope requested
    * @param string $redirectUrl URL To redirect to after authorization
    *
    * @return string
    */
    function getAuthorizationUrl($clientId, $scope, $redirectUrl);
}