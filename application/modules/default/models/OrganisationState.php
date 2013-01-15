<?
class Model_OrganisationState extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_organisation';
    
    public function updateOrganisationState($organisations_id,$state_id)
    {
        parent::update(array('state_id'=>$state_id),array('id IN(?)'=>$organisations_id));
    }
}