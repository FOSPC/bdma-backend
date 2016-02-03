 <?php



 return array(
     'controllers' => array(
         'invokables' => array(
             'Invoice\Controller\Invoice' => 'Invoice\Controller\InvoiceController',
         ),
     ),
      //  'route'    => '/album[/:action][/:id]', 
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'invoice' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/invoice[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Invoice\Controller\Invoice',
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
             'invoice' => __DIR__ . '/../view',
         ),
     ),
 );

 ?>