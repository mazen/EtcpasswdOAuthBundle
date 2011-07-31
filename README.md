EtcpasswdOAuthBundle
==============

This bundle is is still under development, things might change!

Installation
============

using svn:

    $ git clone https://github.com/mazen/EtcpasswdOAuthBundle.git vendor/bundles/Etcpasswd/OAuthBundle
    $ git clone https://github.com/kriswallsmith/Buzz.git vendor/buzz

register the namespaces in your autoloader:

    # app/autoload.php
    $loader->registerNamespaces(array(
        'Etcpasswd'        => __DIR__.'/../vendor/bundles',
        'Buzz'             => __DIR__.'/../vendor/buzz/lib',
        // .. your other namespaces
    ));

register the bundle within your Application's Kernel:

    # app/AppKernel.php
    $bundles = array(
        new Etcpasswd\OAuthBundle\EtcpasswdOAuthBundle(),
        // .. other bundles
    );

Configure your security firewall:

    # app/config/security.yml

    firewalls:
        oauth:
          anonymous: true
          logout: true
          pattern: ^/
          oauth:
            auth_provider: api provider
            client_id:     client id
            client_secret: secret
            scope:         requested scope
            login_path:    /login
            check_path:    /auth
            failure_path:  /

    factories:
      - "%kernel.root_dir%/../vendor/bundles/Etcpasswd/OAuthBundle/Resources/config/security_factories.xml"

Please not that you do not need to build any controllers for either the
login_path or the check_path. They are only used internally to identify
when a login needs to happen.

Builtin OAuth Providers
=======================
This bundle ships with the following builtin providers:

* Github
* Facebook
* Google
* <strike>Twitter</strike>
* <strike>Yahoo!</strike>

Notes on Google: you need to at least provide the scope `https://www.googleapis.com/auth/buzz.readonly`
in order to get a username


Adding a custom OAuth Provider
==============================


