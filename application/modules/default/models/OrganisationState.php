<?php
class Model_OrganisationState extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_organisation';
    
    public function updateOrganisationState($organisationIds,$stateId)
    {
        parent::update(array('state_id'=>$stateId),array('id IN(?)'=>$organisationIds));
    }
}