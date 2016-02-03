<?php
 namespace Users\Model;
 
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

class Users implements InputFilterAwareInterface
 {
     public $id;
     public $active;
     public $public;
     public $company;
     public $gender;
     public $name;
     public $firstname;
     public $lang;
     public $phone;
     public $gsm;
     protected $inputFilter;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->active = (!empty($data['active'])) ? $data['active'] : 0;
         $this->admin = (!empty($data['admin'])) ? $data['admin'] : 0;
         $this->company = (!empty($data['company'])) ? $data['company'] : null;
         $this->public = (!empty($data['public'])) ? $data['public'] : 0;
         $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
         $this->name = (!empty($data['name'])) ? $data['name'] : null;
         $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
         $this->lang = (!empty($data['lang'])) ? $data['lang'] : null;
         $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
         $this->gsm = (!empty($data['gsm'])) ? $data['gsm'] : null;
     }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        ),
                    ),
                ),
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
 }
?>
