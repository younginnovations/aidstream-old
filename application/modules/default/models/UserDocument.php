<?php

class Model_UserDocument extends Zend_Db_Table_Abstract
{
    protected $_name = 'user_documents';
    
    public function saveDocumentInfo($documentName)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $orgId = $identity->account_id;
        
        $this->insert(array('filename' => $documentName , 'org_id' => $orgId));
    }
    
    public function fetchAllDocuments($orgId)
    {
        $query = $this->select()
            ->where('org_id = ? ' , $orgId);
        $result = $this->fetchAll($query);
        if($result){
            return $result->toArray();
        }
    }
}