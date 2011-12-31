<?php

namespace Etcpasswd\OAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\DefinitionDecorator,
    Symfony\Component\Config\Definition\Builder\NodeDefinition,
    Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

/**
 * OAuth Factory for setting up oauth related services hooking into
 * the security component
 *
 * @author Marcel Beerta <marcel@etcpasswd.de>
 */
class OAuthFactory extends AbstractFactory
{
    /**
     * The factory key. This is used by the security framework
     * to check which factory to invoke.
     *
     * @var string
     */
    protected $key;

    /**
     * Client options
     *
     * @var array
     */
    protected $options = array(
        'client_id'                      => null,
        'client_secret'                  => null,
        'auth_provider'                  => null,
        'scope'                          => null,
        'uid'                            => null,
        'check_path'                     => '/login_check',
        'login_path'                     => '/login',
        'use_forward'                    => false,
        'always_use_default_target_path' => false,
        'default_target_path'            => '/',
        'target_path_parameter'          => '_target_path',
        'use_referer'                    => false,
        'failure_path'                   => null,
        'failure_forward'                => false,
        'remember_me'                    => false,
    );

    /**
     * {@inheritDoc}
     */
    protected function createAuthProvider(ContainerBuilder $container, $id,
        $config, $userProviderId)
    {
        $provider       = 'etcpasswd_oauth.authentication.provider.oauth.'.$id;

        $container
            ->setDefinition($provider,
                new DefinitionDecorator('etcpasswd_oauth.authentication.provider.oauth')
            )
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(1, $id);

        return $provider;
    }

    /**
     * {@inheritDoc}
     */
    protected function createListener($container, $id, $config, $userProvider)
    {
        $providerType   = $config['auth_provider'];
        $id = $id.'.'.$providerType;

        $oAuthProvider = sprintf('etcpasswd_oauth.provider.%s', $providerType);
        $listenerId = parent::createListener($container, $id, $config, $userProvider);

        $listener = $container->getDefinition($listenerId);
        $listener ->replaceArgument(10, new Reference($oAuthProvider));

        return $listenerId;
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
        parent::addConfiguration($node);

        $node->children()
            ->scalarNode('auth_provider')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('client_id')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('client_secret')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('uid')->defaultNull()->end()
            ->scalarNode('scope')->defaultValue('')->end()
            ->scalarNode('failure_path')->cannotBeEmpty()->end();
    }

    /**
     * {@inheritDoc}
     */
    protected function isRememberMeAware($config)
    {
        return $config['remember_me'];
    }

    /**
     * @{inheritDoc}
     */
    protected function getListenerId()
    {
        return 'etcpasswd_oauth.authentication.listener.oauth';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return ($this->key !== null) ? $this->key : 'oauth';
    }

    /**
     * Allows for overriding the provided key so that multiple instances of this factory can be generated
     * using different keys.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return 'http';
    }
}