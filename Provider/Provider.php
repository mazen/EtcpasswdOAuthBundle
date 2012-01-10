<?php

namespace Etcpasswd\OAuthBundle\Provider;

use Buzz\Client\ClientInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;

/**
 * Base Provider class
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
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
        if (null === $method) {
            $method = Request::METHOD_GET;
        }

        $request = new Request($method, $url);
        $response = new Response();
        $this->client->send($request, $response);

        return $response->getContent();
    }
}