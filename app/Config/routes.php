<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

    # Home page
    Router::connect('/', 
        array('controller' => 'pages', 'action' => 'display', 'home')
    );

    # Error pages
    Router::connect('/404', 
        array('controller' => 'pages', 'action' => 'display', '404')
    );
    Router::connect('/500', 
        array('controller' => 'pages', 'action' => 'display', '500')
    );

    # About
    Router::connect('/about', 
        array('controller' => 'pages', 'action' => 'display', 'about')
    );

    # Signup
    Router::connect('/register/*', 
        array('controller' => 'users', 'action' => 'register')
    );

    # Login/logout
    Router::connect('/login', 
        array('controller' => 'users', 'action' => 'login')
    );
    Router::connect('/logout', 
        array('controller' => 'users', 'action' => 'logout')
    );

    # Upload
    Router::connect('/upload',
        array('controller' => 'uploads', 'action' => 'batch')
    );

    # Resource, collection and user singular aliases
    Router::connect('/resource/*', 
        array('controller' => 'resources', 'action' => 'viewer')
    );
    Router::connect('/collection/*', 
        array('controller' => 'collections', 'action' => 'viewer')
    );
    Router::connect('/user/*', 
        array('controller' => 'users', 'action' => 'profile')
    );

    # Search 
    # (we're using the greedy pattern so that we can match urls with slashes, 
    # e.g. 'search/filetype:application/pdf')
    Router::connect('/search/**', 
        array('controller' => 'pages', 'action' => 'search')
    );

    # Search must have a trailing slash, for the client-side code's sanity. 
    Router::redirect('/search', '/search/');

    # Configuration status
    Router::redirect('/admin',
        array('controller' => 'admin', 'action' => 'status')
    );

    # Pages routes
    Router::connect('/pages/*', 
        array('controller' => 'pages', 'action' => 'display')
    );

    # Docs routes
    Router::connect('/help',
        array('controller' => 'help', 'action' => 'display', 'index')
    );
    Router::connect('/help/*', 
        array('controller' => 'help', 'action' => 'display')
    );

    # Map resources for the API controllers
    Router::mapResources(array(
        'resources',
        'comments',
        'keywords',
        'annotations',
        'bookmarks',
        'users',
        'flags',
        'jobs',
        'metadata'
    ));
    Router::parseExtensions();

	CakePlugin::routes();
	require CAKE . 'Config' . DS . 'routes.php';
