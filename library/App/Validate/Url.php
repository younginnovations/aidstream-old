<?php
class App_Validate_Url extends Zend_Validate_Abstract
{
    const MSG_URL = 'msgUrl';

    protected $_messageTemplates = array(
        self::MSG_URL => "Please enter a single url in valid url format with valid characters (spaces are not allowed) e.g http(s)://mysite.com/filename.pdf",
    );

    public function isValid($value)
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
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