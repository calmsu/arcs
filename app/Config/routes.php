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
    Router::redirect('/', 
        array('controller' => 'resources', 'action' => 'search')
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
    Router::connect('/signup', 
        array('controller' => 'users', 'action' => 'add')
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

    # Resource, collection, and user singular aliases
    Router::connect('/resource/*', 
        array('controller' => 'resources', 'action' => 'view')
    );
    Router::connect('/collection/*', 
        array('controller' => 'collections', 'action' => 'view')
    );
    Router::connect('/user/*', 
        array('controller' => 'users', 'action' => 'view')
    );

    # Search
    Router::connect('/search/**', 
        array('controller' => 'resources', 'action' => 'search')
    );

    # Search must have a trailing slash, for the client-side code's
    # sanity. IMO it shouldn't be optional to begin with.
    Router::redirect('/search', '/search/');

    # Configuration status
    Router::connect('/status',
        array('controller' => 'pages', 'action' => 'status')
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

    Router::resourceMap(array(
        array('action' => 'index', 'method' => 'GET', 'id' => false),
        array('action' => 'view', 'method' => 'GET', 'id' => true),
        array('action' => 'add', 'method' => 'POST', 'id' => false),
        array('action' => 'edit', 'method' => 'PUT', 'id' => true),
        array('action' => 'delete', 'method' => 'DELETE', 'id' => true),
        array('action' => 'update', 'method' => 'POST', 'id' => true)
    ));
    # Map resources for the ajax-only controllers
    Router::mapResources(array(
        'resources',
        'comments',
        'keywords',
        'hotspots',
        'bookmarks',
        'flags'
    ));
    Router::parseExtensions();

	CakePlugin::routes();
	require CAKE . 'Config' . DS . 'routes.php';
