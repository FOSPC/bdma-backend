<?php

 namespace Company\Model;

 use Zend\Db\TableGateway\TableGateway;

 class Company
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

    

     public function deleteCompany($id)
     {
        try {
            $this->tableGateway->delete(array('id' => (int) $id));
        } catch (Exception $e) {
           echo "we cant delete this Country :( -->More Info :  ". $e->getMessage();
            
        }


         
     }
 }

?>