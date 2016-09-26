<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'ut-socialcommerce',
    'version' => '4.0.1',
    'path' => 'application/modules/UtSocialcommerce',
    'title' => 'UT - Social Commerce',
    'description' => '',
    'author' => '<a href="http://4lifemedi.com/" title="University of Technology" target="_blank">University of Technology</a>',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/UtSocialcommerce',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/ut-socialcommerce.csv',
    ),
  ),
); ?>