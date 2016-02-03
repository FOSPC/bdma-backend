<?php
namespace Admin\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Admin;         
use Admin\Form\AdminForm;  

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    


// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class AdminController extends AbstractActionController
{
    protected $adminTable;

    public function indexAction()
    {

    // instansiation d'une session d'admin (duré = 30 Jrs)
    $session = new Container('admin');
    

        // tester si une session 'email existe '
        if ($session->offsetExists('email')  && $session->offsetGet('user')=='admin') {

          //Prepare statistique information pour la vue Index ->des statistiques
            $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
             $sql1 = "select COUNT(pro_user.id) AS 'a' FROM pro_user"; 
             $sql2 = "select COUNT(pro_company.id) AS 'b' FROM pro_company"; 
             $sql3 = "select COUNT(pro_invoice.id) AS 'c' FROM pro_invoice"; 
             $sql4 = "select COUNT(pro_paid.id)    AS 'd' FROM pro_paid"; 
             $sql5 = "select COUNT(pro_product.id) AS 'e' FROM pro_product"; 
             $sql6 = "select COUNT(pro_product_bdma.id_product_bdma) AS 'f' FROM pro_product_bdma"; 
             $sql7 = "select * FROM product_bdma_price_history"; 


             $statement1 =$adapter->query($sql1); 
             $statement2 =$adapter->query($sql2); 
             $statement3 =$adapter->query($sql3); 
             $statement4 =$adapter->query($sql4); 
             $statement5 =$adapter->query($sql5); 
             $statement6 =$adapter->query($sql6); 
             $statement7 =$adapter->query($sql7); 

             $results1=$statement1->execute();
             $results2=$statement2->execute();
             $results3=$statement3->execute();
             $results4=$statement4->execute();
             $results5=$statement5->execute();
             $results6=$statement6->execute();
             $results7=$statement7->execute();

             $row1 = $results1->current();
             $row2 = $results2->current();
             $row3 = $results3->current();
             $row4 = $results4->current();
             $row5 = $results5->current();
             $row6 = $results6->current();
             // resultset7 vas être déclaré ci dessous pour plus de manipulation

                 //tester si les variables rouX ont bien remplis
                 if (!$row1 || !$row2 || !$row3 || !$row4 || !$row5 || !$row6)
                 {
                    //redirection vers l'index avec un message GET var 
                    $this->redirect()->toRoute('admin',array('action' => 'index'),array('query' => array('status' => 'erreur_fetching_statis_data')));          
                 }
                 else
                 {
                    // 1 preparation des données array list
                     $statisticData=array(
                            'user'=>     $row1['a'],
                            'company'=>  $row2['b'],
                            'invoice'=>  $row3['c'],
                            'paid'=>     $row4['d'],
                            'product'=>$row5['e'],
                            'productav'=>  $row6['f'],

                        );  
                    // 2 preparation des donnees  result sett , retourne un mass d'information
                     if ($results7 instanceof ResultInterface && $results7->isQueryResult()) 
                    {
                        //instanciation  de la class result set pour l'enregistrement des information fournis par la BD
                        $resultSet = new ResultSet;
                        $resultSet->initialize($results7);

                        //  redirection vers la vue index avec les information des statistiques
                        return new ViewModel(array('statistic'=>$statisticData,'history'=> $resultSet)); 
                    }           
                 }             
            }
            else
            {
                //si la session n'existe pas ,, donc la redirection vers login page pour l'authentification avec un petit message 
                $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
                
            }
    }

    public function logoutAction()
    {
        // pour detruire la session admin
        $session = new Container('admin');
        $session->getManager()->getStorage()->clear('admin');
        //redirection vers la page de login avec un petit message
        $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
    }

     public function loginAction()
     {
        /*
         pour le login on prepare la form et en test si il y a une post method 
         si /oui on test les information 
         si /non redirection vers l'index est affichage de la form
        */ 
        $form = new AdminForm();
        $request = $this->getRequest();
        $session = new Container('admin');
        if ($session->offsetExists('email')  && $session->offsetGet('user')=='admin') {
            $this->redirect()->toRoute('admin',array('action' => 'index'));                      
        }
        else
        {

            if ($request->isPost()) {
                    $password = $this->getRequest()->getPost('password');
                    $email = $this->getRequest()->getPost('email');
                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                         $sql = "select email,password from login where email='".$email."' and password='".$password."'"; 

                         $statement =$adapter->query($sql); 

                         $results=$statement->execute();

                         $row = $results->current();

                         if (!$row)
                         {
                            $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'filed_login')));          
                         }
                         else
                         {
                                 $email = $row['email'];
                                 $session->offsetSet('email', $email );
                                 $session->offsetSet('user', 'admin' );
                                 $this->redirect()->toRoute('admin');   
                                 $session = new Container('useradmin');
                                 $session->getManager()->getStorage()->clear('useradmin');       
                         }  
            }
            
        } 
        return array('form' => $form); 
     }
}
?>
