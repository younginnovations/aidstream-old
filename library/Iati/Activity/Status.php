<?
class Iati_Activity_Status
{
    protected static $_instance;
    
    const STATUS_ADDED = 1;
    const STATUS_EDITED = 2;
    const STATUS_CHECKED = 3;
    const STATUS_PUBLISHED = 4;
    
    protected $STATUS_LIST = array(
                              self::STATUS_ADDED     =>'Added',
                              self::STATUS_EDITED    =>'Edited',
                              self::STATUS_CHECKED   =>'Checked',
                              self::STATUS_PUBLISHED =>'Published'
                              );
    protected $TRANSITIONS = array(
                                    self::STATUS_ADDED      => array(self::STATUS_EDITED),
                                    self::STATUS_EDITED     => array(self::STATUS_CHECKED),
                                    self::STATUS_CHECKED    => array(self::STATUS_ADDED, self::STATUS_PUBLISHED),
                                    self::STATUS_PUBLISHED  => array()
                                );
    
    public static function getInstance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new Iati_Activity_Status();
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
}