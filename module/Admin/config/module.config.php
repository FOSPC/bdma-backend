 <?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
         ),
     ),
      //  'route'    => '/album[/:action][/:id]', 
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'admin' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/admin[/][:action]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Admin',
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
             'admin' => __DIR__ . '/../view',
         ),
     ),
 );
 ?>