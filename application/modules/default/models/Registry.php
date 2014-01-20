<?php

class Model_Registry 
{
    /**
     * @todo remove $registryInfo parameter and fetch internally.
     */
    public static function publish($files , $publisherId , $registryInfo , $organisation = false)
    {
        // Set publihser name for file title
        $model = new Model_Wep();
        $twitterModel = new Model_Twitter();

        $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $publisherId);
        $defaults = $defaultFieldsValues->getDefaultFields();

        $publisherName = $defaults['reporting_org'];
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $email = $identity->email;
        $publisherInfo = array('name' => $publisherName , 'email' => $email);
        
        $reg = new Iati_Core_Registry($registryInfo->publisher_id , $registryInfo->api_key , $publisherInfo);
        $reg->setVersion(Zend_Registry::getInstance()->config->registry->version);

        foreach($files as $file){
            $fileObj = new Iati_Core_Registry_File();
            $fileObj->setData($file);
            if($organisation) $fileObj->setIsOrganisationData();
            $reg->publishToRegistry($fileObj);
        }

        if($reg->getErrors()){
            return array('error' => $reg->getErrors());
        }

        //Tweet about the publish file from @aidstream
        $twitterModel->sendTweet();
        return array('error' => false);
    }
    
    public static function getCountryNamefromCode($code)
    {
        $db = new Model_Wep();
        $country = $db->getRowsByFields('Country' , 'Code' , $code);
        foreach($country as $countryData){
            if($countryData['lang_id'] == 1){
                return ucfirst(strtolower($countryData['Name']));
            }
        }
        return false;
    }
    
     public static function getRegionNamefromCode($code)
    {
        $db = new Model_Wep();
        $region = $db->getRowsByFields('Region' , 'Code' , $code);
        foreach($region as $regionData){
            if($regionData['lang_id'] == 1){
                return ucfirst(strtolower($regionData['Name']));
            }
        }
        return false;
    }
}