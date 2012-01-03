<?php

namespace Etcpasswd\OAuthBundle\Security\Core\User;

use Symfony\Component\HttpKernel\KernelInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * A User provider which is using the OAuth bundle to generate new
 * "abstract" users
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 *
 */
class UserProvider implements UserProviderInterface
{
    protected $supportedClass;

    public function __construct($supportedClass)
    {
        $this->supportedClass = $supportedClass;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = new $this->supportedClass;
        $user->setUsername($username);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->supportedClass;
    }
}