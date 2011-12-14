<?php

namespace Etcpasswd\OAuthBundle\Security\Http\Firewall;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface,
    Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface,
    Symfony\Component\Security\Http\HttpUtils,
    Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface,
    Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface,
    Symfony\Component\HttpKernel\Log\LoggerInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Etcpasswd\OAuthBundle\Provider\ProviderInterface,
    Etcpasswd\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

/**
 * Authentication listener handling OAuth Authentication requests
 *
 * @author   Marcel Beerta <marcel@etcpasswd.de>
 */
class OAuthListener extends AbstractAuthenticationListener
{
    private $oauthProvider;
    protected $httpUtils;

    /**
     * {@inheritdoc}
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager,
        SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey,
        array $options = array(), AuthenticationSuccessHandlerInterface $successHandler = null,
        AuthenticationFailureHandlerInterface $failureHandler = null, LoggerInterface $logger = null,
        EventDispatcherInterface $dispatcher = null, ProviderInterface $oauthProvider)
    {
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey,
            $options, $successHandler, $failureHandler, $logger, $dispatcher);
        $this->oauthProvider = $oauthProvider;
        $this->httpUtils     = $httpUtils;
    }

    /**
     * {@inheritDoc}
     */
    protected function requiresAuthentication(Request $request)
    {
        if ( $this->httpUtils->checkRequestPath($request, $this->options['check_path'])
            || $this->httpUtils->checkRequestPath($request, $this->options['login_path'])
        ) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {

        // redirect to auth provider
        if ($this->httpUtils->checkRequestPath($request, $this->options['login_path'])) {
            return $this->createProviderRedirectResponse($request);
        }

        $code = $request->get('code');
        $token = $this->oauthProvider
            ->createTokenResponse(
                $this->options['client_id'],
                $this->options['client_secret'],
                $code,
                $this->assembleRedirectUrl($this->options['check_path'], $request)
            );

        if (is_null($token)) {
            throw new AuthenticationException('Authentication failed');
        }

        if (null === $this->options['uid']) {
            $username = $token->getUsername();
        } else {
            $username = $token->getUsername($this->options['uid']);
        }

        $authToken = new OAuthToken(array(), $token);
        $authToken->setUser($username);

        return $this->authenticationManager
            ->authenticate($authToken);
    }

    private function createProviderRedirectResponse(Request $request)
    {
        $url = $this->oauthProvider->getAuthorizationUrl(
            $this->options['client_id'],
            $this->options['scope'],
            $this->assembleRedirectUrl($this->options['check_path'], $request)
        );
        return $this->httpUtils->createRedirectResponse($request, $url);
    }

    private function assembleRedirectUrl($path, Request $request)
    {
        $proto = $request->isSecure() ? 'https' : 'http';

        $url = sprintf('%s://%s%s', $proto, $request->getHost(), $path);
        return urlencode($url);
    }
}
