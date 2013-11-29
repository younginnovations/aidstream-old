<?php

class Model_UserDocument extends Zend_Db_Table_Abstract
{
    protected $_name = 'user_documents';
    
    public function saveDocumentInfo($documentName)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $orgId = $identity->account_id;
        if(!$this->checkIfExists($documentName)){
            $this->insert(array('filename' => $documentName , 'org_id' => $orgId));
        }
    }
    
    public function checkIfExists($filename)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $orgId = $identity->account_id;
        $data = $this->getFileInfo($filename , $orgId);
        if(!$data){
            return false;
        } else {
            $dataArray = $data->toArray();
            if(!empty($dataArray)){
                return true;
            } else {
                return false;
            }
        }
    }
    
    public function getFileInfo($filename , $orgId)
    {
        $query = $this->select()
                        ->where('filename = ?' , $filename)
                        ->where('org_id = ?' , $orgId);
        return  $this->fetchAll($query);
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
    
    public function fetchUsedDocuments($orgId)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('docs' => 'iati_document_link' ), array('id' , '@url' , 'activity_id'))
            ->join(array('iact'=>'iati_activity') , 'docs.activity_id = iact.id' , array())
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id',array())
            ->join(array('ident'=>'iati_identifier') , 'ident.activity_id = iact.id' , array('activity_identifier' , 'text'))
            ->where('iacts.account_id=?',$orgId);
            
        $docs = $this->fetchAll($rowSet)->toArray();
        return $docs;
    }
}