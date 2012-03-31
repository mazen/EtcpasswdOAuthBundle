<?php

namespace Etcpasswd\OAuthBundle\Provider\Token;

/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
interface TokenResponseInterface
{

    /**
     * Return the username attached to this token
     *
     * @return string
     */
    function getUsername();

    /**
     * Return the access token which can be used for
     * further api calls
     *
     * @return string
     */
    function getAccessToken();

    /**
     * Returns the unix timestamp when this token will expire
     * and api calls are no longer be possible
     *
     * @return int
     */
    function getExpires();

    /**
     * Returns wether or not this token is expiring at all
     *
     * @return bool
     */
    function isLongLived();

    /**
     * Returns the provider id used to generate the access
     * token
     *
     * @returun string
     */
    function getProviderKey();

    /**
     * Returns the json data about access token
     *
     * @returun \stdClass
     */
    function getJson();
}