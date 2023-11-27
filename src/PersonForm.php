<?php
require_once 'Phone_Number_Validator.php';
require_once 'Age_validator.php';
class PersonForm extends Zend_Form
{
    public function init()
    {
        $this->setView(new Zend_View());
        $this->addElement(new Zend_Form_Element_Text(array(
            'name' => 'age',
            'label' => 'Věk',
            'validators' => array(
                new Age_validator(),
            )
        )));
        $this->addElement(new Zend_Form_Element_Text(array(
            'name' => 'email',
            'label' => 'Email',
            'required' => true,
            'validators' => array(
                new Zend_Validate_EmailAddress(),
            ),
        )));
        $this->addElement(new Zend_Form_Element_Text(array(
            'name' => 'phone',
            'label' => 'Telefonní číslo',
            'required' => true,
            'validators' => array(
                new Phone_Number_Validator(),
            ),
        )));
        $this->addElement(new Zend_Form_Element_Submit(array(
            'name' => 'submit',
            'ignore' => true,
        )));
        $this->setElementDecorators(array(
            'ViewHelper',
            'HtmlTag',
            'Label'
        ));
    }
}
