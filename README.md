EtcpasswdOAuthBundle - [Test] Pfister Edition!
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
            uid:           email
            scope:         requested scope
            login_path:    /login
            check_path:    /auth
            failure_path:  /

    factories:
      - "%kernel.root_dir%/../vendor/bundles/Etcpasswd/OAuthBundle/Resources/config/security_factories.xml"

Please not that you do not need to build any controllers for either the
login_path or the check_path. They are only used internally to identify
when a login needs to happen.

Also note that you still have to provide a user provider. This bundle only authenticates the user based on an OAuth service but does not create any User object itself.

Specifying multiple OAuth2 Providers
====================================
This package also allows to use different providers at once for signing in.
All you have to do is to add those providers to the security.yml

Example:

	firewalls:
	    main:
	      anonymous: true
	      logout: true
	      pattern: ^/

	      oauth_github:
	        auth_provider: "github"
	        client_id: xxx
	        client_secret: xxx
	        scope: repo,user
	        login_path: /login/github
	        check_path: /auth/github
	        failure_path:  /

	      oauth_facebook:
	        auth_provider: "facebook"
	        client_id:     xxx
	        client_secret: xxx
	        scope:         ""
	        login_path:    /login/facebook
	        check_path:    /auth/facebook
	        failure_path:  /

	      oauth_google:
	        auth_provider: "google"
	        client_id:     xxx
	        client_secret: xxx
	        scope:         "https://www.googleapis.com/auth/plus.me"
	        login_path:    /login/google
	        check_path:    /auth/google
	        failure_path:  /

Calling either /login/github, /login/facebook or /login/google will then use
the correct oauth provider.

Builtin OAuth Providers
=======================
This bundle ships with the following builtin providers:

* Github
* Facebook
* Google

Notes on Google: you need to at least provide the scope `https://www.googleapis.com/auth/plus.me`
in order to get a username

Authorizing users
=================
This bundle does not ship with any way of authorization of users and / or persitant state. You should have a look 
at the https://github.com/FriendsOfSymfony/FOSUserBundle for that. 
If you want to use those users anyways, without ever wanting to persist them into a database, you can though add 
the provider shipped with this bundle to your security configuration.
This will allow you to access the accessToken via the Security Context service to query other API services from the 
given provider.

Example security.yml:

    security:
      firewalls:
        main:
          anonymous: true
          logout: true
          pattern: ^/

          oauth_github:
            auth_provider: "github"
            client_id:     xxx
            client_secret: xxx
            scope: repo,user
            login_path: /login/github
            check_path: /auth/github
            failure_path:  /

      role_hierarchy:
        ROLE_ADMIN: [ROLE_USER]

      providers:
        main:
          id: etcpasswd_oauth.user.provider
      
      access_control: ~  

      factories:
        - "%kernel.root_dir%/../vendor/bundles/Etcpasswd/OAuthBundle/Resources/config/security_factories.xml"
