<?
class Iati_WEP_ActivityState
{
    protected static $_instance;
    
    const STATUS_EDITING = 1;
    const STATUS_TO_BE_CHECKED = 2;
    const STATUS_CHECKED = 3;
    const STATUS_PUBLISHED = 4;
    
    protected $STATUS_LIST = array(
                            self::STATUS_EDITING        =>'Editing',
                            self::STATUS_TO_BE_CHECKED  =>'To be checked',
                            self::STATUS_CHECKED        =>'Checked',
                            self::STATUS_PUBLISHED      =>'Published'
                            );
    protected $TRANSITIONS = array(
                            self::STATUS_EDITING           => array(self::STATUS_TO_BE_CHECKED),
                            self::STATUS_TO_BE_CHECKED     => array(self::STATUS_EDITING, self::STATUS_CHECKED),
                            self::STATUS_CHECKED           => array(self::STATUS_EDITING, self::STATUS_PUBLISHED),
                            self::STATUS_PUBLISHED         => array(self::STATUS_EDITING)
                            );
    
    protected $PERMISSIONS  = array(
                            self::STATUS_EDITING        =>array(Iati_WEP_PermissionConts::ADD_ACTIVITY,Iati_WEP_PermissionConts::ADD_ACTIVITY_ELEMENTS,Iati_WEP_PermissionConts::EDIT_ACTIVITY,Iati_WEP_PermissionConts::EDIT_ACTIVITY_ELEMENTS),
                            self::STATUS_TO_BE_CHECKED  =>array(Iati_WEP_PermissionConts::ADD_ACTIVITY,Iati_WEP_PermissionConts::ADD_ACTIVITY_ELEMENTS,Iati_WEP_PermissionConts::EDIT_ACTIVITY,Iati_WEP_PermissionConts::EDIT_ACTIVITY_ELEMENTS),
                            self::STATUS_CHECKED        =>array(Iati_WEP_PermissionConts::PUBLISH),
                            self::STATUS_PUBLISHED      =>array(Iati_WEP_PermissionConts::PUBLISH)
                            );
    
    public static function getInstance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new Iati_WEP_ActivityState();
        }
        return self::$_instance;
    }
    
    protected function _getStatus($status_key = null)
    {
        if($status_key){
            return $this->STATUS_LIST[$status_key];
        } else {
            return $this->STATUS_LIST;
        }
    }
    
    protected function _getTransitions($status = null)
    {
        if($status){
            return $this->TRANSITIONS[$status];
        } else {
            return $this->TRANSITIONS;
        }
    }
    
    protected function _isValidTransition($initial_state,$final_state)
    {
        if(in_array($final_state,$this->TRANSITIONS[$initial_state])){
            return true;
        } else {
            return false;
        }
    }
    
    protected function _hasPermissionForState($state)
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        if($identity->role == 'user')
        {
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $required_permissions = $this->PERMISSIONS[$state];
            foreach($required_permissions as $permission)
            {
                if(!$userPermission->hasPermission($permission))
                {
                    return false;
                }
            }
        }
        return true;
    }
    
    public static function getStatus($status_key = null)
    {
        return self::getInstance()->_getStatus($status_key);
    }
    
    public static function getTransitions($status = null)
    {
        return self::getInstance()->_getTransitions($status);
    }
    
    public static function isValidTransition($initial_state,$final_state)
    {
        return self::getInstance()->_isValidTransition($initial_state,$final_state);
    }
    
    public static function hasPermissionForState($state)
    {
        return self::getInstance()->_hasPermissionForState($state);
    }
}