<?php

namespace Etcpasswd\OAuthBundle\Provider;

use Buzz\Message\Request;
use Buzz\Message\Response;
use Etcpasswd\OAuthBundle\Provider\Token\GoogleToken;

/**
 * OAuth provider for google
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 * @link   http://code.google.com/apis/accounts/docs/OAuth2.html
 */
class GoogleProvider extends Provider
{
    /**
     * {@inheritDoc}
     */
    public function createTokenResponse($clientId, $secret, $code, $redirectUrl = "")
    {
        $url = 'https://www.google.com/accounts/o8/oauth2/token';

        $request = new Request(Request::METHOD_POST, $url);
        $request->setContent(http_build_query(array(
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $secret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => urldecode($redirectUrl)
        )));

        $response = new Response();
        $this->client->send($request, $response);

        $data = json_decode($response->getContent());
        if (isset($data->error)) {
            return;
        }
        $expiresAt = time() + $data->expires_in;

        $people = 'https://www.googleapis.com/plus/v1/people/me'
            .'?key='.$clientId
            .'&access_token='.$data->access_token;
        $me = json_decode($this->request($people));

        return new GoogleToken($me, $data->access_token, $expiresAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($clientId, $scope, $redirectUrl)
    {
        return 'https://accounts.google.com/o/oauth2/auth'
            .'?client_id='.$clientId
            .'&redirect_uri='.$redirectUrl
            .'&scope='.urlencode($scope)
            .'&response_type=code';
    }
}