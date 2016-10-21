<?php
$route = 'social-commerce';
return array(
    'package' =>
        array(
            'type' => 'module',
            'name' => 'socialcommerce',
            'version' => '4.0.1',
            'path' => 'application/modules/Socialcommerce',
            'title' => 'Social - Commerce',
            'description' => '',
            'author' => '<a href="http://4lifemedi.com/" title="University of Technology" target="_blank">University of Technology</a>',
            'callback' => array(
                'path' => 'application/modules/Socialcommerce/settings/install.php',
                'class' => 'Socialcommerce_Installer',
            ),
            array(
                'class' => 'Engine_Package_Installer_Module',
            ),
            'actions' =>
                array(
                    0 => 'install',
                    1 => 'upgrade',
                    2 => 'refresh',
                    3 => 'enable',
                    4 => 'disable',
                ),
            'directories' =>
                array(
                    0 => 'application/modules/Socialcommerce',
                ),
            'files' =>
                array(
                    0 => 'application/languages/en/socialcommerce.csv',
                ),
        ),
    'items' => array(
        'socialcommerce_listing',
        'socialcommerce_category',
        'socialcommerce_stall',
        'socialcommerce_review',
    ),

    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onItemCreateAfter',
            'resource' => 'Socialcommerce_Plugin_Core',
        ),
        array(
            'event' => 'addActivity',
            'resource' => 'Socialcommerce_Plugin_Core'
        ),
        array(
            'event' => 'getActivity',
            'resource' => 'Socialcommerce_Plugin_Core'
        ),
    ),

    'routes' => array(
        'socialcommerce_extended' => array(
            'route' => $route.'/:controller/:action/*',
            'defaults' => array(
                'module' => 'socialcommerce',
                'controller' => 'index',
                'action' => 'index',
            ),
            'reqs' => array(
                'controller' => '\D+',
                'action' => '\D+',
            )
        ),

        'socialcommerce_general' => array(
            'route' => 'social-commerce/:controller/:action/*',
            'defaults' => array(
                'module' => 'socialcommerce',
                'controller' => 'index',
                'action' => 'index',
            ),
            'reqs' => array(
                'controller' => '\D+',
                'action' => '\D+',
            )
        ),

        'socialcommerce_profile' => array(
            'route' => $route.'/stall/:stall_id/:slug/*',
            'defaults' => array(
                'module' => 'socialcommerce',
                'controller' => 'stall',
                'action' => 'profile',
                'slug' => '',
            ),
            'reqs' => array(
                'id' => '\d+',
            )
        ),

        'socialcommerce_product_general' => array(
            'route' => 'social-commerce/stall/:stall_id/products/:action/*',
            'defaults' => array(
                'module' => 'socialcommerce',
                'controller' => 'product',
                'action' => 'index',
            ),
            'reqs' => array(
                'controller' => '\D+',
                'action' => '\D+',
            )
        ),
    )

); ?>