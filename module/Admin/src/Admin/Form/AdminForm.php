<?php

namespace Admin\Form;

use Zend\Form\Form;

class AdminForm extends Form
{
    public function __construct($name = null)
    {
        
        parent::__construct('admin');

// creation de input email passwor et submit button
        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'attributes' => array(
                'class' => 'validate',
                'id' => 'email',
                ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'class' => 'validate',
                'id' => 'password',
                ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'submitbutton',
                'class' => 'waves-effect waves-light btn',
            ),
        ));
    }
}



?>
