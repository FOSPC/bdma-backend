<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\Users;            
use Users\Form\UsersForm;       

// pour l'adapter et servicelocator 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter; 

// pour les sessions
use Zend\Session\Container;    


// pour  result_set manipulations
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class UsersController extends AbstractActionController
{
    protected $usersTable;





    public function indexAction()
    {        
       $session = new Container('admin');
        if ($session->offsetExists('email')) {
/*----------------------------------------------------*/
    // grab the paginator from the usersTable
     $paginator = $this->getUsersTable()->fetchAll(true);
     // set the current page to what has been passed in query string, or to 1 if none set

  //   (int) $this->params()->fromQuery('page', 1)
    //  $page = (int) $this->params()->fromRoute('page',1);

    $page=(int)1;
    if (isset($_GET['page'])) {
    $page=(int)$_GET['page'];
    }


     $paginator->setCurrentPageNumber($page);
     // set the number of items per page to 10
     $paginator->setItemCountPerPage(10);
     return new ViewModel(array(
         'paginator' => $paginator
     ));
           
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
                    $c1 =       strip_tags($this->getRequest()->getPost('c1'));
                    $c2 =       strip_tags($this->getRequest()->getPost('c2'));
                    $c3 =       strip_tags($this->getRequest()->getPost('c3'));
                    if ($c1!=1) {
                        $c1=0;
                    }

                    if ($c2!=1) {
                        $c2=0;
                    }

                    if ($c3!=1) {
                        $c3=0;
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
                    $comp =     strip_tags($this->getRequest()->getPost('comp'));
                    $id =       strip_tags($this->getRequest()->getPost('id'));


                    if ($c2==1) {
                        $preadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                        $presql="call fix_admin(".$comp.")";
                        $statement =$preadapter->query($presql); 
                        $results=$statement->execute();
                    }


                    $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
$sql = "insert into pro_user(id, active, public,admin, company, gender, name, firstname, email, lang, phone, gsm, passwd) 
values ('0',
 '".$c1."',
 '".$c3."',
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
                $this->redirect()->toRoute('users',array('action' => 'index'),array('query' => array('status' => 'y_add')));          
        }


        $adapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');  
        $sql="select id,enterprise from pro_company";
        $statement =$adapter1->query($sql); 
        $results=$statement->execute(); 
            if ($results instanceof ResultInterface && $results->isQueryResult()) 
                {
                    //instanciation  de la class result set pour l'enregistrement des information fournis par la BD
                    $resultSet = new ResultSet;
                    $resultSet->initialize($results);

                    //  redirection vers la vue index avec les information des statistiques
                    return new ViewModel(array('datac'=> $resultSet)); 
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
            return $this->redirect()->toRoute('users', array(
                'action' => 'add'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            /*---------------------------lles operation de la modification ----------------------------------*/
                    $c1 =       strip_tags($this->getRequest()->getPost('c1'));
                    $c2 =       strip_tags($this->getRequest()->getPost('c2'));
                    $c3 =       strip_tags($this->getRequest()->getPost('c3'));
                    if ($c1!=1) {
                        $c1=0;
                    }

                    if ($c2!=1) {
                        $c2=0;
                    }

                    if ($c3!=1) {
                        $c3=0;
                    }





                    $lang =     strip_tags($this->getRequest()->getPost('lang'));
                    $gend =     strip_tags($this->getRequest()->getPost('gend'));
                    $date =     strip_tags($this->getRequest()->getPost('date'));
                    $id =       strip_tags($this->getRequest()->getPost('id'));
                   
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
                    $comp =     strip_tags($this->getRequest()->getPost('comp'));


                    if ($c2==1) {
                        $preadapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                        $presql="call fix_admin(".$comp.")";
                        $statement =$preadapter->query($presql); 
                        $results=$statement->execute();
                    }


                        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

                        $sql="update pro_user SET `active`='".$c1
                        ."', `public`='".$c3
                        ."', `admin`='".$c2
                        ."', `company`='".$comp
                        ."', `added`='".$date
                        ."', `gender`='".$gend
                        ."', `name`='".$name
                        ."', `firstname`='".$lname
                        ."', `email`='".$email
                        ."', `lang`='".$lang
                        ."', `phone`='".$phone
                        ."', `gsm`='".$gsm
                        ."', `passwd`='".$password
                        ."' WHERE `id`='".$id."';
                        ";

                        $statement =$adapter->query($sql); 
                        $results=$statement->execute();
                     $this->redirect()->toRoute('users',array('action' => 'index'),array('query' => array('status' => 'yupdate')));            
            /*-----------------------------------------------------------------------------------------------*/
        }
        /*------------------------------preparation des donnes-----------------------------*/
                $adapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');  
                $sql="select id,enterprise from pro_company";
                $statement =$adapter1->query($sql); 
                $results=$statement->execute(); 

                if ($results instanceof ResultInterface && $results->isQueryResult()) 
                    {
                        //instanciation  de la class result set pour l'enregistrement des information fournis par la BD
                        $resultSet = new ResultSet;
                        $resultSet->initialize($results);

                        //  redirection vers la vue index avec les information des statistiques
                    }       



                $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $sql1 = "select * from pro_user where id='".$id."'";
                $statement1 =$adapter->query($sql1); 
                $results1=$statement1->execute();
                $row1 = $results1->current();
                 //tester si les variables rouX ont bien remplis
                 if (!$row1)
                 {
                    //redirection vers l'index avec un message GET var 
                    $this->redirect()->toRoute('users',array('action' => 'index'),array('query' => array('status' => 'nouser')));          
                 }
        /*---------------------------------------------------------------------------------*/
        return new ViewModel(array('user'=>$row1,'datac'=>$resultSet)); 
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
            return $this->redirect()->toRoute('users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->getUsersTable()->deleteUsers($id);
            }

           
            return $this->redirect()->toRoute('users');
        }

        return array(
            'id'    => $id,
            'users' => $this->getUsersTable()->getUsers($id)
        );
    }
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Users\Model\UsersTable');
        }
        return $this->usersTable;
    }



}
?>
