<?php

class Age_validator extends Zend_Validate_Abstract
{
    const NOT_VALID = 'notValid';

    protected $_messageTemplates = array(
        self::NOT_VALID => 'Vek zadávajte len ako číslo',
    );
    public function isValid($value)
    {
        $this->_setValue($value);
        $pattern = '/^[0-9]*$/';
        if (!preg_match($pattern, $value)) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}
