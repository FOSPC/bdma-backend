<?php
namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Product;            
use Product\Form\ProductForm;       

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    


// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class ProductController extends AbstractActionController
{
    protected $productTable;

    public function indexAction()
    {        
       $session = new Container('admin');
        if ($session->offsetExists('email')) {
                  //Prepare statistique information pour la vue Index ->des statistiques
                  $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                  $sql1 = "select * from pro_product_bdma";


         $statement1 =$adapter->query($sql1); 


         $results1=$statement1->execute();


         $row1 = $results1->current();

             //tester si les variables rouX ont bien remplis
             if (!$row1)
             {
                //redirection vers l'index avec un message GET var 
                $this->redirect()->toRoute('product',array('action' => 'index'),array('query' => array('status' => 'erreur_fetching_view_data')));          
             }
             else
             {
                // 2 preparation des donnees  result sett , retourne un mass d'information
                 if ($results1 instanceof ResultInterface && $results1->isQueryResult()) 
                {
                    //instanciation  de la class result set pour l'enregistrement des information fournis par la BD
                    $resultSet = new ResultSet;
                    $resultSet->initialize($results1);

                    //  redirection vers la vue index avec les information des statistiques
                    return new ViewModel(array('data'=> $resultSet)); 
                }           
             }            
        }
        else
        {
            $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
          $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));
        }


        if ($request->isPost()) 
        {

                    $name =     strip_tags($this->getRequest()->getPost('name'));
                    $desc =    strip_tags($this->getRequest()->getPost('desc'));
                    $ref =    strip_tags($this->getRequest()->getPost('ref'));
                    $prixm =      strip_tags($this->getRequest()->getPost('prixm'));
                    $prixy =    strip_tags($this->getRequest()->getPost('prixy'));
                    $dateb = strip_tags($this->getRequest()->getPost('dateb'));
                    $datef =     strip_tags($this->getRequest()->getPost('dateb'));



                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');


                    $sql="insert into pro_product_bdma (`id_product_bdma`, `product_bdma_name`, `product_bdma_description`, `product_bdma_reference`, `product_bdma_price_month`, `product_bdma_price_year`, `product_bdma_date_begin_valid`, `product_bdma_date_end_valid`) VALUES ('0','".$name."','".$desc."','".$ref."','".$prixm."','".$prixy."','".$dateb."','".$datef."')";

                    $statement =$adapter->query($sql); 

                    $results=$statement->execute();
                    $this->redirect()->toRoute('product',array('action' => 'index'),array('query' => array('status' => 'y_add')));      
        }
        return new ViewModel();                  
    }  

    

 
    public function editAction()
    {


       $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }

        
        $id = (int) $this->params()->fromRoute('id', 0);


        if (!$id) {
            return $this->redirect()->toRoute('product', array(
                'action' => 'add'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            /*---------------------------lles operation de la modification ----------------------------------*/

                    $name =     strip_tags($this->getRequest()->getPost('name'));
                    $desc =    strip_tags($this->getRequest()->getPost('desc'));
                    $ref =    strip_tags($this->getRequest()->getPost('ref'));
                    $prixm =      strip_tags($this->getRequest()->getPost('prixm'));
                    $prixy =    strip_tags($this->getRequest()->getPost('prixy'));
                    $dateb = strip_tags($this->getRequest()->getPost('dateb'));
                    $datef =     strip_tags($this->getRequest()->getPost('dateb'));



                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

 $sql="update pro_product_bdma SET `product_bdma_name`='".$name."', `product_bdma_description`='".$desc."', `product_bdma_reference`='".$ref."', `product_bdma_price_month`='".$prixm."', `product_bdma_price_year`='".$prixy."', `product_bdma_date_begin_valid`='".$dateb."', `product_bdma_date_end_valid`='".$datef."' WHERE `id_product_bdma`='".$id."'";

                    $statement =$adapter->query($sql); 

                    $results=$statement->execute();
                    $this->redirect()->toRoute('product',array('action' => 'index'),array('query' => array('status' => 'yupdate')));      

           
            /*-----------------------------------------------------------------------------------------------*/
        }
        /*------------------------------preparation des donnes-----------------------------*/
                $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $sql1 = "select * from pro_product_bdma where id_product_bdma='".$id."'";
                $statement1 =$adapter->query($sql1); 
                $results1=$statement1->execute();
                $row1 = $results1->current();
                 //tester si les variables rouX ont bien remplis
                 if (!$row1)
                 {
                    //redirection vers l'index avec un message GET var 
                $this->redirect()->toRoute('product',array('action' => 'index'),array('query' => array('status' => 'nouser')));          
                 }
        /*---------------------------------------------------------------------------------*/
        return new ViewModel(array('prod'=>$row1)); 
    }

   
    public function getProductTable()
    {
        if (!$this->productTable) {
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('Product\Model\ProductTable');
        }
        return $this->productTable;
    }



}
?>
