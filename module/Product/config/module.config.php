 <?php


 return array(
     'controllers' => array(
         'invokables' => array(
             'Product\Controller\Product' => 'Product\Controller\ProductController',
         ),
     ),
      //  'route'    => '/album[/:action][/:id]', 
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'product' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/product[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Product\Controller\Product',
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
             'product' => __DIR__ . '/../view',
         ),
     ),
 );

 ?>