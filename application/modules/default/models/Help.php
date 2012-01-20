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
   
}
