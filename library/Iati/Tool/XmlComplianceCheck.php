<?php
class Iati_Tool_XmlComplianceCheck
{
    protected $xml;
    protected $parsedXml;
    //    protected $csv;
    protected $xmlArray;
    protected $folderPath;
    protected $destination;
    protected $filename;
    protected $resultMatrix;

    public function setXml($xml)
    {
        $this->xml = $xml;
    }

    public function getXml()
    {
        return $this->xml;
    }

    public function setParsedXml($object)
    {
        $this->parsedXml = $object;
    }

    public function getParsedXml()
    {
        return $this->parsedXml;
    }

    /*public function setCsv($path)
     {
     $this->csv = $path;
     }

     public function getCsv()
     {
     return $this->csv;
     }*/

    public function setXmlArray($xmlArray)
    {
        $this->xmlArray = $xmlArray;
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

    public function setFileName($filename)
    {
        $this->filename = $filename;
    }

    public function getFileName()
    {
        return $this->filename;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function xmlParser()
    {
        $specialElement = array('transaction');
        $toCheck = array(
                        'iati-activity','reporting-org','participating-org',
                        'recipient-country', 'recipient-region', 'collaboration-type',
                        'default-flow-type', 'default-aid-type', 'default-finance-type',
                        'iati-identifier','other-identifier','title','description',
                        'sector','activity-date','default-tied-status','policy-marker',
                        'transaction','activity-status','contact-info','activity-website',
                        'related-activity','document-link','location'
                 );
        $path_parts = pathinfo($this->getXml());
        $this->setFileName($path_parts['filename']);
        $xmlObject = simplexml_load_file($this->getXml());
        $namespaces = $xmlObject->getNameSpaces(true);
        $ir = $xmlObject->children($namespaces['ir']);
        $xml = $ir->{'registry-record'}->children();
        $xml = $xmlObject; 
        $row = 0;
        $mainArray = array();
        foreach($xml as $key => $element){
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                foreach($xm as $k1 => $value){
                    foreach($value as $k=>$v){
                        $mainArray[$row][$key]['xml:lang'] = $v;
                    }
                }
            }
            $attr = $element->attributes();
            foreach ($attr as $k => $attribute) {
                $mainArray[$row][$key][$k] = $attribute;
            }
            foreach($element as $eachKey=>$eachElement){
                if(!in_array($eachKey, $toCheck)){
                    continue;
                }
                $namespaces = $eachElement->getNameSpaces(true);
                if($namespaces){
                    $xm = $eachElement->children($namespaces['xml']);
                    $xm = (array)$xm;
                    //                                var_dump($xm);exit();
                    foreach($xm as $key => $value){
                        foreach($value as $k=>$v){
                            $eachElement['xml:lang'] = $v;
                        }
                    }
                }
                 
                $mainArray[$row][$eachKey][] = $eachElement;
            }$row++;
    
        }
        $this->setXmlArray($mainArray);
    }

    public function validate()
    {
        $matrixArray = array();
        $xml_output = $this->getXmlArray();
        $tempArray = $xml_output[0]['reporting-org'][0];
        $referenceReportingOrg = (array)$tempArray;
        foreach($xml_output as $key => $activity)
        {
            $Activity_obj  = new Iati_Tool_MatrixGenerator();
            $Activity_obj->setReference_reporting_org($referenceReportingOrg[0]);
            $Activity_obj->setFolderPath($this->getFolderPath());
            $Activity_obj->validateActivity($activity);
            $outputArray[$key] = $Activity_obj;
        }
       $path =  $this->matrixGenerator($outputArray);
       return $path;
    }

    public function matrixGenerator($outputArray)
    {
        $matrixArray = array(
        				'heading'=>array('Element'),
                        'iati_activity' => array('iati-activity'),
                        'iati_activity_xml_lang' => array('iati-activity/@xml:lang'),
                        'iati_activity_default_currency' => array('iati-activity/@default-currency'),
                        'iati_activity_hierarchy' => array('iati-activity/@hierarchy'),
                        'iati_activity_last_updated_datetime' =>array('iati-activity/@last-updated-datetime'),
                        'reporting_org' => array('reporting-org'),
                        'reporting_org_ref' => array('reporting-org/@ref'),
                        'reporting_org_type' => array('reporting-org/@type'),
                        'reporting_org_text' => array('reporting-org/text()'),
                        'reporting_org_xml_lang' => array('reporting-org/@xml:lang'),
                        'participating_org' => array('participating-org'),
                        'participating_org_role' => array('participating-org/@role'),
                        'participating_org_ref' => array('participating-org/@ref'),
                        'participating_org_type' => array('participating-org/@type'),
                        'participating_org_text' => array('participating-org/text()'),
                        'participating_org_xml_lang' => array('participating-org/@xml:lang'),
                        'recipient_country' => array('recipient-country'),
                        'recipient_country_code' => array('recipient-country/@code'),
                        'recipient_country_text' => array('recipient-country/text()'),
                        'recipient_country_percentage' => array('recipient-country/@percentage'),
                        'recipient_country_xml_lang' => array('recipient-country/@xml:lang'),
                        'recipient_region' => array('recipient-region'),
                        'recipient_region_code'=> array('recipient-region/@code'),
                        'recipient_region_text' => array('recipient-region/text()'),
                        'recipient_region_percentage' => array('recipient-region/@percentage'),
                        'recipient_region_xml_lang' => array('recipient-region/@xml:lang'),
                        'collaboration_type' => array('collaboration-type'),
                        'collaboration_type_code' => array('collaboration-type/@code'),
                        'collaboration_type_text' => array('collaboration-type/text()'),
                        'collaboration_type_xml_lang' => array('collaboration-type/@xml:lang'),
                        'default_flow_type' => array('default-flow-type'),
                        'default_flow_type_code' => array('default-flow-type/@code'),
                        'default_flow_type_text' => array('default-flow-type/text()'),
                        'default_flow_type_xml_lang' => array('default-flow-type/@xml:lang'),
                        'default_aid_type' => array('default-aid-type'),
                        'default_aid_type_code' => array('default-aid-type/@code'),
                        'default_aid_type_text' => array('default-aid-type/text()'),
                        'default_aid_type_xml_lang' => array('default-aid-type/@xml:lang'),
                        'default_finance_type' => array('default-finance-type'),
                        'default_finance_type_code' => array('default-finance-type/@code'),
                        'default_finance_type_text' => array('default-finance-type/text()'),
                        'default_finance_type_xml_lang' => array('default-finance-type/@xml:lang'),
                        'iati_identifier' => array('iati-identifier'),
                        'iati_identifier_text' => array('iati-identifier/text()'),
                        'other_identifier' => array('other-identifier'),
                        'other_identifier_owner_ref' => array('other-identifier@owner-ref'),
                        'other_identifier_owner_name' => array('other-identifier/@owner-name'),
                        'other_identifier_text' => array('other-identifier/text()'),
                        'title' => array('title'),
                        'title_text' => array('title/text()'),
                        'title_xml_lang' => array('title/@xml:lang'),
                        'description' => array('description'),
                        'description_type' => array('description/@type'),
                        'description_text' => array('description/text()'),
                        'description_xml_lang' => array('description/@xml:lang'),
                        'sector' => array('sector'),
                        'sector_vocabulary' => array('sector/@vocabulary'),
                        'sector_code' => array('sector/@code'),
                        'sector_text' => array('sector/text()'),
                        'sector_percentage' => array('sector/@percentage'),
                        'sector_xml_lang' => array('sector/@xml:lang'),
                        'activity_date' => array('activity-date'),
                        'activity_date_type' => array('activity-date/@type'),
                        'activity_date_iso_date' => array('activity-date/@iso-date'),
                        'activity_date_text' => array('activity-date/text()'),
                        'activity_date_xml_lang' => array('activity-date/@xml:lang'),
                        'default_tied_status' => array('default-tied-status'),
                        'default_tied_status_code' => array('default-tied-status/@code'),
                        'default_tied_status_text' => array('default-tied-status/text()'),
                        'default_tied_status_xml_lang' => array('default-tied-status/@xml:lang'),
                        'policy_marker' => array('policy-marker'),
                        'policy_marker_significance' => array('policy-marker/@significance'),
                        'policy_marker_vocabulary' => array('policy-marker/@vocabulary'),
                        'policy_marker_code' => array('policy-marker/@code'),
                        'policy_marker_text' => array(' policy-marker/text()'),
                        'policy_marker_xml_lang' => array('policy-marker/@xml:lang'),
                        'transaction' => array('transaction'),
                        'transaction_transaction_type' => array('transaction/transaction-type'),
                        'transaction_transaction_type_code' => array('transaction/transaction-type/@code'),
                        'transaction_transaction_type_text' => array('transaction/transaction-type/text()'),
                        'transaction_provider_org' => array('transaction/provider-org'),
                        'transaction_provider_org_text' => array('transaction/provider-org/text()'),
                        'transaction_provider_org_ref' => array('transaction/provider-org/@ref'),
                        'transaction_provider_org_provider_activity_id' => array('transaction/provider-org/@provider-activity-id'),
                        'transaction_receiver_org' => array('transaction/receiver-org'),
                        'transaction_receiver_org_text' => array('transaction/receiver-org/text()'),
                        'transaction_receiver_org_ref' => array('transaction/receiver-org/@ref'),
                        'transaction_receiver_org_receiver_activity_id' => array('transaction/receiver-org/@receiver-activity-id'),
                        'transaction_value' => array('transaction/value'),
                        'transaction_value_text' => array('transaction/value/text()'),
                        'transaction_value_currency' => array('transaction/value/@currency'),
                        'transaction_value_value_date' => array('transaction/value/@value-date'),
                        'transaction_description' => array('transaction/description'),
                        'transaction_description_text' => array('transaction/description/text()'),
                        'transaction_description_xml_lang' => array('transaction/description/@xml:lang'),
                        'transaction_transaction_date' => array('transaction/transaction-date'),
                        'transaction_transaction_date_iso_date' => array('transaction/transaction-date/@iso-date'),
                        'transaction_transaction_date_text' => array('transaction/transaction-date/text()'),
                        'transaction_flow_type' => array('transaction/flow-type'),
                        'transaction_flow_type_code' => array('transaction/flow-type/@code'),
                        'transaction_flow_type_text' => array('transaction/flow-type/text()'),
                        'transaction_flow_type_xml_lang' => array('transaction/flow-type/@xml:lang'),
                        'transaction_finance_type' => array('transaction/finance-type'),
                        'transaction_finance_type_code' => array('transaction/finance-type/@code'),
                        'transaction_finance_type_text' => array('transaction/finance-type/text()'),
                        'transaction_finance_type_xml_lang' => array('transaction/finance-type/@xml:lang'),
                        'transaction_aid_type' => array('transaction/aid-type'),
                        'transaction_aid_type_code' => array('transaction/aid-type/@code'),
                        'transaction_aid_type_text' => array('transaction/aid-type/text()'),
                        'transaction_aid_type_xml_lang' => array('transaction/aid-type/@xml:lang'),
                        'transaction_disbursement_channel' => array('transaction/disbursement-channel'),
                        'transaction_disbursement_channel_code' => array('transaction/disbursement-channel/@code'),
                        'transaction_disbursement_channel_text' => array('transaction/disbursement-channel/text()'),
                        'transaction_tied_status' => array('transaction/tied-status'),
                        'transaction_tied_status_code' => array('transaction/tied-status/@code'),
                        'transaction_tied_status_text'=> array('transaction/tied-status/text()'),
                        'transaction_tied_status_xml_lang' => array('transaction/tied-status/@xml:lang'),
                        'activity_status' => array('activity-status'),
                        'activity_status_code' => array('activity-status/@code'),
                        'activity_status_text' => array('activity-status/@text'),
                        'activity_status_xml_lang' => array('activity-status/@xml:lang'),
                        'contact_info' => array('contact-info'),
                        'contact_info_organisation_text' => array('contact-info/organisation/text()'),
                        'contact_info_person_name_text' => array('contact-info/person-name/text()'),
                        'contact_info_telephone_text' => array('contact-info/telephone/text()'),
                        'contact_info_email_text' => array('contact-info/email/text()'),
                        'contact_info_mailing_address_text' => array('contact-info/mailing-address/text()'),
                        'activity_website' => array('activity-website'),
                        'activity_website_text' => array('activity-website/text()'),
                        'related_activity' => array('related-activity'),
                        'related_activity_type' => array('related-activity/@type'),
                        'related_activity_ref' => array('related-activity/@ref'),
                        'related_activity_text' => array('related-activity/text()'),
                        'related_activity_xml_lang' => array('related-activity/@xml:lang'),
                        'document_link' => array('document-link'),
                        'document_link_url' => array('document-link/@url'),
                        'document_link_format' => array('document-link/@format'),
                        'document_link_xml_lang' => array('document-link/@xml:lang'),
                        'document_link_category' => array('document-link/category'),
                        'document_link_category_code' => array('document-link/category/@code'),
                        'document_link_category_text' => array('document-link/category/text()'),
                        'document_link_title_text' => array('document-link/title/text()'),
                        'location' => array('location'),
                        'location_percentage' => array('location/@percentage'),
                        'location_location_type_code' => array('location/location-type/@code'),
                        'location_name_text' => array('location/name/text()'),
                        'location_name_xml_lang' => array('location/name/@xml:lang'),
                        'location_description_text' => array('location/description/text()'),
                        'location_administrative' => array('location/administrative'),
                        'location_administrative_country' => array('location/administrative/@country'),
                        'location_administrative_adm1' => array('location/administrative/@adm1'),
                        'location_administrative_adm2' => array('location/administrative/@adm2'),
                        'location_administrative_text' => array('location/administrative/text()'),
                        'location_coordinates' => array('location/coordinates'),
                        'location_coordinates_latitude' => array('location/coordinates/@latitude'),
                        'location_coordinates_longitude' => array('location/coordinates/@longitude'),
                        'location_coordinates_percision' => array('location/coordinates/@precision'),
                        'location_gazetteer_entry' => array('location/gazetteer-entry'),
                        'location_gazetteer_entry_gazetteer_ref' => array('location/gazetteer-entry/@gazetteer-ref'),
                        'location_gazetteer_entry_text' => array('location/gazetteer-entry/text()'),
        				'reporting_org_status' => array(''),


        );

        $matrixKeys = array_keys($matrixArray);
        $counter = 1;
        foreach($outputArray as $object){
            foreach($matrixKeys as $key => $value){
				if($value == "heading")
				{
					$matrixArray[$value][] = "Iati-Id ".$object->getIdentifier();
				} elseif($value == "reporting_org_status"){
					$matrixArray[$value][]= $object->getText_reporting_org();
				}else {
					$a = 'get'. ucfirst($value);
                	$matrixArray[$value][] =  $object->$a();
				}
            }
        }
        $path = $this->getDestination().$this->getFilename().'.csv';
        if(file_exists($path)){
            unlink($path);
        }
        $fp= fopen($path,'a');
        foreach($matrixArray as $key => $array){
            fputcsv($fp,$array);
        }

        fclose($fp);
        return $path;
    }


    /*** Element Handles ***/

    public function iati_activity($object)
    {

        $iati_activity = array('xml:lang','default-currency','hierarchy','last-updated-datetime');

       if(is_object($object) || is_array($object)){
            foreach($object as $key=>$value){

                if(is_object($value) || is_array($value)){
                    foreach((array)$value as $k=>$v){
                        $newArray[$key] = $v;
                    }
                }
            }
            $statusArray = array();

        }


        $arrayKeys = array_keys($newArray);
         

        return $newArray;
    }

    public function reporting_org($object){
        //                        print_r((array)$object);exit();
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        //        print_r($newArray);exit();
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }
        return $statusArray;
    }

    public function participating_org($object){
        $functionName = 'participating-org';
        $validator = array('role'=>'organisation_role',
                           'ref'=>array('organisation_identifier_bilateral','organisation_identifier_indigo','organisation_identifier_multilateral'),
                           'type'=>'organisation_type',
                           'text'=>'',
                           'xml:lang'=>'language');
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        $rows = 0;
        foreach($newArray as $key=>$element){

            foreach($element['@attributes'] as $k=>$v){
                $statusArray[$rows][$k] = $v;
            }
            $statusArray[$rows]['text'] = $element[0];
            $rows++;
        }//print_r($statusArray);exit();
        //        foreach($)
        /*$statusArray = array();
        foreach($newArray as $key=>$element){
        if(is_array($element)){
        foreach($element as $k=>$value){
        if($k == 0){
        $k = 'text';
        }
        if(is_array($value)){
        foreach($value as $a=>$v){
        $statusArray[$a][] = $v;
        }
        }
        else{
        $statusArray[$k][] = $value;
        }
        }
        }
        else{

        }
        }*///print_r($statusArray);exit();
        /* $statusKeys = array_keys($statusArray);
        //sorting

        foreach($validator as $key=>$value){
        $status = '';
        if(in_array($key, $statusKeys)){
        if($key == 'ref'){
        //                    print 'ref';
        }elseif($key == 'text'){
        //                    print 'text';
        }else{
        //                    print $value;
        $csvArray = $this->csvReader($value, 0);
        unset($csvArray[0]);
        //                    print_r($csvArray);

        //                    var_dump($csvArray);
         
        foreach($statusArray[$key] as $eachKey => $eachValue){
        print $eachValue;print "\n";
        var_dump(in_array($eachValue, $csvArray));exit();
        //                        print $eachValue;
        //                        print_r($csvArray);
        if(in_array($eachValue, $csvArray)){
        //                            print $eachValue;
        $status .= '3';
        }else{
        //                            print $eachValue;
        $status .= '1';
        }print $status;
        }
        }

        $finalArray[$key] = $statusArray[$key];
        }
        else{
        $finalArray[$key] = 0;
        }
        }
        //        print_r($statusArray);
        exit();*/
        return $statusArray;
    }

    public function recipient_country($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function recipient_region($object)
    {

    }

    public function collaboration_type($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function default_flow_type($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function default_aid_type($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function default_finance_type($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function iati_identifier($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function other_identifier($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function title($object)
    {

    }

    public function description($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function sector($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function activity_date($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }

    public function default_tied_status($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($statusArray);exit();
        return $statusArray;
    }


    public function policy_marker($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }//print_r($newArray);exit();
        return $statusArray;
    }

    public function transaction($values)
    {
        $newArray = array();
        foreach((array)$values as $key=>$element){
            foreach($element as $k=>$a){
                $attr = $a->attributes();
                $newArray[$k] = $attr;
                $newArray[$k] = $a;
            }
        }
        /*$statusArray = array();
         foreach($newArray as $key =>$element){
         $element = (array)$element;
         if(is_array($element)){
         foreach($element as $k=>$v){
         // print $v;
         if($k == '0'){
         $k = 'text';
         }
         if(is_array($v) || is_object($v)){
         $v = (array)$v;
         foreach($v as $eachKey => $eachValue){
         $statusArray[$key][$eachKey] = $eachValue;
         }
         }
         else{
         $statusArray[$key][$k] = $v;
         }
         }
         }
         }//print_r($statusArray);exit();*/
        return $statusArray;

    }

    public function activity_status($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }
        //        print_r($statusArray);exit();
        return $statusArray;
    }

    public function contact_info($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        //        print_r($newArray);
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == '0'){
                        $k = 'text';
                    }
                    if(is_array($value)){print "ggg";
                    foreach($value as $a=>$v){
                        $statusArray[$a][] = $v;
                    }
                    }
                    else{
                        $statusArray[$k] = $value;
                    }
                }
            }
            else{

            }
        }
        //        print_r($statusArray);exit();
        return $statusArray;
    }

    public function activity_website($object)
    {

    }

    public function related_activity($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        $statusArray = array();
        foreach($newArray as $key=>$element){
            if(is_array($element)){
                foreach($element as $k=>$value){
                    if($k == 0){
                        $k = 'text';
                    }
                    if(is_array($value)){
                        foreach($value as $a=>$v){
                            $statusArray[$a][] = $v;
                        }
                    }
                    else{
                        $statusArray[$k][] = $value;
                    }
                }
            }
            else{

            }
        }
        //        print_r($statusArray);exit();
        return $statusArray;
    }

    public function document_link($object)
    {

    }

    public function location($object)
    {
        //@todo needs special treatment
    }

    public function legacy_data($object)
    {
        $newArray = array();
        foreach((array)$object as $key=>$element){
            $newArray[$key] = (array)$element;
        }
        return $statusArray;
    }

    /*** end of element handles ***/


    /**
     *  This function reads the csv file specified by "$filename" and returns the array of content in $column
     * @param $filename
     * @param $column
     * @return array
     */
    public function csvReader($filename, $column)
    {
        $this->setFolderPath('/home/manisha/Documents/iati_docs/standard/');// @todo remove this code
        $filePath = $this->getFolderPath().$filename.".csv";
        if(($handle = fopen($filePath, "r")) !== FALSE){
            while(($data = fgetcsv($handle))!== FALSE){
                $columnArray[] = trim($data[$column]);
            }
        }
        fclose($handle);
        return $columnArray;
    }

    public function readCsv()
    {
        $csvSource = "/home/manisha/Documents/iati docs/standard/currency.csv";
        if (($handle = fopen($csvSource, "r")) !== FALSE) {
            while (($data = fgetcsv($handle))!== FALSE) {
                $columnArray[] = $data[0];

                //                 print_r($data);
                //                 exit();
            }
            print_r($matrixArray);
            exit();
        }
        fclose($handle);
    }



}