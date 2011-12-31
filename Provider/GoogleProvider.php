<?php

namespace Etcpasswd\OAuthBundle\Provider;

use Etcpasswd\OAuthBundle\Provider\Token\GoogleToken;

use Buzz\Message\Request,
    Buzz\Message\Response;
/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class GoogleProvider extends Provider
{

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
        $expiresAt = time()+$data->expires_in;

        //$people = 'https://www.googleapis.com/plus/v1/people/me'
        $people = 'https://www.googleapis.com/oauth2/v1/userinfo'
            .'?key='.$clientId
            .'&access_token='.$data->access_token;
        $request = new Request(Request::METHOD_GET, $people);
        $response = new Response();

        $this->client->send($request, $response);
        $me = json_decode($response->getContent());

        return new GoogleToken($me, $data->access_token, $expiresAt);
    }

    public function getAuthorizationUrl($clientId, $scope, $redirectUrl)
    {
        return 'https://accounts.google.com/o/oauth2/auth'
            .'?client_id='.$clientId
            .'&redirect_uri='.$redirectUrl
            .'&state=' . urlencode('/profile')
            .'&scope='.urlencode($scope)
            .'&response_type=code';
    }

}