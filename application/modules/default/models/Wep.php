<?php
/**
 * common model to be used for various database purposes.
 */
class Model_Wep extends Zend_Db_Table_Abstract
{

    protected $_name;

    public function listAll($tableName, $fieldName, $data){
        $this->_name = $tableName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where("$fieldName = ?",$data);
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }

    public function prepPostKeys($table){
        $tableN = $table;
        foreach($data as $key => $eachData){
            /*if($key == 'attr_xmllang'){
             $key = 'attr_xml_lang';
             }*/
            $key = str_replace('attr_', '@', $key);
            $key = str_replace($tableN."_", '', $key);
            $data1[$key] = $eachData;
        }

        return $data1;
    }


    public function prepKeysForForm($table, $data){
        //        print_r($table);exit();
        //$data['@xml'] = 'ddd';
        foreach($data as $key => $eachData){
            /*if($key == '@xml:lang'){
             $key = '@xmllang';
             }*/
            if($key == 'text'){
                $key = $table."_".$key;
            }
            $string = 'attr_'. $table. "_";
            $key = str_replace('@', $string, $key);
            $data1[$key] = $eachData;
        }
        //print_r($data1);exit();
        return $data1;
    }

    /* public function getColumns($tableName){
     $this->_name = $tableName;
     //        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false);
     print_r($this->_getCols());
     }*/

    public function filterField($tableName)
    {
        $this->_name = $tableName;
        //        print($this->_name);
        //        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false);
        $colName = $this->_getCols();
        $i = 0;
        foreach ($colName as $tmpName) {
            if ($tmpName != 'id' && $tmpName != 'lang_id')
            $fieldName[$i] = $tmpName;
            $i++;
        }
        return $fieldName;
    }

    /**
     *
     *
     * @param $tblName
     * @param $fieldName
     * @param $data
     * @return array of data of the row
     */
    public function getRowsByFields($tblName, $fieldName, $data){
        $this->_name = $tblName;
        $rowSet = $this->select()->where("$fieldName = ?",$data);

        $result = $this->fetchAll($rowSet);
        if($result){
            $result = $result->toArray();
        }
//        print_r($result);exit;
        return $result;
    }
    
    public function fetchValueById($tblName, $id, $getField = null){
        $this->_name = $tblName;
        $rowSet = $this->select()->where("id =?", $id);
        
        $result = $this->fetchRow($rowSet);
        if($result){
            $result = $result->toArray();
        }
        if($getField){
            return ($result[$getField])?$result[$getField]:$result['Code'];
        } else {
            return $result['Code'];
        }
    }

    public function getCode($tblName, $codeid,$lang)
    {
        $this->_name = $tblName;
        if (isset($codeid))
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('id = ?', $codeid)
        ->where('lang_id = ?',$lang);
        else
        $rowSet = $this->select()
        ->where('lang_id = ?',$lang);

        $result[0] = $this->fetchAll($rowSet)->toArray();
        $result[1] = $this->_getCols($rowSet);
        return $result;
    }
    
    public function getCodeArray($tblName, $codeid, $lang , $nullOption = false)
    {
        $this->_name = $tblName;
        if ($codeid)
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('id = ?', $codeid)->where('lang_id = ?',$lang);
        else
        $rowSet = $this->select()->where('lang_id = ?',$lang)->order(array('Code ASC'));

        $result = $this->fetchAll($rowSet)->toArray();
        $finalResult = array();
        if($nullOption){
            $finalResult[''] = 'Select Anyone';
        }   
        foreach($result as $key => $eachResult)
        {
            $name = '';
            if(key_exists('Name', $eachResult)){
                $name = " - ".$eachResult['Name'];
            }
            if(key_exists('Abbreviation', $eachResult)){
                $name = " - ".$eachResult['Abbreviation'];
            }
            
            $finalResult[$eachResult['id']] = $eachResult['Code']. $name;
        }
        return $finalResult;
    }

    public function getCodeandName($tblName, $lang_id)
    {
        $this->_name = $tblName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('lang_id = ?',$lang_id)
        ->order(array('id ASC'));
        $results = $this->fetchAll($rowSet)->toArray();
        foreach ($results as $result) {
            $return[$result['id']] = $result['Code'];
        }
        return $return;
    }

    public function insertRowsToTable($tblName, $data){
        $this->_name = $tblName;
        return parent::insert($data);
    }

    public function updateRowsToTable($tblName, $data){
        $this->_name = $tblName;
        return parent::update($data,array('id= ?' => $data['id']));
    }
    
    public function updateRow($tblName, $data, $fieldName,  $id){
        $this->_name = $tblName;
        //print $tblName; print $fieldName; print_r($data);exit;
        return parent::update($data, array("$fieldName = ?" => $id));
    }
    
    public function deleteRowById($id,$tblName) {
        $this->_name = $tblName;
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $this->delete($where);
    }
    
    public function deleteRow($tblName, $fieldName, $value)
    {
        $this->_name = $tblName;
        $where = $this->getAdapter()->quoteInto("$fieldName = ?", $value);
        $this->delete($where);
    }

    public function getDefaults($tableName, $fieldName, $data)
    {
        $this->_name = $tableName;
        $defaults = $this->listAll($tableName, $fieldName, $data);
        $defaultArray = unserialize($defaults[0]['object']);
        return $defaultArray;
    }
    
    public function getIdByField($tableName, $fieldName, $data)
    {
        $this->_name = $tableName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where("$fieldName = ?",$data);

        $result = $this->fetchRow($rowSet)->toArray();
        return $result['id'];
    }
    
    public function findIdByFieldData($tblName, $data, $lang){
        $this->_name = $tblName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('Code = ?', $data)
        ->where('lang_id = ?',$lang);
        return $this->fetchAll($rowSet)->toArray();
    }
    
    public function listOrganisation($tableName)
    {
        $this->_name = $tableName;
        $rowSet = $this->select();
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
//        $select->where()
    }
    
    public function getRowById($tableName, $fieldName, $data)
    {
        $this->_name = $tableName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
         ->where("$fieldName = ?",$data);
         
        $result = $this->fetchRow($rowSet);
        if($result){
            $result = $result->toArray();
        }
        return $result;
        
    }
    
    /**
     *retrieves all the rows of users by account id
     *$tableName is the table name to be queried on
     *$data is the condtional value, in this case account id
     *$additional is  an array for addtional query,
     *eg $additional = array('fieldName' => 'value')
     *or  $additional = array('user_id' => '2')
     *
     */
    public function getUsersByAccountId($tableName, $data, $additional = array()){
        $this->_name = 'user';
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from('user')
            ->join(array('p'=> 'profile'),'user.user_id = p.user_id')
            ->where ("account_id = ?", $data);
        if($additional){
            foreach($additional as $key => $value){
                $rowSet->where("$key = ?", $value);
            }
        }
        
        $result = $this->fetchAll ($rowSet);
        if($result){
            $result = $result->toArray();
        }
        return $result;
    }
 
    public function getAccountUserName($account_id)
    {
        $this->_name = 'account';
        $rowSet = $this->select()->setIntegrityCheck(false)
                ->where("id = ?", $account_id);
        $result = $this->fetchRow ($rowSet);
        if($result){
            
            $result = $result->username;
        }
        return $result;
    }
    
    public function userExists($fieldName, $email){
        $this->_name = 'user';
        $rowSet = $this->select()->setIntegrityCheck(false)
                        ->where("$fieldName = ?", $email);
        $result = $this->fetchRow ($rowSet);
        return $result;
    }
    
    public function getUserPermission($user_id){
        $this->_name = 'user_permission';
        $rowSet = $this->select()->setIntegrityCheck(false)
                                ->where('user_id = ?', $user_id);
        $result = $this->fetchRow($rowSet);

        if($result){
            $result = unserialize($result->object);
        }
        return $result;
    }

    /**
     * Update all reporting org values in activities and organisation data if changed in settings.
     */
    public function settingsChange() 
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        //Get Activities
        $model = new Model_Wep();
        $activities = $model->listAll('iati_activities', 'account_id', $identity->account_id);
        $activities_id = $activities[0]['id'];
        $activityArray = $model->listAll('iati_activity', 'activities_id', $activities_id);
        
        //Update Each Activity Reporting Org
        $activityReportingOrg = new Model_ReportingOrg();
        $activityHashModel = new Model_ActivityHash();
        $activityModel = new Model_Activity();
        foreach ($activityArray as $key=>$activity) {
            $activityReportingOrg->updateReportingOrg($activity['id']);
            $updated = $activityHashModel->updateActivityHash($activity['id']);
            if($updated){
                //Update each activity: last updated time
                $wepModel = new Model_Wep();
                $activityData['id'] = $activity['id'];
                $activityData['@last_updated_datetime'] = date('Y-m-d H:i:s');
                $wepModel->updateRowsToTable('iati_activity', $activityData);             
            }
        }

        //Get Organisation Id
        $organisationModelObj = new Model_Organisation();
        $organisationId = $organisationModelObj->checkOrganisationPresent($identity->account_id);

        // If organisation exists        
        if ($organisationId) {
            //Update Organisation Reporting Org
            $organisationReportingOrg = new Model_OrganisationDefaultElement();
            $organisationReportingOrg->updateElementData('ReportingOrg', $organisationId);

            //Update Organisation Hash
            $organisationHashModel = new Model_OrganisationHash();
            $update = $organisationHashModel->updateHash($organisationId);
            
            if ($update) {
                //Update organisation: last updated time
                $wepModel = new Model_Wep();
                $organisationData = array();
                $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
                $organisationData['id'] = $organisationId;
                $wepModel->updateRowsToTable('iati_organisation' , $organisationData);                        
            }
        }

        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($identity->account_id);
        if($registryInfo && $registryInfo->publisher_id){
            $pub = new Iati_WEP_Publish(
                    $identity->account_id,
                    $registryInfo->publisher_id ,
                    $registryInfo->publishing_type
                );
            $pub->publish();
        }

    }

    /**
     * Update IATI Identifier values in activities and organisation data if changed in settings.
     */
    public function updateIatiIdentifiers($reportingOrgRef) {
        $identity = Zend_Auth::getInstance()->getIdentity(); 
        // For Activities
        $activities = $this->listAll('iati_activities', 'account_id', $identity->account_id);
        $activitiesId = $activities[0]['id'];
        $activityArray = $this->listAll('iati_activity', 'activities_id', $activitiesId);
        foreach ($activityArray as $key=>$activity) {
            $activityRow = $this->getRowById('iati_identifier', 'activity_id', $activity['id']);
            $iatiIdentifier = $reportingOrgRef . '-' . $activityRow['activity_identifier']; 
            $data['text'] = $iatiIdentifier;
            $this->updateRow('iati_identifier', $data, 'activity_id', $activity['id']);
        }
        // For Organisation Data
        $organisation = $this->getRowById('iati_organisation', 'account_id', $identity->account_id);
        $organisationId = $organisation['id'];
        $this->updateRow('iati_organisation/identifier', array('text'=>$reportingOrgRef), 'organisation_id', $organisationId);
    }

}