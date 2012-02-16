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
        array('controller' => 'resources', 'action' => 'add')
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

    # Configuration status
    Router::connect('/status',
        array('controller' => 'pages', 'action' => 'status')
    );

    # Resource hasMany routes
    Router::connect('/resources/:id/comments',
        array('controller' => 'resources', 'action' => 'comments'),
        array('id' => '[\d]+')
    );
    Router::connect('/resources/:id/tags',
        array('controller' => 'resources', 'action' => 'tags'),
        array('id' => '[\d]+')
    );
    Router::connect('/resources/:id/hotspots',
        array('controller' => 'resources', 'action' => 'hotspots'),
        array('id' => '[\d]+')
    );

    # Pages routes
    Router::connect('/pages/*', 
        array('controller' => 'pages', 'action' => 'display')
    );

    # Docs routes
    Router::connect('/docs',
        array('controller' => 'docs', 'action' => 'display', 'index')
    );
    Router::connect('/docs/*', 
        array('controller' => 'docs', 'action' => 'display')
    );

    # Map resources for the ajax-only controllers
    Router::mapResources(array(
        'comments',
        'tags',
        'hotspots',
        'bookmarks'
    ));

    # Parse extensions (not super useful for us)
    Router::parseExtensions();

	CakePlugin::routes();
	require CAKE . 'Config' . DS . 'routes.php';
