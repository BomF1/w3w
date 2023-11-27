<?php

class Phone_Number_Validator extends Zend_Validate_Abstract
{
    const NOT_VALID = 'notValid';

    protected $_messageTemplates = array(
        self::NOT_VALID => 'Zadávajte telefonné číslo v správnom tvare +420XXXXXXXXX',
    );
    public function isValid($value)
    {
        $this->_setValue($value);
        $pattern = '/^\+420[0-9]{9}$/';
        if (!preg_match($pattern, $value)) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}
