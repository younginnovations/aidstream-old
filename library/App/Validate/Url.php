<?php
class App_Validate_Url extends Zend_Validate_Abstract
{
    const MSG_URL = 'msgUrl';

    protected $_messageTemplates = array(
        self::MSG_URL => "Invalid URL , Please enter url in format http://mysite.com",
    );

    public function isValid($value)
    {
        if (preg_match('"(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?"', $value))  {
            return true;
        } else {
            $this->_error(self::MSG_URL);
            return false;

        }

    }
}

?>