<?php

class Model_Help extends Zend_Db_Table_Abstract
{
    protected $_name = 'Help';
    
    public function getHelpMessage($element)
    {
        $select = $this->select()
            ->from($this,array('message'))
            ->where('element_name = ?',$element);
        return $this->fetchRow($select);
    }
    
    public function getHelpMessageById($id)
    {
        $select = $this->select()
            ->where('id = ?' , $id);
        return $this->fetchRow($select)->toArray();
    }
    
    public function updateHelpMessageById($data , $id)
    {
        $this->update($data , array('id = ?' => $id));
    }
    
    public function updateXmlLangMessages($message)
    {
        $this->update(array('message'=> $message) , array("element_name LIKE ('%xml_lang%')"));
    }
    
    public function updateDateMessages($message)
    {
        $this->update(array('message'=> $message) , array("element_name LIKE ('%iso_date%')"));
    }
    
    public function getMessagesForList()
    {
        $messages = $this->fetchAll(null , "element_name ASC")->toArray();
        $data = array();
        foreach($messages as $message)
        {
            if(preg_match('/xml_lang/' , $message['element_name'])){
                $data['xml_lang'][] = $message;
            } else if (preg_match('/iso_date/' , $message['element_name'])){
                $data['iso_date'][] = $message;
            } else if (preg_match('/activity_defaults/' , $message['element_name'])){
                $data['default'][] = $message;
            } else {
                $data['other'][] = $message;
            }
        }
        return $data;
    }
    
    public static function getHelpMessageForStates($state)
    {
        if($state == Iati_Wep_ActivityState::STATUS_EDITING){
            
            $help =  "Clicking on Completed button would mean that you have completed entering information about the activity.
            The activity will be changed to Completed state from Edit state.";
            
        } else if($state == Iati_WEP_ActivityState::STATUS_COMPLETED){
            
            $help = "Clicking on Verified button would mean that you have checked and verified the
            information about the activity and its ready to publish. The activity will be changed to Verified state.";
            
        } else if($state == Iati_WEP_ActivityState::STATUS_VERIFIED){
            $modelRegistry  = new Model_RegistryInfo();
            $registryData = $modelRegistry->getRegistryInfoForCurrentAccount();
            $updateRegistry = $registryData['update_registry'];
            if($updateRegistry){
                $help = "Clicking on Published button would create the IATI XML files and would mean that
                the activity can be pushed to the IATI Registry. You have selected \"Yes\" in \"Automatically
                Update the IATI Registry when publishing files\" in Settings. Your files will be directly
                published to the IATI Registry.";
            } else {
                $help = "Clicking on Published button would create the IATI XML files and would mean that
                the activity can be pushed to the IATI Registry. You have selected \"No\" in \"Automatically
                Update the IATI Registry when publishing files\" in Settings. When you publish,
                activity XML files will be created but the files will not be registered at IATI Registry.
                The created XML files will be listed and can be pushed to Registry from List Published Files section.";
            }
        }
        return $help;
    }
    
    public static function getHelpMessagesForPublishButton()
    {
        $modelRegistry  = new Model_RegistryInfo();
        $registryData = $modelRegistry->getRegistryInfoForCurrentAccount();
        $updateRegistry = $registryData['update_registry'];
        if($updateRegistry){
            $message = "You have selected \"Yes\" in \"Automatically Update the IATI Registry when publishing files\"
            in Settings. Your files will be directly published to the IATI Registry.";
        } else {
            $message = "You have selected \"No\" in \"Automatically Update the IATI Registry when publishing files\"
            in Settings. When you publish, activity XML files will be created but the files will not be registered
            at IATI Registry. The created XML files will be listed and can be pushed to Registry from
            List Published Files section.";
        }
        return $message;
    }
    
    public static function showHelpforPushToRegistry()
    {
        $modelRegistry  = new Model_RegistryInfo();
        $registryData = $modelRegistry->getRegistryInfoForCurrentAccount();
        $updateRegistry = $registryData['update_registry'];
        if($updateRegistry) {
            return false;
        } else {
            return true;
        }
    }
}
