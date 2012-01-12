<?php

namespace Etcpasswd\OAuthBundle\Provider;

use Etcpasswd\OAuthBundle\Provider\Token\FacebookToken;

/**
 * OAuth provider for facebook
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 * @link   http://developers.facebook.com/docs/authentication/
 */
class FacebookProvider extends Provider
{
    /**
     * {@inheritDoc}
     */
    public function createTokenResponse($clientId, $secret, $code, $redirectUrl = "", $service = null)
    {
        $url = 'https://graph.facebook.com/oauth/access_token'
            .'?client_id='.$clientId
            .'&redirect_uri='.$redirectUrl
            .'&client_secret='.$secret
            .'&code='.$code;

        parse_str($this->request($url), $result);

        if (isset($result['error'])) {
            return;
        }

        $accessToken = $result['access_token'];

        $url = 'https://graph.facebook.com/me'
            .'?access_token='.$accessToken;

        $json = json_decode($this->request($url));
        $expiresAt = time() + $result['expires'];

        return new FacebookToken($json, $accessToken, $expiresAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($clientId, $scope, $redirectUrl)
    {
        return 'https://www.facebook.com/dialog/oauth'
            .'?client_id='.$clientId
            .'&redirect_uri='.$redirectUrl
            .'&scope='.$scope;
    }
}