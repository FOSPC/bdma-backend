 <?php


 return array(
     'controllers' => array(
         'invokables' => array(
             'Company\Controller\Company' => 'Company\Controller\CompanyController',
         ),
     ),
      //  'route'    => '/album[/:action][/:id]', 
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'company' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/company[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Company\Controller\Company',
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
             'company' => __DIR__ . '/../view',
         ),
     ),
 );

 ?>