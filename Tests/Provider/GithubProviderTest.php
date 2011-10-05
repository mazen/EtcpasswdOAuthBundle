<?php

namespace Etcpasswd\OAuthBundle\Provider;

use PHPUnit_Framework_TestCase;

/**
 * Test class for GithubProvider.
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class GithubProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GithubProvider
     */
    protected $object;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->object = new GithubProvider;
    }

    public function testAuthorizationUrlCreation()
    {
        $expected = 'https://github.com/login/oauth/authorize'
            .'?client_id=foo&scope=user&redirect_url=http%3A%2F%2Fexample.org';

        $this->assertEquals(
            $expected,
            $this->object
                ->getAuthorizationUrl('foo', 'user', 'http://example.org')
        );
    }

}
