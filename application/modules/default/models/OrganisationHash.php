<?php

class Model_OrganisationHash extends Zend_Db_Table_Abstract
{
    protected $_name = 'organisation_hash';
    
    public function updateHashByOrganisationId($data)
    {
        $this->update($data,array('organisation_id = ?'=>$data['organisation_id']));
    }
    
    public function getByOrganisationId($organisation_id)
    {
        $rowSet = $this->select()
                ->where('organisation_id = ?',$organisation_id);
        $organisation = $this->fetchRow($rowSet);
        return $organisation;
    }
    
    public function updateHash($organisation_id)
    {
        $organisationClassobj = new Iati_Aidstream_Element_Organisation();
        $organisations = $organisationClassobj->fetchData($organisation_id , false);
        unset($organisations['Organisation']['@last_updated_datetime']);
        $new_hash = sha1(serialize($organisations));
        
        $data['organisation_id'] = $organisation_id;
        $data['hash'] = $new_hash;
        
        $has_hash = $this->getByOrganisationId($organisation_id);
        if($has_hash){
            if($has_hash['hash'] == $new_hash){
                return false;
            } else {
                $this->updateHashByOrganisationId($data);   
                return true;
            }
        } else {
            $this->insert($data);
            return true;
        }
    }
    
    public function deleteOrganisationHash($organisation_id)
    {
        $this->delete(array('organisation_id = ?'=>$organisation_id));
    }
}