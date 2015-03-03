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
                    $errors[$activityId][] = $fullName;
                }
            }
        }

        $check = self::checkRecipients($activityId);
        if (!$check) {
            $errors[$activityId][] = 'Recipient Country or Recipient Region';
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
            if (array_key_exists($name, self::$requiredElementValues)) {
                if ($name == 'ParticipatingOrg') {
                    if ($rowSet[0]['@role'] != 1 && $rowSet[0]['@role'] != 4) {
                        $value = false;
                    }
                } elseif ($name == 'ActivityDate') {
                    if (!$rowSet[0]['@iso_date'])
                        $value = false;
                }
            }
        }

        return array(
                'content' => $content,
                'value'   => $value
            );
    }

    /**
     * Check if recipient country or recipient region value exists.
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

        if ($recipientCountryData && $recipientRegionData) {
            $value = false;
        } elseif (!$recipientCountryData && !$recipientRegionData) {
            $value = false;
        } else {
            $value = true;
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

}
?>