<?php
namespace Country\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Country\Model\Country;            
use Country\Form\CountryForm;       
use Zend\Session\Container;


class CountryController extends AbstractActionController
{
    protected $countryTable;

    public function indexAction()
    {        
       $session = new Container('admin');
        if ($session->offsetExists('email')) {
            return new ViewModel(array('countrys' => $this->getCountryTable()->fetchAll(),));           
        }
        else
        {
            $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }
    }

    public function addAction()
    {
        $session = new Container('admin');
        $form = new CountryForm();
        $form->get('submit')->setValue('Add');
      if ($session->offsetExists('email')) 
        {
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $country = new Country();
                    $form->setInputFilter($country->getInputFilter());
                    $form->setData($request->getPost());

                    if ($form->isValid()) {
                        $country->exchangeArray($form->getData());
                        $this->getCountryTable()->saveCountry($country);

                        return $this->redirect()->toRoute('country');
                    }
                }  
        }
        else
        {
            $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }
       return array('form' => $form);
    }

 
    public function editAction()
    {


       $session = new Container('admin');

        if (!$session->offsetExists('email')) 
        {
           $this->redirect()->toRoute('admin',array('action' => 'login'),array('query' => array('status' => 'u_login')));          
        }

         $form  = new CountryForm();        
         $id = (int) $this->params()->fromRoute('id', 0);


        if (!$id) {
            return $this->redirect()->toRoute('country', array(
                'action' => 'add'
            ));
        }

        try {
            $country = $this->getCountryTable()->getCountry($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('country', array(
                'action' => 'index'
            ));
        }

        $form->bind($country);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($country->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCountryTable()->saveCountry($country);

               
                return $this->redirect()->toRoute('country');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
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
            return $this->redirect()->toRoute('country');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->getCountryTable()->deleteCountry($id);
            }

           
            return $this->redirect()->toRoute('country');
        }

        return array(
            'id'    => $id,
            'country' => $this->getCountryTable()->getCountry($id)
        );
    }
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Country\Model\CountryTable');
        }
        return $this->countryTable;
    }

}
?>
