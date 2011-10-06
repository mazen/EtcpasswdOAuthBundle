<?php

namespace Etcpasswd\OAuthBundle\Security\Core\User;

use Etcpasswd\OAuthBundle\Model\User;

use Symfony\Component\HttpKernel\KernelInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * A User provider which is using the OAuth bundle to generate new
 * "abstract" suers
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 *
 */
class UserProvider implements UserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = new User();
        $user -> setUsername($username);
        return $user;
    }
    
    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $user = $this->loadUserByUsername($user->getUsername());
        return $user;
    }
    
    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Etcpasswd\OAuthBundle\Model\User';
    }
    
}