<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/14/2016
 * Time: 9:17 PM
 */
return array(
    array(
        'title' => 'Social Commerce - Main Menu',
        'description' => 'Displays a menu in the main page.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.main-menu',
        'requirements' => array(
            'no-subject',
        ),
    ),

    array(
        'title' => 'Social Commerce - Categories',
        'description' => 'Displays a list of categories.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.categories',
        'defaultParams' => array(
            'title' => 'Categories',
        ),
    ),
    array(
        'title' => 'Social Commerce - Browse Search',
        'description' => 'Displays a search form in the stall/product browse page.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.browse-search',
        'requirements' => array(
            'no-subject',
        ),
    ),

    array(
        'title' => 'Social Commerce - Browse Search',
        'description' => 'Displays a search form in the stall/product browse page.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.browse-search',
        'requirements' => array(
            'no-subject',
        ),
    ),

    array(
        'title' => 'Social Commerce - Quick Links',
        'description' => 'Displays a list of quick links.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.home-links',
        'requirements' => array(
            'viewer',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Newest Videos',
        'description' => 'Displays Newest Videos of Stall on Stall Detail page',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-newest-videos',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Newest Videos',
            'itemCountPerPage' => 1,
        ),
        'requirements' => array(
            'subject' => 'socialcommerce_stall',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Profile Videos',
        'description' => 'Displays Stall Videos on Stall Detail page',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-profile-videos',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Videos',
            'titleCount' => true,
            'itemCountPerPage' => 5
        ),
        'requirements' => array(
            'subject' => 'socialcommerce_stall',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Profile Shop',
        'description' => 'Displays Stall Products on Stall Detail page',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-profile-products',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Shop',
            'titleCount' => true,
            'itemCountPerPage' => 5
        ),
        'requirements' => array(
            'subject' => 'socialcommerce_stall',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Profile Cover',
        'description' => 'Displays a stall cover and information on it\'s profile.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-profile-cover',
        'requirements' => array(
            'subject' => 'advgroup',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Profile Overview',
        'description' => 'Displays Stall Overview on Stall Detail page',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-profile-overview',
        'defaultParams' => array(
            'title' => 'Overview',
        ),
        'requirements' => array(
            'subject' => 'socialcommerce_stall',
        ),
    ),

    array(
        'title' => 'Social Commerce - Stall Listings',
        'description' => 'Displays stall listings in browse page.',
        'category' => 'Social Commerce',
        'type' => 'widget',
        'name' => 'socialcommerce.stall-listings',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Stall Listings',
        ),
        'requirements' => array(
            'no-subject',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'title',
                    array(
                        'label' => 'Title'
                    )
                ),
                array(
                    'Heading',
                    'mode_enabled',
                    array(
                        'label' => 'Which view modes are enabled?'
                    )
                ),
                array(
                    'Radio',
                    'mode_list',
                    array(
                        'label' => 'List view.',
                        'multiOptions' => array(
                            1 => 'Yes.',
                            0 => 'No.',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'mode_grid',
                    array(
                        'label' => 'Grid view.',
                        'multiOptions' => array(
                            1 => 'Yes.',
                            0 => 'No.',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'mode_pin',
                    array(
                        'label' => 'Pin view.',
                        'multiOptions' => array(
                            1 => 'Yes.',
                            0 => 'No.',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'mode_map',
                    array(
                        'label' => 'Map view.',
                        'multiOptions' => array(
                            1 => 'Yes.',
                            0 => 'No.',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'view_mode',
                    array(
                        'label' => 'Which view mode is default?',
                        'multiOptions' => array(
                            'list' => 'List view.',
                            'grid' => 'Grid view.',
                            'pin' => 'Pin view.',
                            'map' => 'Map view.',
                        ),
                        'value' => 'list',
                    )
                ),

            )
        ),
    ),
) ?>