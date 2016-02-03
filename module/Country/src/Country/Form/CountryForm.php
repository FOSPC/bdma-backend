<?php

namespace Country\Form;

use Zend\Form\Form;

class CountryForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('country');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));


        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'validate',
                'id' => 'nom',
                ),


        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Valider',
                'id' => 'submitbutton',
                'class' => 'waves-effect waves-light btn',
            ),
        ));
    }
}



?>
