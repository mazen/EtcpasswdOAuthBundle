<?php

namespace Etcpasswd\OAuthBundle\Provider\OAuth1;

use Etcpasswd\OAuthBundle\Provider\Provider;

/**
 * Base class for OAuth 1.0a compatible providers
 * which contains all necessary signing methods
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 */
abstract class OAuth1Provider extends Provider
{
    /**
     * Creates a OAuth Nonce
     *
     * @param int  $length      How many characteres the nonce should be
     * @param bool $includeTime Whether to include a timestamp at the beginning
     *
     * @return string
     */
    protected function createNonce($length=12, $includeTime = true)
    {
      $sequence = array_merge(range(0,9), range('A','Z'), range('a','z'));
      $length = $length > count($sequence) ? count($sequence) : $length;
      shuffle($sequence);

      $prefix = $includeTime ? microtime() : '';
      return md5(substr($prefix.implode($sequence), 0, $length));
    }

    /**
     * Creates the signature string for a request
     *
     *
     */
    protected function sign($method, $baseUri, $params = array(), $signKey) {

        $encodedParams = array();
        foreach (asort($params) as $key => $value) {
            $paramString = urlencode($key).'%3D'.urlencode($value);
            $encodedParams[] = $paramString;
        }

        $baseString = sprintf('%s&%s&%s',
            $method,
            urlencode($baseUri),
            implode('%26', $encodedParams)
        );

        return base64_encode(hash_hmac_file('sha1', $baseString, $signKey,true));
    }
}
