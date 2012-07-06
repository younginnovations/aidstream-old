<?php
/**
 * @todo some class description required
 * @author YIPL Dev team
 *
 */
class Simplified_AjaxController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    public function getFundingOrgsAction()
    {
        $model = new Simplified_Model_DbTable_FundingOrg();
        $data = $model->getAllFundingOrgs();
        $fundingOrgsData = array();
        if($data){
            foreach($data as $fundingOrg){
                $fundingOrgsData[] = $fundingOrg['text'];
            }
        }
        print implode(';' , array_unique($fundingOrgsData));
    }
}

