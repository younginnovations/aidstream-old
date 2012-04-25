<?
class Iati_WEP_ActivityState
{
    protected static $_instance;

    const STATUS_EDITING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_VERIFIED = 3;
    const STATUS_PUBLISHED = 4;
    const ACTION_COMPLETE = 'Complete';
    const ACTION_VERIFIED = 'Verify';
    const ACTION_PUBLISH = 'Publish';

    protected $ACTION = array(
                            self::STATUS_COMPLETED    => 'Complete',
                            self::STATUS_VERIFIED     => 'Verify',
                            self::STATUS_PUBLISHED    => 'Publish'
                            );

    protected $STATUS_LIST = array(
                            self::STATUS_EDITING        =>'Edit',
                            self::STATUS_COMPLETED  =>'Completed',
                            self::STATUS_VERIFIED        =>'Verified',
                            self::STATUS_PUBLISHED      =>'Published'
                            );
    protected $TRANSITIONS = array(
                            self::STATUS_EDITING           => array(self::STATUS_COMPLETED),
                            self::STATUS_COMPLETED     => array(self::STATUS_EDITING, self::STATUS_VERIFIED),
                            self::STATUS_VERIFIED           => array(self::STATUS_EDITING, self::STATUS_PUBLISHED),
                            self::STATUS_PUBLISHED         => array(self::STATUS_EDITING)
                            );

    protected $PERMISSIONS  = array(
                            self::STATUS_EDITING        =>array(Iati_WEP_PermissionConts::ADD_ACTIVITY,Iati_WEP_PermissionConts::ADD_ACTIVITY_ELEMENTS,Iati_WEP_PermissionConts::EDIT_ACTIVITY,Iati_WEP_PermissionConts::EDIT_ACTIVITY_ELEMENTS),
                            self::STATUS_COMPLETED  =>array(Iati_WEP_PermissionConts::ADD_ACTIVITY,Iati_WEP_PermissionConts::ADD_ACTIVITY_ELEMENTS,Iati_WEP_PermissionConts::EDIT_ACTIVITY,Iati_WEP_PermissionConts::EDIT_ACTIVITY_ELEMENTS),
                            self::STATUS_VERIFIED        =>array(Iati_WEP_PermissionConts::PUBLISH),
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

    protected  function _getAction($status_key)
    {
        return $this->ACTION[$status_key];
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

    protected function _getNextStatus($status)
    {
        return self::_getStatus(++$status);
    }

    public static function getStatus($status_key = null)
    {
        return self::getInstance()->_getStatus($status_key);
    }

    public static function getAction($status_key)
    {
        return self::getInstance()->_getAction($status_key);
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

    public static function getNextStatus($state)
    {
        if($state == Iati_WEP_ActivityState::STATUS_EDITING) {
            $next_state = Iati_WEP_ActivityState::STATUS_COMPLETED;

        } else if($state == Iati_WEP_ActivityState::STATUS_COMPLETED) {

            $next_state = Iati_WEP_ActivityState::STATUS_VERIFIED;

        } else if($state == Iati_WEP_ActivityState::STATUS_VERIFIED) {

            $next_state = Iati_WEP_ActivityState::STATUS_PUBLISHED;
        } else {
            $next_state = null;
        }
        return $next_state;
    }
}