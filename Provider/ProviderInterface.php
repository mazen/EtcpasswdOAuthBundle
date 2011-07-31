<?php

namespace Etcpasswd\OAuthBundle\Provider;

/**
 * Interface
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
interface ProviderInterface
{
    /**
     * Returns the URL which is called to authorize a user
     *
     * @param string $client_id    Client Id
     * @param string $scope        Scope requested
     * @param string $redirect_url URL To redirect to after authorization
     *
     * @return string
     */
    function getAuthorizationUrl($clientId, $scope, $redirectUrl);

    /**
     * Returns an authorization token which contains data about the given user
     *
     *
     * @param string $client_id    Client Id
     * @param string $secret       Client Secret
     * @param string $redirect_url Redirect url
     * @param string $code         Authentication code
     *
     * @return TokenResponseInterface|null
     */
    function createTokenResponse($clientId, $secret, $code, $redirectUrl = "");


}