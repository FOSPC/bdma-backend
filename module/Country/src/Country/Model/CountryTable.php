<?php

 namespace Country\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CountryTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getCountry($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveCountry(Country $country)
     {
         $data = array(
             'name' => $country->name,
         );

         $id = (int) $country->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getCountry($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Country id does not exist');
             }
         }
     }

     public function deleteCountry($id)
     {
        try {
            $this->tableGateway->delete(array('id' => (int) $id));
        } catch (Exception $e) {
           echo "we cant delete this Country :( -->More Info :  ". $e->getMessage();
            
        }


         
     }
 }

?>