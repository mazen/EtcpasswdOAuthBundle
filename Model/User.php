<?php
namespace Etcpasswd\OAuthBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Implementation of a User entity authenticated by this provider
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 */
class User implements UserInterface
{
    
    private $username;
    
    /**
     * {@inheritdoc}
     */
    function getRoles()
    {
        return array('ROLE_USER');
    }
    
    /**
     * {@inheritdoc}
     */
    function getPassword()
    {
        return '';
    }
    
    /**
     * {@inheritdoc}
     */
    function getSalt()
    {
        return '';
    }
    
    /**
     * {@inheritdoc}
     */
    function eraseCredentials()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    function equals(UserInterface $user)
    {
        return (
            $user instanceof User
            && $user->getUsername() === $this->getUsername()
        );
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}