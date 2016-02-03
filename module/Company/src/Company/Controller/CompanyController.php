<?php
namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Model\Company;            
use Company\Form\CompanyForm;       

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    


// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class CompanyController extends AbstractActionController
{
    protected $companyTable;

    public function indexAction()
    {        
        $session = new Container('admin');
        if ($session->offsetExists('email')) {
          //Prepare statistique information pour la vue Index ->des statistiques
          $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
          $sql1 = "select *,(select count(id) from pro_user pu where pu.company=pc.id) as 'nbr' from pro_company pc limit 50;";
          $statement1 =$adapter->query($sql1); 
          $results1=$statement1->execute();
          $row1 = $results1->current();

             //tester si les variables rouX ont bien remplis
             if (!$row1)
             {
                //redirection vers l'index avec un message GET var 
                $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'erreur_fetching_view_data')));          
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

     public function usersAction()
    {        
         $session = new Container('admin');

        if (!$session->offsetExists('email') || !isset($_GET['idcmp'])) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }


           $idcmp = $_GET['idcmp'];
           if (!$idcmp) {
            return $this->redirect()->toRoute('company');
           }

                      $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                      $sql1 = "select * from pro_user where company=".$_GET['idcmp'];
                      $statement1 =$adapter->query($sql1); 
                      $results1=$statement1->execute();
             

                     //tester si les variables rouX ont bien remplis
             if ($results1->count()<=0)
             {
                //redirection vers l'index avec un message GET var 
                $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'nouser')));          
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

     public function detailAction()
    {        
        if (isset($_GET['idcomp']))
        {
            $session = new Container('admin');
                if ($session->offsetExists('email')) {
                          //Prepare statistique information pour la vue Index ->des statistiques
                          $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                          $sql1 = "select *,(select count(id) from pro_user pu where pu.company=pc.id) as 'nbr' from pro_company pc where pc.id=1;".$_GET['idcomp'];
                          $statement1 =$adapter->query($sql1); 
                          $results1=$statement1->execute();
                          $row1 = $results1->current();

                     //tester si les variables rouX ont bien remplis
                     if (!$row1)
                     {
                        //redirection vers l'index avec un message GET var 
                        $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'erreur_fetching_view_data')));          
                     }
                     else
                     {
                            //  redirection vers la vue index avec les information des statistiques
                            return new ViewModel(array('data'=> $row1));          
                     }            
                }
                else
                {
                    $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
                }         
                            
        }
        else
        {
                    $this->redirect()->toRoute('company');          
        }                
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
            return $this->redirect()->toRoute('company');
        }

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            /*---------------------------lles operation de la modification ----------------------------------*/
                    $public =     strip_tags($this->getRequest()->getPost('public'));
                    if ($public==1) {
                        $public='1';
                    }
                    else{
                        $public='0';    
                    }
                    $added =    strip_tags($this->getRequest()->getPost('added'));
                    $enterprise =    strip_tags($this->getRequest()->getPost('enterprise'));
                    $advertiser =      strip_tags($this->getRequest()->getPost('advertiser'));
                    $provider =    strip_tags($this->getRequest()->getPost('provider'));
                    $street = strip_tags($this->getRequest()->getPost('street'));
                    $number =     strip_tags($this->getRequest()->getPost('number'));
                    $bus =     strip_tags($this->getRequest()->getPost('bus'));
                    $city =    strip_tags($this->getRequest()->getPost('city'));
                    $postal =    strip_tags($this->getRequest()->getPost('postal'));
                    $country =      strip_tags($this->getRequest()->getPost('country'));
                    $btw =    strip_tags($this->getRequest()->getPost('btw'));
                    $invoice = strip_tags($this->getRequest()->getPost('invoice'));
                    $serviceprovider =     strip_tags($this->getRequest()->getPost('serviceprovider'));



                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

$sql="
    UPDATE pro_company SET `public`='".$public."',`added`='".$added."', `enterprise`='".$enterprise."', `advertiser`='".$advertiser."', `provider`='".$provider."', `street`='".$street."', `number`='".$number."', `bus`='".$bus."', `city`='".$city."', `postal`='".$postal."', `country`='".$country."', `btw`='".$btw."', `invoice`='".$invoice."', `serviceprovider`='".$serviceprovider."' WHERE `id`='".$id."'
";
                    $statement =$adapter->query($sql); 

                    $results=$statement->execute();
                    $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'yupdate')));      

           
            /*-----------------------------------------------------------------------------------------------*/
        }
        /*------------------------------preparation des donnes-----------------------------*/
                $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $sql1 = "select * from pro_company where id='".$id."'";
                $statement1 =$adapter->query($sql1); 
                $results1=$statement1->execute();
                $row1 = $results1->current();
                 //tester si les variables rouX ont bien remplis
                 if (!$row1)
                 {
                    //redirection vers l'index avec un message GET var 
                   $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'nocomp')));          
                 }
        /*---------------------------------------------------------------------------------*/
        return new ViewModel(array('data'=>$row1));                  
    }  
 
     public function deleteAction()
    {
        $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }


           $id = (int) $this->params()->fromRoute('id', 0);
           if (!$id) {
            return $this->redirect()->toRoute('company');
           }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $del = $request->getPost('del', 'Non');

                if ($del == 'Oui') {
                    $id = (int) $request->getPost('id');

                    /*_____________________recherche________________________*/
                     if (!isset($_GET['inv']))
                     {
                        $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'needIdInv')));          
                     }

                      $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                      $sql1 = "select count(pu.id) from pro_invoice pu,pro_company pc where pu.id=".$id." and pc.id=".$_GET['inv'];
                      $statement1 =$adapter->query($sql1); 
                      $results1=$statement1->execute();
                      $row1 = $results1->current();



                    if ($row1['count(pu.id)']!=0) {
                         $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' =>'youcant'))); 
                     }
                    else{
                      $adapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                      $sql1 = "delete from pro_company where id=".$id;
                      $statement1 =$adapter1->query($sql1); 
                      $results1=$statement1->execute();
                         $this->redirect()->toRoute('company',array('action' => 'index'),array('query' => array('status' => 'deleted'))); 
                    }        
                }
            }

        return array(
            'id'    => $id,
            'inv' => $_GET['inv']
        );
    }
}
?>
