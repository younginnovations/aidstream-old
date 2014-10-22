<?php
class App_Validate_Url extends Zend_Validate_Abstract
{
    const MSG_URL = 'msgUrl';

    protected $_messageTemplates = array(
        self::MSG_URL => "Invalid URL , Please enter a single url in valid url format e.g http(s)://mysite.com",
    );

    public function isValid($value)
    {
        $valid = Zend_Uri::check($value);
        if ($valid) {
            return true;
        } else {
            $this->_error(self::MSG_URL);
            return false;
        }
    }
}

?>