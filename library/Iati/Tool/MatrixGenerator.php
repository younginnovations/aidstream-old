<?php
class Iati_Tool_MatrixGenerator extends Iati_Tool_IatiActivity
{
    protected $xmlArray;
    protected $folderPath;
    protected $attribute;
    protected $group_name;
    protected $ref_reporting_org;
    protected $identifier;
    protected $text_reporting_org;
    protected $reference_reporting_org;
    
	public function setIdentifier($identifier)
    {
    	$this->identifier = $identifier;
    }
    public function getIdentifier()
    {
    	return $this->identifier;
    }
    public function setText_reporting_org($text_reporting_org)
    {
    	$this->text_reporting_org = $text_reporting_org;
    }
    public function getReference_reporting_org()
    {
    	return $this->reference_reporting_org;
    }
    public function setReference_reporting_org($reference_reporting_org)
    {
    	$this->reference_reporting_org = $reference_reporting_org;
    }
    public function getText_reporting_org()
    {
    	return $this->text_reporting_org;
    }

    public function setXmlArray($array)
    {
        $this->xmlArray = $array;
    }

    public function getXmlArray()
    {
        return $this->xmlArray;
    }

    public function setFolderPath($path)
    {
        $this->folderPath = $path;
    }

    public function getFolderPath()
    {
        return $this->folderPath;
    }

    public function validateActivity($activity)
    {
        foreach($activity as $group=>$values)
        {
            $groupname = preg_replace('/-/', '_', $group);
            $function_name = $groupname."_"."Validation";
            $this->$function_name($values);
        }
    }
    public function isValueArray($value)
    {
        $is_array = false;
        $max_size = 0;
        array_values($value);
        foreach($value as $value)
        {
            if(is_array($value))
            {
                $is_array = true;
                if(sizeof($value)>$max_size)
                {
                    $max_size = sizeof($value);
                }
            }
        }
         
        return array('is_array'=>$is_array,'size'=>$max_size);
    }
    public function iati_activity_Validation($values)
    {
        $lang= 0;
        $default_currency = 0;
        $hierarchy = 0;
        $last_update_time = 0;
         
        foreach($values as $key => $value)
        {
            switch($key)
            {
                case "xml:lang":
                    $status =  $this->language($value);
                    $lang = $status;
                    break;
                case "default-currency":
                    $status = $this->currency($value);
                    $default_currency = $status ;
                    break;
                case "hierarchy":
                    $status = $this->hierarchy($value);
                    $hierarchy = $status;
                    break;
                case "last-updated-datetime":
                    $status = $this->dateTime($value);
                    $last_update_time = $status;
                    break;

            }

        }
        $this->setIati_activity_xml_lang($lang);
        $this->setIati_activity_default_currency($default_currency) ;
        $this->setIati_activity_hierarchy($hierarchy);
        $this->setIati_activity_last_updated_datetime($last_update_time);
    }

    public function reporting_org_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_ref = "";
        $main_type = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $ref = 0;
            $type = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                    if($this->reference_reporting_org == $values)
                    {
                    	$this->text_reporting_org = "";
                    }
                    else{
                    	$this->text_reporting_org = "Reporting organisation name does not match";
                    }
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "ref":
                            	$this->ref_reporting_org = $value;
                                $status = $this->organisation_identifier($value);
                                $ref = $status;
                                break;
                            case "type":
                                $status = $this->organisation_type($value);
                                $type = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_ref = $main_ref.$ref;
            $main_text = $main_text.$text;
            $main_type = $main_type.$type;
        }
        $this->setReporting_org_xml_lang($main_lang);
        $this->setReporting_org_ref($main_ref);
        $this->setReporting_org_type($main_type);
        $this->setReporting_org_text($main_text);

    }

    public function participating_org_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_ref = "";
        $main_type = "";
        $main_role = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $ref = 0;
            $type = 0;
            $role = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "ref":
                                $status = $this->organisation_identifier($value);
                                $ref = $status;
                                break;
                            case "type":
                                $status = $this->organisation_type($value);
                                $type = $status;
                                break;
                            case "role":
                                $status = $this->organisation_role($value);
                                $role = $status;
                                break;
                                 
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_ref = $main_ref.$ref;
            $main_text = $main_text.$text;
            $main_type = $main_type.$type;
            $main_role = $main_role.$role;
        }
        $this->setParticipating_org_xml_lang($main_lang);
        $this->setParticipating_org_ref($main_ref);
        $this->setParticipating_org_type($main_type);
        $this->setParticipating_org_role($main_role);
        $this->setParticipating_org_text($main_text);

    }

    public function recipient_country_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_code = "";
        $main_percentage = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $code = 0;
            $percentage = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "code":
                                $status = $this->code('country',$value);
                                $code = $status;
                                break;
                            case "percentage":
                                $status = $this->integer($value);
                                $percentage = $status;
                                break;
                                 
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
            $main_percentage = $main_percentage.$percentage;
        }
        $this->setRecipient_country_xml_lang($main_lang);
        $this->setRecipient_country_text($main_text);
        $this->setRecipient_country_code($main_code);
        $this->setRecipient_country_percentage($percentage);

    }

    public function recipient_region_Validation($values)
    {
        $main_code = "";
        $main_text = "";
        $main_percentage  = "";
        $main_lang = "";
        foreach($values as $element)
        {
            $code = 0;
            $text = 0;
            $percentage = 0;
            $lang = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "code":
                                $status = $this->code('region', $value);
                                $code = $status;
                                break;
                            case "percentage":
                                $status = $this->integer($value);
                                $percentage = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
            $main_percentage = $main_percentage.$percentage;
        }
        $this->setRecipient_region_code($main_code);
        $this->setRecipient_region_text($main_text);
        $this->setRecipient_region_percentage($main_percentage);
        $this->setRecipient_region_xml_lang($main_lang);

    }
    public function collaboration_type_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_code = "";
        $main_percentage = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $code = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "code":
                                $status = $this->code('collaboration_type',$value);
                                $code = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
        }
        $this->setCollaboration_type_xml_lang($main_lang);
        $this->setCollaboration_type_text($main_text);
        $this->setCollaboration_type_code($main_code);
         
    }
    public function default_flow_type_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_code = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $code = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                                 
                            case "code":
                                $status = $this->code('flow_type',$value);
                                $code = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
        }
        $this->setDefault_flow_type_xml_lang($main_lang);
        $this->setDefault_flow_type_text($main_text);
        $this->setDefault_flow_type_code($main_code);
    }

    public function default_aid_type_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_code = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $code = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                                 
                            case "code":
                                $status = $this->code('aid_type',$value);
                                $code = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
        }
        $this->setDefault_aid_type_xml_lang($main_lang);
        $this->setDefault_aid_type_text($main_text);
        $this->setDefault_aid_type_code($main_code);
         
    }

    public function default_finance_type_Validation($values)
    {
        $main_text = "";
        $main_lang = "";
        $main_code = "";
        foreach($values as $element)
        {
            $text = 0;
            $lang = 0;
            $code = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "code":
                                $status = $this->code('finance_type',$value);
                                $code = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
        }
        $this->setDefault_finance_type_xml_lang($main_lang);
        $this->setDefault_finance_type_text($main_text);
        $this->setDefault_finance_type_code($main_code);
         

    }
    public function iati_identifier_Validation($values)
    {
    	$text = 0;
    	$element = (array)$values;
    	foreach ($element as $key=>$value)
    	{
    		$status = $this->text($value);
	    	if($status == 3)
	    	{
	    		$identifier = $this->ref_reporting_org;
	    		$exp = "/^".$identifier."/";
	    		$id = (array)$value;
	    		$id = $id[0];
	    		$this->identifier = $id;
	    		if(preg_match($exp, $id))
	    		{
	    			$text = "3";
	    		}else{
	    			$text = "1";
	    		}
	    	}
    	}
    	
    	$this->setIati_identifier_text($text);
     }
    public function other_identifier_Validation($values)
    {
        $main_owner_ref = "";
        $main_owner_name = "";
        $main_text = "";
        foreach($values as $element)
        {
            $owner_ref = 0;
            $owner_name = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "owner-ref":
                                $status =  $this->organisation_identifier($value);
                                $owner_ref = $status;
                                break;
                            case "owner-name":
                                $status = $this->text($value);
                                $owner_name = $status;
                                break;
                        }
                    }
                }
            }
            $main_owner_ref = $main_owner_ref.$owner_ref;
            $main_owner_name = $main_owner_name.$owner_name ;
            $main_text = $main_text.$text;
        }
        $this->setOther_identifier_owner_ref($main_owner_ref);
        $this->setOther_identifier_owner_name($main_owner_name);
        $this->setOther_identifier_text($main_text);

    }
    public function title_Validation($values)
    {
        $main_text = "";
        $main_lang = '';
        foreach($values as $element)
        {
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                        }
                    }
                }
            }
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
        }
        $this->setTitle_xml_lang($main_lang);
        $this->setTitle_text($main_text);
    }
    public function description_Validation($values)
    {
        $main_type = "";
        $main_text = "";
        $main_lang = '';
        foreach($values as $element)
        {
            $type = 0;
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "type":
                                $status =  $this->code('description_type',$value);
                                $type = $status;
                                break;
                        }
                    }
                }
            }
            $main_type = $main_type.$type;
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
        }
        $this->setDescription_type($main_type);
        $this->setDescription_xml_lang($main_lang);
        $this->setDescription_text($main_text);
    }
    public function sector_Validation($values)
    {
        $main_vocabulary = "";
        $main_text = "";
        $main_lang = '';
        $main_percentage = '';
        $main_code = '';
        foreach($values as $element)
        {
            $vocabulary = 0;
            $code = 0;
            $percentage = 0;
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "vocabulary":
                                $status =  $this->code('vocabulary',$value);
                                $vocabulary = $status;
                                break;
                            case "percentage":
                                $status =  $this->integer($value);
                                $percentage = $status;
                                break;
                            case "code":
                                $status =  $this->code('sector',$value);
                                $percentage = $status;
                                break;
                        }
                    }
                }
            }
            $main_vocabulary = $main_vocabulary.$vocabulary;
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
            $main_code = $main_lang.$code;
            $main_percentage = $main_percentage.$percentage;
        }
        $this->setSector_vocabulary($main_vocabulary);
        $this->setSector_code($main_code);
        $this->setSector_text($main_text);
        $this->setSector_percentage($main_percentage);
        $this->setSector_xml_lang($main_lang);
    }

    public function activity_date_Validation($values)
    {
        $main_type = "";
        $main_iso_date = "";
        $main_lang = '';
        $main_text = '';
        foreach($values as $element)
        {
            $type = 0;
            $iso_date = 0;
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "type":
                                $status =  $this->code('activity_date_type',$value);
                                $type = $status;
                                break;
                            case "iso-date":
                                $status =  $this->dateTime($value);
                                $iso_date = $status;
                                break;
                                 
                        }
                    }
                }
            }
            $main_type = $main_type.$type;
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
            $main_iso_date = $main_iso_date.$iso_date;
        }
        $this->setActivity_date_type($main_type);
        $this->setActivity_date_xml_lang($main_lang);
        $this->setActivity_date_text($main_text);
        $this->setActivity_date_iso_date($main_iso_date);
    }

    public function default_tied_status_Validation($values)
    {
        $main_code = '';
        $main_lang = '';
        $main_text = '';
        foreach($values as $element)
        {

            $code = 0;
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "code":
                                $status =  $this->code('tied_status',$value);
                                $code = $status;
                                break;
                                 
                        }
                    }
                }
            }
            $main_code = $main_code.$code;
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
        }
        $this->setDefault_tied_status_code($main_code);
        $this->setDefault_tied_status_xml_lang($main_lang);
        $this->setDefault_tied_status_text($main_text);
    }

    public function policy_marker_Validation($values)
    {
        $main_significance = '';
        $main_vocabulary = '';
        $main_text = '';
        $main_code = '';
        $main_lang = '';
        foreach($values as $element)
        {
            $significance = 0;
            $code = 0;
            $vocabulary = 0;
            $text = 0;
            $lang = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "significance":
                                $status =  $this->code('policy_significance',$value);
                                $significance = $status;
                                break;
                            case "code":
                                $status =  $this->code('policy_marker',$value);
                                $code = $status;
                                break;
                            case "vocabulary":
                                $status =  $this->code('vocabulary',$value);
                                $vocabulary = $status;
                                break;
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                        }
                    }
                }
            }
            $main_code = $main_code.$code;
            $main_vocabulary = $main_vocabulary.$vocabulary;
            $main_text = $main_text.$text;
            $main_significance = $main_significance.$significance;
            $main_lang = $main_lang.$lang;
        }
        $this->setPolicy_marker_code($main_code);
        $this->setPolicy_marker_vocabulary($main_vocabulary);
        $this->setPolicy_marker_text($main_text);
        $this->setPolicy_marker_significance($main_significance);
        $this->setPolicy_marker_xml_lang($main_lang);
    }

    public function transaction_Validation($values)
    {
        $main_transaction_type_code = "";
        $main_transaction_type_text = "";

        $main_provider_org_text = "";
        $main_provider_org_ref = "";
        $main_provider_org_provider_activity_id = "";

        $main_receiver_org_text = "";
        $main_receiver_org_ref = "";
        $main_receiver_org_receiver_activity_id = "";

        $main_value_text = "";
        $main_value_currency = "";
        $main_value_value_date = "";

        $main_description_text = "";
        $main_description_xml_lang = "";

        $main_transaction_date_iso_date = "";
        $main_transaction_date_text = "";

        $main_flow_type_code = "";
        $main_flow_type_text = "";
        $main_flow_type_xml_lang = "";

        $main_finance_type_code = "";
        $main_finance_type_text = "";
        $main_finance_type_xml_lang = "";

        $main_aid_type_code = "";
        $main_aid_type_text = "";
        $main_aid_type_xml_lang = "";

        $main_disbursement_channel_code = "";
        $main_disbursement_channel_text = "";

        $main_tied_status_code = "";
        $main_tied_status_text = "";
        $main_tied_status_xml_lang = "";
        foreach($values as $transaction)
        {
            
            $this->transaction_transaction_type_code = 0;
            $this->transaction_transaction_type_text = 0;

            $this->transaction_provider_org_text = 0;
            $this->transaction_provider_org_ref = 0;
            $this->transaction_provider_org_provider_activity_id = 0;

            $this->transaction_receiver_org_text = 0;
            $this->transaction_receiver_org_ref = 0;
            $this->transaction_receiver_org_receiver_activity_id = 0;

            $this->transaction_value_text = 0;
            $this->transaction_value_currency = 0;
            $this->transaction_value_value_date = 0;

            $this->transaction_description_text = 0;
            $this->transaction_description_xml_lang = 0;

            $this->transaction_transaction_date_iso_date = 0;
            $this->transaction_transaction_date_text = 0;

            $this->transaction_flow_type_code = 0;
            $this->transaction_flow_type_text = 0;
            $this->transaction_flow_type_xml_lang = 0;

            $this->transaction_finance_type_code = 0;
            $this->transaction_finance_type_text = 0;
            $this->transaction_finance_type_xml_lang = 0;

            $this->transaction_aid_type_code = 0;
            $this->transaction_aid_type_text = 0;
            $this->transaction_aid_type_xml_lang = 0;

            $this->transaction_disbursement_channel = 0;
            $this->transaction_disbursement_channel_code = 0;
            $this->transaction_disbursement_channel_text = 0;

            $this->transaction_tied_status_code = 0;
            $this->transaction_tied_status_text = 0;
            $this->transaction_tied_status_xml_lang = 0;
            foreach($transaction as $trans_key=>$trans_element)
            {
                $trans_element = (array)$trans_element;
                $element_name = preg_replace('/-/','_',$trans_key);

                foreach ($trans_element as $key=>$values)
                {
                    if(!is_array($values)){
                        $status = $this->text($values);
                        if($element_name == 'value'){
                            $status = $this->integer($values);
                        }
                        $variable = "transaction_".$element_name."_text";
                        $this->$variable = $status;

                    }else{                     
                        foreach($values as $key=>$value)
                        {
                            switch($key)
                            {
                                case "xml:lang":
                                    $status =  $this->language($value);
                                    $variable = "transaction_".$element_name."_xml_lang";
                                    $this->$variable = $status;
                                    break;
                                case "code":
                                    $status = $this->code($element_name,$value);
                                    $variable = "transaction_".$element_name."_code";
                                    $this->$variable = $status;
                                    break;
                                case "ref":
                                    $status = $this->organisation_identifier($value);
                                    $variable = "transaction_".$element_name."_ref";
                                    $this->$variable = $status;
                                    break;
                                case "provider-activity-id":
                                    $status = $this->text($value);
                                    $variable = "transaction_".$element_name."_provider_activity_id";
                                    $this->$variable = $status;
                                    break;
                                case "receiver-activity-id":
                                    $status = $this->text($value);
                                    $variable = "transaction_".$element_name."_receiver_activity_id";
                                    $this->$variable = $status;
                                    break;
                                case "currency":
                                    $status = $this->currency($value);
                                    $variable = "transaction_".$element_name."_currency";
                                    $this->$variable = $status;
                                    break;
                                case "value-date":
                                    $status = $this->dateTime($value);
                                    $variable = "transaction_".$element_name."_value_date";
                                    $this->$variable = $status;
                                    break;
                                case "iso-date":
                                    $status = $this->dateTime($value);
                                    $variable = "transaction_".$element_name."_iso_date";
                                    $this->$variable = $status;
                                    break;


                            }
                        }
                    }
                }

            }
            $main_transaction_type_code .= $this->transaction_transaction_type_code;
            $main_transaction_type_text .= $this->transaction_transaction_type_text;

            $main_provider_org_text .= $this->transaction_provider_org_text;
            $main_provider_org_ref .= $this->transaction_provider_org_ref;
            $main_provider_org_provider_activity_id .= $this->transaction_provider_org_provider_activity_id;

            $main_receiver_org_text .= $this->transaction_receiver_org_text;
            $main_receiver_org_ref .= $this->transaction_receiver_org_ref;
            $main_receiver_org_receiver_activity_id .= $this->transaction_receiver_org_receiver_activity_id;

            $main_value_text .= $this->transaction_value_text;
            $main_value_currency .= $this->transaction_value_currency;
            $main_value_value_date .= $this->transaction_value_value_date;

            $main_description_text .= $this->transaction_description_text;
            $main_description_xml_lang .= $this->transaction_description_xml_lang;

            $main_transaction_date_iso_date .= $this->transaction_transaction_date_iso_date;
            $main_transaction_date_text .= $this->transaction_transaction_date_text;

            $main_flow_type_code .= $this->transaction_flow_type_code;
            $main_flow_type_text .= $this->transaction_flow_type_text;
            $main_flow_type_xml_lang .= $this->transaction_flow_type_xml_lang;

            $main_finance_type_code .= $this->transaction_finance_type_code;
            $main_finance_type_text .= $this->transaction_finance_type_text;
            $main_finance_type_xml_lang .= $this->transaction_finance_type_xml_lang;

            $main_aid_type_code .= $this->transaction_aid_type_code;
            $main_aid_type_text .= $this->transaction_aid_type_text;
            $main_aid_type_xml_lang .= $this->transaction_aid_type_xml_lang;
             
            $main_disbursement_channel_code .= $this->transaction_disbursement_channel_code;
            $main_disbursement_channel_text .= $this->transaction_disbursement_channel_text;

            $main_tied_status_code .= $this->transaction_tied_status_code;
            $main_tied_status_text .= $this->transaction_tied_status_text;
            $main_tied_status_xml_lang .= $this->transaction_tied_status_xml_lang;
        }
        $this->setTransaction_transaction_type_code($main_transaction_type_code);
        $this->setTransaction_transaction_type_text($main_transaction_type_text);

        $this->setTransaction_provider_org_text($main_provider_org_text);
        $this->setTransaction_provider_org_ref($main_provider_org_ref);
        $this->setTransaction_provider_org_provider_activity_id($main_provider_org_provider_activity_id);

        $this->setTransaction_receiver_org_text($main_receiver_org_text);
        $this->setTransaction_receiver_org_ref($main_receiver_org_ref);
        $this->setTransaction_receiver_org_receiver_activity_id($main_receiver_org_receiver_activity_id);

        $this->setTransaction_value_text($main_value_text);
        $this->setTransaction_value_currency($main_value_currency);
        $this->setTransaction_value_value_date($main_value_value_date);

        $this->setTransaction_description_text($main_description_text);
        $this->setTransaction_description_xml_lang($main_description_xml_lang);

        $this->setTransaction_transaction_date_iso_date($main_transaction_date_iso_date);
        $this->setTransaction_transaction_date_text($main_transaction_date_text);

        $this->setTransaction_flow_type_code($main_flow_type_code);
        $this->setTransaction_flow_type_text($main_flow_type_text);
        $this->setTransaction_flow_type_xml_lang($main_flow_type_xml_lang);

        $this->setTransaction_finance_type_code($main_finance_type_code);
        $this->setTransaction_finance_type_text($main_finance_type_text);
        $this->setTransaction_finance_type_xml_lang($main_finance_type_xml_lang);

        $this->setTransaction_aid_type_code($main_aid_type_code);
        $this->setTransaction_aid_type_text($main_aid_type_text);
        $this->setTransaction_aid_type_xml_lang($main_aid_type_xml_lang);

        $this->setTransaction_disbursement_channel_code($main_disbursement_channel_code);
        $this->setTransaction_disbursement_channel_text($main_disbursement_channel_text);

        $this->setTransaction_tied_status_code($main_tied_status_code);
        $this->setTransaction_tied_status_text($main_tied_status_text);
        $this->setTransaction_tied_status_xml_lang($main_tied_status_xml_lang);
    }

    public function activity_status_Validation($values)
    {
        $main_text = '';
        $main_code = '';
        $main_lang = '';
        foreach($values as $element)
        {
            $code = 0;
            $text = 0;
            $lang = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "code":
                                $status =  $this->code('policy_marker',$value);
                                $code = $status;
                                break;
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                        }
                    }
                }
            }
            $main_code = $main_code.$code;
            $main_text = $main_text.$text;
            $main_lang = $main_lang.$lang;
        }
        $this->setActivity_status_code($main_code);
        $this->setActivity_status_text($main_text);
        $this->setActivity_status_xml_lang($main_lang);
    }

    public function contact_info_Validation($values)
    {
        $main_organisation = "";
        $main_telephone = "";
        $main_email = "";
        $main_person_name = "";
        $main_mailing_address ="";
        foreach($values as $contact)
        {
            $organisation = 0;
            $telephone = 0;
            $email = 0;
            $person_name = 0;
            $mailing_address = 0;
            $element = (array)$element;
            foreach ($contact as $key=>$values)
            {
                 
                switch($key)
                {
                    case "organisation":
                        $status =  $this->text($values);
                        $organisation = $status;
                        break;
                    case "telephone":
                        $status = $this->text($values);
                        $telephone = $status;
                        break;
                    case "email":
                        $status = $this->email($value);
                        $email =$status;
                        break;
                    case "person-name":
                        $status = $this->text($values);
                        $person_name =$status;
                        break;
                    case "mailing-address":
                        $status = $this->text($values);
                        $mailing_address = $status;
                        break;
                }
            }
            $main_organisation .= $organisation;
            $main_telephone .= $telephone;
            $main_email .= $email;
            $main_person_name .= $person_name;
            $main_mailing_address .= $mailing_address;
        }
        $this->setContact_info_email_text($main_email);
        $this->setContact_info_mailing_address_text($main_mailing_address);
        $this->setContact_info_organisation_text($main_organisation);
        $this->setContact_info_person_name_text($main_person_name);
        $this->setContact_info_telephone_text($main_telephone);
    }

    public function activity_website_Validation($values)
    {
        $main_text = '';
        foreach($values as $element)
        {
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    /* foreach($values as $key=>$value)
                     {
                     switch($key)
                     {
                     case "code":
                     $status =  $this->code('policy_marker',$value);
                     $code = $status;
                     break;
                     case "xml:lang":
                     $status =  $this->language($value);
                     $lang = $status;
                     break;
                     }
                     }*/
                }
            }
            $main_text = $main_text.$text;
        }
        $this->setActivity_website_text($main_text);
    }

    public function related_activity_Validation($values)
    {
        $main_type = '';
        $main_ref = '';
        $main_text = '';
        $main_lang = '';
        foreach($values as $element)
        {
            $type = 0;
            $ref = 0;
            $lang = 0;
            $text = 0;
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "type":
                                $status =  $this->code('related_activity_type',$value);
                                $type = $status;
                                break;
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "ref":
                                $status =  $this->text($value);
                                $ref = $status;
                                break;
                        }
                    }
                }
            }
            $main_type = $main_text.$type;
            $main_ref = $main_ref.$ref;
            $main_lang = $main_lang.$lang;
            $main_text = $main_text.$text;
        }
        $this->setRelated_activity_ref($main_ref);
        $this->setRelated_activity_text($main_text);
        $this->setRelated_activity_type($main_type);
        $this->setRelated_activity_xml_lang($main_lang);
    }

    public function document_link_Validation($values)
    {
        $main_code = "";
        $main_title_text = "";
        $main_category_text = "";
        $main_url = "";
        $main_lang = "";
        $main_format = "";
        foreach($values as $links)
        {
            $code = 0;
            $category_text = 0;
            $title_text = 0;
            $url = 0;
            $lang = 0;
            $format = 0;
            $attributes = $links->attributes();
            $attributes = (array)$attributes;

            foreach ($attributes as $key=>$values)
            {
                if(!is_array($values)){
                    $status = $this->text($values);
                    $text = $status;

                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "url":
                                $status = $this->url($value);
                                $url = $status;
                                break;
                            case "xml:lang":
                                $status =  $this->language($value);
                                $lang = $status;
                                break;
                            case "format":
                                $status =  $this->code('file_format',$value);
                                $format = $status;
                                break;
                        }
                    }
                }
            }
            foreach($links as $link_key=>$link_elements)
            {
                $link_elements = (array)$link_elements;
                $element_name = preg_replace('/-/','_',$link_key);
                foreach ($link_elements as $key=>$values)
                {
                    if(!is_array($values)){
                        $status = $this->text($values);
                        if($element_name == 'category'){
                            $category_text = $status;
                        } else {
                            $title_text = $status;
                        }
                    }else{
                        foreach($values as $key=>$value)
                        {
                            switch($key)
                            {
                                case "code":
                                    $status =  $this->code('document_category',$value);
                                    $code = $status;
                                    break;
                            }
                        }
                    }
                }
            }
            $main_category_text .= $category_text;
            $main_code .= $code;
            $main_format .= $format;
            $main_lang .= $lang;
            $main_title_text .= $title_text;
            $main_url .= $url;
        }
        $this->setDocument_link_category_code($main_code);
        $this->setDocument_link_category_text($main_category_text);
        $this->setDocument_link_format($main_format);
        $this->setDocument_link_title_text($main_title_text);
        $this->setDocument_link_xml_lang($main_lang);
        $this->setDocument_link_url($main_url);
    }

    public function location_Validation($values)
    {
        $main_percentage = "";
        $main_location_type_code = "";
        $main_name_text = "";
        $main_name_xml_lang = "";
        $main_description_text = "";

        $main_administrative_country = "";
        $main_administrative_adm1 = "";
        $main_administrative_adm2 = "";
        $main_administrative_text = "";

        $main_coordinates_latitude = "";
        $main_coordinates_longitude = "";
        $main_coordinates_percision = "";

        $main_gazetter_entry_gazetter_ref = "";
        $main_gazetter_entry_text = "";
        foreach($values as $location)
        {
            $this->location_percentage = 0;
            $this->location_location_type_code = 0;
            $this->location_name_text = 0;
            $this->location_name_xml_lang = 0;
            $this->location_description_text = 0;

            $this->location_administrative_country = 0;
            $this->location_administrative_adm1 = 0;
            $this->location_administrative_adm2 = 0;
            $this->location_administrative_text = 0;

            $this->location_coordinates_latitude = 0;
            $this->location_coordinates_longitude = 0;
            $this->location_coordinates_percision = 0;

            $this->location_gazetteer_entry_gazetteer_ref = 0;
            $this->location_gazetteer_entry_text = 0;
            $attributes = $location->attributes();
            $attributes = (array)$attributes;
            foreach ($attributes as $key=>$values)
            {
                foreach($values as $key=>$value)
                {
                    switch($key)
                    {
                        case "percentage":
                            $status = $this->integer($value);
                            $this->location_percentage = $status;
                            break;
                    }

                }
            }
            foreach($location as $loc_key=>$loc_elements)
            {
                $loc_elements = (array)$loc_elements;
                $element_name = preg_replace('/-/','_',$loc_key);
                foreach ($loc_elements as $key=>$values)
                {
                    if(!is_array($values)){
                        $status = $this->text($values);
                        if($element_name == "gazetteer_entry")
                        {
                            $status = $this->integer($value);
                        }
                        $variable = "location_".$element_name."_text";
                        $this->$variable = $status;
                         
                    }else{
                        foreach($values as $key=>$value)
                        {
                            switch($key)
                            {
                                case "xml:lang":
                                    $status =  $this->language($value);
                                    $variable = "location_".$element_name."_xml_lang";
                                    $this->$variable = $status;
                                    break;
                                case "code":
                                    $status = $this->code($element_name,$value);
                                    $variable = "location_".$element_name."_code";
                                    $this->$variable = $status;
                                    break;
                                case "country":
                                    $status = $this->code('country',$value);
                                    $variable = "location_".$element_name."_country";
                                    $this->$variable = $status;
                                    break;
                                case "adm1":
                                    $status = $this->code('admin_1',$value);
                                    $variable = "location_".$element_name."_adm1";
                                    $this->$variable = $status;
                                    break;
                                case "adm2":
                                    $status = $this->code('admin_2',$value);
                                    $variable = "location_".$element_name."_adm2";
                                    $this->$variable = $status;
                                    break;
                                case "latitude":
                                    $status = $this->decimal($value);
                                    $variable = "location_".$element_name."_latitude";
                                    $this->$variable = $status;
                                    break;
                                case "longitude":
                                    $status = $this->decimal($value);
                                    $variable = "location_".$element_name."_longitude";
                                    $this->$variable = $status;
                                    break;
                                case "percision":
                                    $status = $this->code('percision',$value);
                                    $variable = "location_".$element_name."_precision";
                                    $this->$variable = $status;
                                    break;
                                case "gazetteer-ref":
                                    $status = $this->code('gazetteer_agency',$value);
                                    $variable = "location_".$element_name."_gazetteer_ref";
                                    $this->$variable = $status;
                                    break;
                            }
                        }
                    }
                }
            }
            $main_percentage .= $this->location_percentage;
            $main_location_type_code .= $this->location_location_type_code;
            $main_name_text .= $this->location_name_text;
            $main_name_xml_lang .= $this->location_name_xml_lang;
            $main_description_text .= $this->location_description_text;

            $main_administrative_country .= $this->location_administrative_country;
            $main_administrative_adm1 .= $this->location_administrative_adm1;
            $main_administrative_adm2 .= $this->location_administrative_adm2;
            $main_administrative_text .= $this->location_administrative_text;

            $main_coordinates_latitude .= $this->location_coordinates_latitude;
            $main_coordinates_longitude .= $this->location_coordinates_longitude;
            $main_coordinates_percision .= $this->location_coordinates_percision;

            $main_gazetter_entry_gazetter_ref .= $this->location_gazetteer_entry_gazetteer_ref;
            $main_gazetter_entry_text .= $this->location_gazetteer_entry_text;
        }
         
        $this->setLocation_percentage($main_percentage);
        $this->setLocation_location_type_code($main_location_type_code);
        $this->setLocation_name_text($main_name_text);
        $this->setLocation_name_xml_lang($main_name_xml_lang);
        $this->setLocation_description_text($main_description_text);

        $this->setLocation_administrative_country($main_administrative_country);
        $this->setLocation_administrative_adm1($main_administrative_adm1);
        $this->setLocation_administrative_adm2($main_administrative_adm2);
        $this->setLocation_administrative_text($main_administrative_text);

        $this->setLocation_coordinates_latitude($main_coordinates_latitude);
        $this->setLocation_coordinates_longitude($main_coordinates_longitude);
        $this->setLocation_coordinates_percision($main_coordinates_percision);

        $this->setLocation_gazetteer_entry_gazetteer_ref($main_gazetter_entry_gazetter_ref);
        $this->setLocation_gazetteer_entry_text($main_gazetter_entry_text);

    }
    //=============== Validators ==============================//

    public function organisation_identifier($value)
    {
        $value = (array)$value;
       /* $csvData = array();
        $csvArray = array('organisation_identifier_bilateral',
                         'organisation_identifier_ingo',
                         'organisation_identifier_multilateral');
        foreach($csvArray as $eachCsv){
            $data = $this->csvReader($eachCsv, 0);
            unset($data[0]);
            $csvData = array_merge($csvData, $data);
        }*/
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){
                /*if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }*/
                $status .= '3';
            }
        }else{
            /*if(in_array($value, $csvData)){
                $status = '3';
            }*/
            $status = '3';
        }
        return $status;
    }

    public function organisation_type($value)
    {
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader('organisation_type', 0);
        unset($csvData[0]);
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
         
    }

    public function organisation_role($value)
    {
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader('organisation_role', 0);
        unset($csvData[0]);
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
    }

    public function code($type, $value)
    {
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader($type, 0);
        unset($csvData[0]);
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
    }

    public function text($value)
    {
        $value = (array)$value;
        $status = '3';
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){
                $status = '3';
            }
        }
         
        return $status;
         
    }

    public function integer($value)
    {
        $value = (array)$value;
        $status = '1';
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){
                if(!filter_var($eachValue, FILTER_VALIDATE_INT)){
                    $status = "1";
                }else{
                    $status = "3";
                }
            }
        }
        else{
            if(!filter_var($value, FILTER_VALIDATE_INT)){
                $status = "1";
            }else{
                $status = "3";
            }
        }
        return $status;
    }

    public function url($value)
    {

    	$urlregex = "^((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
    	if (eregi($urlregex, $value)) {
        	$status = "3";
        } else {
        	$status = "1";
        }
        return $status;
    }

    public function dateTime($value, $time = False)
    {
        $value = (array)$value;
        $status = '1';
        if(is_array($value)){
            foreach($value as $v){
                if($time){
                    if(preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}/' , $v ) == 1){
                        $status = '3';
                    }

                }
                else{
                    if(preg_match( '/^(\d{1,2})-(\d{1,2})-((?:\d{2}){1,2})$/' , $v ) == 1){
                        $status = '3';
                    }
                }
            }
        }

        return $status;
    }

    public function decimal($value)
    {
        $value = (array)$value;
        $status = '1';
        if(is_array($value)){
            $status = '';
            foreach($value as $v){
                if(preg_match('/^\s*-?\d{1,3}\.\d+,\s*\d{1,3}\.\d+\s*$/', $v)){
                    $status .= '3';
                }
                else{
                    $status .='1';
                }
            }
        }
        else{
            if(preg_match('/^\s*-?\d{1,3}\.\d+,\s*\d{1,3}\.\d+\s*$/', $v)){
                $status .= '3';
            }
        }
        return $status;
    }

    public function email($value)
    {
        $value = (array)$value;
        $status = '1';
        if(is_array($value)){
            $status = '';
            foreach($value as $key => $v){
                if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }
        return $status;
    }

    public function hierarchy($value)
    {
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader('activity_hierarchy', 0);
        unset($csvData[0]);
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
    }

    public function language($value)
    {
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader('language', 0);
        unset($csvData[0]);
        if(is_array($value)){

            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
    }

    public function currency($value){
        $value = (array)$value;
        $status = '1';
        $csvData = $this->csvReader('currency', 0);
        unset($csvData[0]);
        if(is_array($value)){
            $status = '';
            foreach($value as $eachValue){

                if(in_array($eachValue, $csvData)){
                    $status .= '3';
                }
                else{
                    $status .= '1';
                }
            }
        }else{
            if(in_array($value, $csvData)){
                $status = '3';
            }
        }
        return $status;
    }

    /**
     *  This function reads the csv file specified by "$filename" and returns the array of content in $column
     * @param $filename
     * @param $column
     * @return array
     */
    public function csvReader($filename, $column)
    {
        $filePath = $this->getFolderPath().$filename.".csv";
        if(($handle = fopen($filePath, "r")) !== FALSE){
            while(($data = fgetcsv($handle))!== FALSE){
                $columnArray[] = trim($data[$column]);
            }
        }
        fclose($handle);
        return $columnArray;
    }

}