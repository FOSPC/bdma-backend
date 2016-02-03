<?php

 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class AdminTable
 {
     protected $tableGateway;

     //préparation du table gateway pour les requête SQL spécifique

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

 }

?>