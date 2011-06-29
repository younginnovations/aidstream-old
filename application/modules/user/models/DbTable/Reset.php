<?php
class User_Model_DbTable_Reset extends Zend_Db_Table_Abstract
{
	protected $_name = 'reset';
	
	public function uniqueValue($email, $resetValue)
	{
		$select = $this->select()->where('email = ?', $email)->where('value = ? ', $resetValue)->where('reset_flag = ? ', '1');
		$row = $this->fetchRow($select);
		if($row) {
			return FALSE;
		}else {
			return TRUE;
		}
		
	}

        public function getResetId($email, $resetValue)
	{
		$select = $this->select()->where('email = ?', $email)->where('value = ? ', $resetValue)->where('reset_flag = ? ', '1');
		$row = $this->fetchRow($select);
		
                $resetId = $row['reset_id'];
                return $resetId;

	}
	
}