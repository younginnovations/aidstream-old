<?php
class Model_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
    
    public function getAwaitingUser()
    {
        $select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->joinLeft(array('ac'=>'account'),'user.account_id = ac.id')
        ->where('role_id = ?', 1)
        ->where('status = ?',0);
        $row = $this->fetchAll($select);
        return $row;
    }
    
    public function updateStatus($status,$id) {
        foreach($status as $key => $val){
            $data[$key] = $val;
        }
        return parent::update($data,array('user_id = ?' => $id));
    }
    
    public function updateStatusByAccount($accountId , $status)
    {
        return $this->update(array('status' => $status) , array('account_id = ?' => $accountId));
    }
    
    public function getUserByAccountId($account_id,$filter = null)
    {
        $select = $this->select()
            ->where('account_id = ?',$account_id);
        if($filter){
            foreach($filter as $key=>$value){
                $select->where("$key = $value");
            }
        }
        return $this->fetchRow($select)->toArray();
        
    }
    
    public function getAllUsersByAccountId($accountId)
    {
        $select = $this->select()
            ->where('account_id = ?',$accountId);
        return $this->fetchAll($select)->toArray();
    }
    public function getUserCountByAccountId($account_id)
    {
        $select = $this->select()
            ->from($this->_name,array('COUNT(user_id) as users_count'))
            ->where('account_id = ?',$account_id);
        return $this->fetchAll($select)->toArray();
    }
    
    public function getHelpState($stateId) 
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $userId = $identity->user_id;

        $select = $this->select()
                    ->from($this->_name, array('help_state'))
                    ->where('user_id = ?', $userId);
        $result = $this->fetchRow($select);
        if ($result->help_state) {
            $data = unserialize($result->help_state);
            $stateName = Iati_WEP_ActivityState::getStatus($stateId);
            return $data[$stateName];
        }
        return false;
    }

    /**
     * This function is used to check if user has minimum number of files publised for stopping displaying various tooltips.
     * The minimum number is saved in application.ini
     */
    public static function checkHasPublished($accountId = null)
    {
	if(!$accountId){
	    $identity = Zend_Auth::getInstance()->getIdentity();
	    $accountId = $identity->account_id;
	}
	$minPublishedForHelp = Zend_Registry::get('config')->min_published_for_help;

	$model = new Model_ActivityCollection();
        $publishedCount = $model->getActivitiesCountByStatusAndAccount( Iati_WEP_ActivityState::STATUS_PUBLISHED , $accountId);
        if($publishedCount >= $minPublishedForHelp) {
            return true;
        } else {
            return false;
        }
    }

    public function updateHelpState($stateId)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $userId = $identity->user_id;

        $data = array(
                    'Draft' => 1,
                    'Completed' => 0,
                    'Verified' => 0,
                    'Published' => 0
                );

        $select = $this->select()
                    ->from($this->_name, array('help_state'))
                    ->where('user_id = ?', $userId);
        $result = $this->fetchRow($select);
        if ($result->help_state) {
            $data = unserialize($result->help_state);
        }

        $stateName = Iati_WEP_ActivityState::getStatus($stateId);
        $data[$stateName] = 1;
        $data = serialize($data);

        return $this->update(array('help_state' => $data) , array('user_id = ?' => $userId));
    }

}