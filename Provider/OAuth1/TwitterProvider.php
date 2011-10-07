<?php
namespace Etcpasswd\OAuthBundle\Provider\OAuth1;

/**
 * Implementation of OAuth 1.0a for the Twitter Api
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 */
class TwitterProvider extends OAuth1Provider
{
    public function createTokenResponse($clientId, $secret, $code, $redirectUrl = "")
    {

    }

    /**
     * Returns the URL which is called to authorize a user
     *
     * @param string $clientId    Client Id
     * @param string $scope       Scope requested
     * @param string $redirectUrl URL To redirect to after authorization
     *
     * @return string
     */
    public function getAuthorizationUrl($clientId, $scope, $redirectUrl)
    {
        $token = $this->createRequestToken($clientId, $redirectUrl);

        return 'https://api.twitter.com/oauth/authorize?oauth_token='.$token;
    }

    /**
     * Creates a new request token
     *
     * @param string $clientId    Client Id
     * @param string $redirectUrl Redirect Url
     *
     * @return string The generated token
     */
    private function createRequestToken($clientId, $redirectUrl)
    {
        $nonce = $this->createNonce();

        $params = array(
            'oauth_nonce'               => $nonce,
            'oauth_callback'            => $redirectUrl,
            'oauth_signature_method'    => 'HMAC-SHA1',
            'oauth_timestamp'           => time(),
            'oauth_consumer_key'        => $consumerKey,
            'oauth_version'             => '1.0'
        );

        $signKey .= '&';

        $signature = $this->sign(
            'POST',
            'https://api.twitter.com/oauth/request_token',
            $params,
            $signKey
        );

        $body = 'OAuth oauth_nonce="%s", oauth_callback="%s", oauth_signature_met'
    }
}
