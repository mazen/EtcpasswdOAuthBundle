<?php

namespace Etcpasswd\OAuthBundle\Provider;

use Etcpasswd\OAuthBundle\Provider\Token\GithubToken;

/**
 * OAuth provider for github
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 * @link   http://developer.github.com/v3/oauth
 */
class GithubProvider extends Provider
{
    /**
     * {@inheritDoc}
     */
    public function createTokenResponse($clientId, $secret, $code, $redirectUrl = "", $service = null)
    {
        $url = 'https://github.com/login/oauth/access_token'
            .'?client_id='.$clientId
            .'&redirect_url='.$redirectUrl
            .'&client_secret='.$secret
            .'&code='.$code
            .'&grant_type=authorization_code';

        $response = parse_str($this->request($url), $result);

        if (isset($result['error'])) {

         // @todo: handling of backend errors
//          error
//                 REQUIRED.  A single error code from the following:
//                 invalid_request
//                       The request is missing a required parameter, includes an
//                       unsupported parameter or parameter value, or is otherwise
//                       malformed.
//                 unauthorized_client
//                       The client is not authorized to request an authorization
//                       code using this method.
//                 access_denied
//                       The resource owner or authorization server denied the
//                       request.
//                 unsupported_response_type
//                       The authorization server does not support obtaining an
//                       authorization code using this method.
//                 invalid_scope
//                       The requested scope is invalid, unknown, or malformed.
//                 server_error
//                       The authorization server encountered an unexpected
//                       condition which prevented it from fulfilling the request.
//                 temporarily_unavailable
//                       The authorization server is currently unable to handle
//                       the request due to a temporary overloading or maintenance
//                       of the server.
//           error_description
//                         OPTIONAL.  A human-readable UTF-8 encoded text providing
//                         additional information, used to assist the client developer in
//                         understanding the error that occurred.
//           error_uri
//                         OPTIONAL.  A URI identifying a human-readable web page with
//                         information about the error, used to provide the client
//                         developer with additional information about the error.

            return;
        }

        // call user api to fetch some details
        $accessToken = $result['access_token'];

        $url = 'https://api.github.com/user?access_token='.$accessToken;
        $jsonObject = json_decode($this->request($url));

        return new GithubToken($jsonObject, $result['access_token']);
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($clientId, $scope, $redirectUrl)
    {
        return 'https://github.com/login/oauth/authorize'
            .'?client_id='.$clientId
            .'&scope='.$scope
            .'&redirect_url='.urlencode($redirectUrl);
    }
}