<?php

namespace Company\Form;

use Zend\Form\Form;

class CompanyForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('company');
    }
}



?>
