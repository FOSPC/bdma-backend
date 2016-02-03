<?php

 namespace Company\Model;
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

class Company implements InputFilterAwareInterface
 {

        public $id;
        public $public; 
        public $added ;
        public $enterprise ;
        public $advertiser ;
        public $provider;
        public $street;
        public $number; 
        public $bus ;
        public $city;
        public $postal ;
        public $country;
        public $btw;
        public $invoice;
        public $serviceprovider;
        protected $inputFilter;


     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->public = (!empty($data['public'])) ? $data['public'] : null;
         $this->added = (!empty($data['added'])) ? $data['added'] : null;
         $this->entreprise     = (!empty($data['entreprise'])) ? $data['entreprise'] : null;
         $this->advertiser = (!empty($data['advertiser'])) ? $data['advertiser'] : null;
         $this->provider = (!empty($data['provider'])) ? $data['provider'] : null;
         $this->street     = (!empty($data['street'])) ? $data['street'] : null;
         $this->number = (!empty($data['number'])) ? $data['number'] : null;
         $this->bus = (!empty($data['bus'])) ? $data['bus'] : null;
         $this->city     = (!empty($data['city'])) ? $data['city'] : null;
         $this->postal = (!empty($data['postal'])) ? $data['postal'] : null;
         $this->country = (!empty($data['country'])) ? $data['country'] : null;
         $this->btw     = (!empty($data['btw'])) ? $data['btw'] : null;
         $this->invoice = (!empty($data['invoice'])) ? $data['invoice'] : null;
         $this->serviceprovider = (!empty($data['serviceprovider'])) ? $data['serviceprovider'] : null;
     }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
 }
?>
