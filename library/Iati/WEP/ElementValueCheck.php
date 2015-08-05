<?php

class Iati_WEP_ElementValueCheck {

    protected static $requiredElements = array(
        'ReportingOrg'     => 'Reporting Organisation',
        'IatiIdentifier'   => 'Iati Identifier',
        'Title'            => 'Title',
        'Description'      => 'Description',
        'ParticipatingOrg' => 'Participating Organisation',
        'ActivityStatus'   => 'Activity Status',
        'ActivityDate'     => 'Activity Date',
        'Sector'           => 'Sector'
    );

    protected static $requiredElementValues = array(
        'ActivityDate'     => '@iso_date',
        'ParticipatingOrg' => '@role'
    );

    /**
     * Check if default elements are present for an Activity and return errors if any.
     * 
     * @param array $activityIds
     * @return array $errors
     */
    public static function checkDefaults($activityIds)
    {
        $errors = array();
        foreach ($activityIds as $activityId) {
            foreach (self::$requiredElements as $element => $fullName) {
                $row = self::getRowSet($element, $activityId);
                if (!$row['value']) {
                     if($fullName == "Participating Organisation"){
                        if(isset($row['message']['participatingOrg'])){
                            $fullName = "Participating Organisation(either Funding or Implementing)";
                        }
                     }
                    $errors[$activityId][] = $fullName;
                }
            }
        }

        $activityLevelCheck = self::checkRecipients($activityId);
        $transactionLevelCheck = self::checkTransactionCountryRegion($activityId);

        if ($activityLevelCheck == "valid" && $transactionLevelCheck == "valid")
        {
            $errors[$activityId][] = "Either Transaction Recipient Country/Region Or Recipient Country/Region in Activity level" ;
        }

        if (($activityLevelCheck == "valid" && $transactionLevelCheck == "invalid") || ($activityLevelCheck == "invalid" && $transactionLevelCheck == "invalid") || ($activityLevelCheck == "nodata" && $transactionLevelCheck == "invalid"))
        {
            $errors[$activityId][] = 'Either Transaction Recipient Country/Region to all transactions Or you may choose to delete all your Transaction Recipient Country/Region and use Recipient Country/Region in Activity Level'; 
        }

        if ($activityLevelCheck == "invalid" && $transactionLevelCheck == "valid")
        {
            $errors[$activityId][] = 'Either Transation Recipient Country/Regoin or Recipient Country/Region in Activity level'; 
        }

        if ($activityLevelCheck == "invalid" && $transactionLevelCheck == "nodata")
        {
            $errors[$activityId][] = 'Mention % when using both Recipient Country and Recipient Region Region and % shound sum to 100%'; 
        }

        if ($activityLevelCheck == "nodata" && $transactionLevelCheck == "nodata")
        {
            $errors[$activityId][] = 'Recipient Country Or Recipient Region'; 
        }

        return $errors;
    }

    /**
     * Check If Value Exist For An Element Of An Activity
     * 
     * @param type $name, className
     * @param type $id, parentId
     * @return array  
     */
    public static function getRowSet($name , $id)
    {
        $string = "Iati_Aidstream_Element_Activity_" . $name;
        $obj = new $string;
        $rowSet = $obj->fetchData($id , true);
        if (!$rowSet) {
            $content = false;
            $value = false;
        } else {
            $content = true;
            $value = true;
            $count;
            if (array_key_exists($name, self::$requiredElementValues)) {
                if ($name == 'ParticipatingOrg') {        
                    if(!self::ifExistsParticipatingRole($rowSet)) {
                        $value = false;
                        $message['participatingOrg'] = 'fail';
                    }
                }
                } elseif ($name == 'ActivityDate') {
                    if (!$rowSet[0]['@iso_date'])
                        $value = false;
                }
            
        }

        return array(
                'content' => $content,
                'value'   => $value,
                'message' => $message
            );
    }

    /**
     * Check if recipient country or recipient region value exists in activity level.
     *
     * @param type $id
     * @return boolean
     */
    public static function checkRecipients($id)
    {
        $recipientCountry = new Iati_Aidstream_Element_Activity_RecipientCountry;
        $recipientRegion = new Iati_Aidstream_Element_Activity_RecipientRegion;

        $recipientCountryData = $recipientCountry->fetchData($id, true);
        $recipientRegionData = $recipientRegion->fetchData($id, true);
        $percentage = $recipientCountryData[0]['@percentage'] + $recipientRegionData[0]['@percentage'];

        if ($recipientCountryData && $recipientRegionData && $percentage == 100) {
            $value = "valid";
        } elseif (!$recipientCountryData && !$recipientRegionData) {
            $value = "nodata";
        } elseif($recipientCountryData && !$recipientRegionData) {
            $value = "valid";
        } elseif (!$recipientCountryData && $recipientRegionData) {
            $value = "valid";
        } else{
            $value = "invalid";
        }

        return $value;
    }

    /**
    *check if recipient country and region present in transaction level
    */
    public static function checkTransactionCountryRegion($id)
    {
        $transaction =  new Iati_Aidstream_Element_Activity_Transaction;
        $transactionData = $transaction->fetchData($id,true);
        $count = 0;
        $status = array();
        foreach ($transactionData as $transactionCountry)
        {
            $transctionRecipientConData = $transactionCountry['RecipientCountry'];
            $transctionRecipientRegData = $transactionCountry['RecipientRegion'];

            if(!empty($transctionRecipientConData) || !empty($transctionRecipientRegData)) {
                $status[] = $transctionRecipientConData ;
            }
        }
        $totalTransactionData = count($transactionData);
        $totalStatus = count($status);

        if($totalStatus == 0)
        {
            $value = "nodata";
        }
        elseif ($totalTransactionData == $totalStatus)
        {
            $value = "valid";
        }
        else
        {
            $value = "invalid" ;
        }
        return $value;
    }

    /**
     * Check if group elements are selected or not.
     * 
     * @param  [array]  $fieldGroup
     * @param  [array]  $elements
     * @return boolean
     */
    public static function hasGroupSet($fieldGroup, $elements)
    {
        $selected = false; 
        foreach ($elements as $element) 
        {
            $name = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $element));
            if ($fieldGroup[$name]) {   
                $selected = true;
                break;
            }   
        }
        return $selected;
    }

    public static function ifExistsParticipatingRole($rowSet)
    {
        foreach($rowSet as $row)
        {
            //check funding or implementing
            if($row['@role'] == 1 || $row['@role'] == 4)
            {
                return true;
            }
        }
        return false;
    }
}
?>