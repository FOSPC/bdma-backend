<?php

 namespace Users\Model;



 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Paginator\Adapter\DbSelect;
 use Zend\Db\Sql\Select;
 use Zend\Paginator\Adapter\DbTableGateway;
 use Zend\Paginator\Paginator;
 use Zend\Db\Sql\Sql;

 class UsersTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }
      public function fetchAll($paginated=false)
     {

         if ($paginated) {

             $select = new Select();
            $select->from('pro_user');



             // create a new result set based on the user entity
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Users());
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select,
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getUsers($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveUsers(Users $users)
     {
         $data = array(
             'name' => $users->name,
         );

         $id = (int) $users->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getUsers($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('User id does not exist');
             }
         }
     }

     public function deleteUsers($id)
     {
        try {
            $this->tableGateway->delete(array('id' => (int) $id));
        } catch (Exception $e) {
           echo "we cant delete this User :( -->More Info :  ". $e->getMessage();
            
        }


         
     }
 }

?>