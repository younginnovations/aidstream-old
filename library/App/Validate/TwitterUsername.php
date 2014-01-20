<?php  
class App_Validate_TwitterUsername extends Zend_Validate_Abstract 
{
	const ERROR = 'errorProcessingRequest';
	const INVALID = 'invalidTwitterScreenName';

	protected $_messageTemplates = array(
		self::INVALID => 'Invalid twitter screen name: %value%',
		self::ERROR => 'Error validating twitter name. Please try again.'
	);	

	public function isValid($value) {
		$username = (string) $value;
		$this->_setValue($username);

		$model = new Model_Twitter();
		$result = $model->checkUsername(preg_replace("/@/", "", $username, 1));
		if ($result == 'INVALID') {
			$this->_error(self::INVALID);
			return false;
		} elseif ($result == 'ERROR') {
			$this->_error(self::ERROR);
			return false;
		} else {
			return true;
		}
	}
}