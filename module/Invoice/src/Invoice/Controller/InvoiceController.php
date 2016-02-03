<?php
namespace Invoice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Invoice\Model\Invoice;            
use Invoice\Form\InvoiceForm;       

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    

// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class InvoiceController extends AbstractActionController
{
    protected $invoiceTable;

    public function indexAction()
    {        
        $session = new Container('admin');
        if ($session->offsetExists('email')) {
                  //Prepare statistique information pour la vue Index ->des statistiques
                  $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                  $sql1 = "select * from pro_invoice limit 50";
                   $statement1 =$adapter->query($sql1); 
                   $results1=$statement1->execute();
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
        else
        {
            $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }
    }
    public function detaillAction()
    {       
        $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('invoice');
        }

        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $sql1 = "select pp.*,ppb.product_bdma_name,ppb.product_bdma_reference,ppb.product_bdma_price_month from pro_product pp,pro_product_bdma ppb where pp.invoice=".$id." and pp.product=ppb.id_product_bdma;";
        $statement1 =$adapter->query($sql1); 
        $results1=$statement1->execute();
        
         if ($results1 instanceof ResultInterface && $results1->isQueryResult()) 
        {
            $resultSet = new ResultSet;
            $resultSet->initialize($results1);
            return new ViewModel(array('data'=> $resultSet,'inv'=>$id)); 
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

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('invoice');
        }


        if ($request->isPost()) 
        {
                    $user =     strip_tags($this->getRequest()->getPost('user'));
                    $company =    strip_tags($this->getRequest()->getPost('company'));
                    $invoice =    strip_tags($this->getRequest()->getPost('invoice'));
                    $serviceprovider =      strip_tags($this->getRequest()->getPost('serviceprovider'));
                    $product =    strip_tags($this->getRequest()->getPost('product'));
                    $time = strip_tags($this->getRequest()->getPost('time'));



                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');


$sql="insert INTO pro_product (`id`, `user`, `company`, `invoice`, `product`, `serviceprovider`, `time`) VALUES ('0', '".$user."', '".$company."', '".$id."', '".$product."', '".$serviceprovider."', '".$time."')";

                    $statement =$adapter->query($sql); 

                    $results=$statement->execute();
                    $this->redirect()->toRoute('invoice',array('action' => 'detaill','id'=>$id),array('query' => array('status' => 'y_add')));      
        }
        /*************************Preparation des liste****************************************/
        $adapteru = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $adapterc = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $adapterp = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $sqlu = "select pu.id,pu.name from pro_user pu";
        $sqlc = "select pc.id,pc.enterprise from pro_company pc";
        $sqlp = "select id_product_bdma,product_bdma_name from pro_product_bdma";

        $statementu =$adapteru->query($sqlu); 
        $statementc =$adapterc->query($sqlc); 
        $statementp =$adapterp->query($sqlp);

        $resultsu=$statementu->execute();
        $resultsc=$statementc->execute();
        $resultsp=$statementp->execute();
        
         if ($resultsu instanceof ResultInterface && $resultsu->isQueryResult()) 
        {
            $resultSetu = new ResultSet;
            $resultSetu->initialize($resultsu);
        }         
        if ($resultsc instanceof ResultInterface && $resultsc->isQueryResult()) 
        {
            $resultSetc = new ResultSet;
            $resultSetc->initialize($resultsc);
        }          
        if ($resultsp instanceof ResultInterface && $resultsp->isQueryResult()) 
        {
            $resultSetp = new ResultSet;
            $resultSetp->initialize($resultsp);
        } 
        /*************************Preparation des liste****************************************/
        return new ViewModel(array('user'=>$resultSetu,'product'=>$resultSetp,'company'=>$resultSetc,'inv'=>$id));                      
    }  
    public function deleteAction()
    {
        $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }

        if (!isset($_GET['inv'])) 
        {
           $this->redirect()->toRoute('invoice',array('action' => 'index'));          
        }




           $id = (int) $this->params()->fromRoute('id', 0);

           if (!$id) {
            return $this->redirect()->toRoute('invoice',array('action' => 'detaill'));
           }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $del = $request->getPost('del', 'Non');

                if ($del == 'Oui') {
                    $id = (int) $request->getPost('id');
                      $adapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                      $sql1 = "delete from pro_product where id=".$id;
                      $statement1 =$adapter1->query($sql1); 
                      $results1=$statement1->execute();
                      $this->redirect()->toRoute('invoice',array('action' => 'detaill','id'=>$_GET['inv']));
                          
                }else{
                              return $this->redirect()->toRoute('invoice',array('action' => 'detaill','id'=>$_GET['inv'])); 
                }
            }

        return array(
            'id'    => $id,
            'inv'    => $_GET['inv']
        );
    }
}
?>
