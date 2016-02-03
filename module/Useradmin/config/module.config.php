 <?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Useradmin\Controller\Useradmin' => 'Useradmin\Controller\UseradminController',
         ),
     ),
      //  'route'    => '/album[/:action][/:id]', 
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'useradmin' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/useradmin[/][:action]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'useradmin\Controller\useradmin',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     'translator' => array(
        'local'=>'en_US',
        'translation_file_patterns' => array(
            array(
              'type'     => 'gettext',
              'base_dir' => __DIR__ . '/../language',
              'pattern'  => '%s.mo',
              'text_domain' => __NAMESPACE__,
            ),
        ),
    ),

     'view_manager' => array(
         'template_path_stack' => array(
             'useradmin' => __DIR__ . '/../view',
         ),
     ),
 );
 ?>