<?php

namespace Etcpasswd\OAuthBundle\Provider;


use Buzz\Client\ClientInterface,
    Buzz\Message\Request,
    Buzz\Message\Response;

/**
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
abstract class Provider implements ProviderInterface
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function request($url, $method = null)
    {
        $method = is_null($method) ? Request::METHOD_GET : $method;
        $request = new Request($method, $url);
        $response = new Response();
        $this->client->send($request, $response);
        return $response->getContent();
    }

}