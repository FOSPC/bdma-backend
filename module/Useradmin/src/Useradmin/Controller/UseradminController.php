<?php
namespace Useradmin\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Useradmin\Model\Useradmin;         
use Useradmin\Form\UseradminForm;  

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    


// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class UseradminController extends AbstractActionController
{
    protected $useradminTable;

    public function indexAction()
    {  
        // instansiation d'une session d'admin (duré = 30 Jrs)
        $session = new Container('useradmin');
    

    // tester si une session 'email existe '
    if ($session->offsetExists('uid') && $session->offsetExists('ucomp')  &&  $session->offsetGet('user')=='user') {

      //Prepare statistique information pour la vue Index ->des statistiques
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $cmpny=$session->offsetGet('ucomp');

$sql1 = "select  `id`, `gender`,`company`, `name`, `firstname`, `email`, `lang`, `phone`, `gsm` from pro_user where company='".$cmpny."' and active=1";


         $statement1 =$adapter->query($sql1); 


         $results1=$statement1->execute();


         $row1 = $results1->current();

             //tester si les variables rouX ont bien remplis
             if (!$row1)
             {
                //redirection vers l'index avec un message GET var 
                $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'erreur_fetching_view_data')));          
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
            //si la session n'existe pas ,, donc la redirection vers login page pour l'authentification avec un petit message 
            $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'u_login')));          
            
        }
    }

    public function adduserAction()
    {

        $request = $this->getRequest();
        $session = new Container('useradmin');

        if (!$session->offsetExists('uid') && !$session->offsetExists('ucomp')  &&  !$session->offsetGet('user')=='user') {
        $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'u_login')));                                 
        }
     
        if ($request->isPost()) 
        {
                    //strip_tags pour la filtration des input

                    $c1 =       strip_tags($this->getRequest()->getPost('c1'));
                    $c2 =       strip_tags($this->getRequest()->getPost('c2'));
                    if ($c1!=1) {
                        $c1=0;
                    }

                    if ($c2!=1) {
                        $c2=0;
                    }





                    $lang =     strip_tags($this->getRequest()->getPost('lang'));
                    $gend =     strip_tags($this->getRequest()->getPost('gend'));
                   
                    if ($lang==1) {
                        $lang='french';
                    }
                    elseif ($lang==2) {
                        $lang='dutch';
                    }else{
                        $lang='';
                    }

                    if ($gend==1) {
                        $gend='M';
                    }
                    elseif ($gend==2) {
                        $gend='F';
                    }else{
                        $gend='';
                    }

                    $name =     strip_tags($this->getRequest()->getPost('name'));
                    $lname =    strip_tags($this->getRequest()->getPost('lname'));
                    $phone =    strip_tags($this->getRequest()->getPost('phone'));
                    $gsm =      strip_tags($this->getRequest()->getPost('gsm'));
                    $email =    strip_tags($this->getRequest()->getPost('email'));
                    $password = strip_tags($this->getRequest()->getPost('password'));
                    $comp = strip_tags($this->getRequest()->getPost('comp'));
                    $id = strip_tags($this->getRequest()->getPost('id'));


                    if ($c2==1) {
                        $preadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                        $presql="call fix_admin(".$comp.")";
                        $statement =$preadapter->query($presql); 
                        $results=$statement->execute();
                    }


                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
$sql = "insert into pro_user(id, active, admin, company, gender, name, firstname, email, lang, phone, gsm, passwd) 
values ('0',
 '".$c1."',
  '".$c2."',
   '".$comp."',
    '".$gend."',
     '".$name."',
      '".$lname."',
       '".$email."',
        '".$lang."',
         '".$phone."',
          '".$gsm."',
           '".$password."')"; 

             $statement =$adapter->query($sql); 

             $results=$statement->execute();
                $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'y_add')));          
        }
            
        return new ViewModel(); 
    }

    public function logoutuserAction()
    {
        $session = new Container('useradmin');
        $session->getManager()->getStorage()->clear('useradmin');


        //redirection vers la page de login avec un petit message
        $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'u_login')));  
    }

    public function loginuserAction()
    {
     /*
        pour le login on prepare la form et en test si il y a une post method 
        si /oui on test les information 
        si /non redirection vers l'index est affichage de la form
    */ 


                $request = $this->getRequest();
                $session = new Container('useradmin');
                if ($session->offsetExists('uid') && $session->offsetExists('ucomp')  &&  $session->offsetGet('user')=='user') {
                    $this->redirect()->toRoute('useradmin',array('action' => 'index'));                      
                }
         

                    if ($request->isPost())
                    {

                       $password = $this->getRequest()->getPost('password');
                       $email = $this->getRequest()->getPost('email');



                       $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');


                       $sql = "select pu.id,pu.name,pu.company,pu.active,pu.admin,pu.email,pu.passwd FROM pro_user pu where pu.email='".
                              $email."' and pu.passwd='".$password."' and pu.admin=1 and pu.active=1"; 

                         $statement =$adapter->query($sql); 

                         $results=$statement->execute();

                         $row = $results->current();

                           if (!$row)
                           {
                              $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'filed_login')));          
                           }
                           else
                           {
                              if ($row['admin']!=1 && $row['active']!=1) {
                               $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'no_permission')));                                        
                                }
                               // collection des information
                                 $uid = $row['id'];
                                 $uprenom = $row['name'];
                                 $uemail = $row['email'];
                                 $ucomp = $row['company'];


                                 $session->offsetSet('user','user');
                                 $session->offsetSet('uid',$uid);
                                 $session->offsetSet('uprenom',$uprenom);
                                 $session->offsetSet('uemail',$uemail);
                                 $session->offsetSet('ucomp',$ucomp);
                                $session = new Container('admin');
                                $session->getManager()->getStorage()->clear('admin');
                                $this->redirect()->toRoute('useradmin',array('action' => 'index'));
                           }  
                    }   
              
       //return new ViewModel();
    }



    public function disableuserAction()
    {
        $session = new Container('useradmin');
        if (!$session->offsetExists('uid') && !$session->offsetExists('ucomp')  &&  !$session->offsetGet('user')=='user') {
        $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'u_login')));                                 
        }

        if (isset($_GET['iduser'])) 
        {
                $id=$_GET['iduser'];
                $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $sql="update pro_user set active=0 where id='".$id."'";
                $statement =$adapter->query($sql); 
                $results=$statement->execute(); 
          $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'y')));           
        }
        else
        {
          $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'n')));          
        }
    }
    public function dmnAction()
    {
        $session = new Container('useradmin');
        if (!$session->offsetExists('uid') && !$session->offsetExists('ucomp')  &&  !$session->offsetGet('user')=='user') {
        $this->redirect()->toRoute('useradmin',array('action' => 'loginuser'),array('query' => array('status' => 'u_login')));                                 
        }
        if (isset($_GET['iduser'])) 
        {
                $id=$_GET['iduser'];
                $comp=$_GET['comp'];
                


                $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $sql="update pro_user set admin=1 where id='".$id."'";
                $sql2="call fix_admin(".$comp.")";
                
                $statement2 =$adapter->query($sql2);
                $statement =$adapter->query($sql); 
                $results2=$statement2->execute(); 
                $results=$statement->execute(); 
          $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'ydmn')));           
        }
        else
        {
          $this->redirect()->toRoute('useradmin',array('action' => 'index'),array('query' => array('status' => 'n')));          
        }
    }
    
}
?>
