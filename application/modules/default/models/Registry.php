<?php

class Model_Registry extends Zend_Db_Table_Abstract
{
    /**
     * @todo remove $registryInfo parameter and fetch internally.
     */
    public static function publish($files , $publisherId , $registryInfo)
    {
        $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key);
        
        // Set publihser name for file title
        $model = new Model_Wep();

        $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $publisherId);
        $defaults = $defaultFieldsValues->getDefaultFields();

        $publisherName = $defaults['reporting_org'];
        $reg->setPublisherName($publisherName);

        foreach($files as $file){
            $reg->prepareRegistryData($file);
            $reg->publishToRegistry();
        }

        if($reg->getErrors()){
            return array('error' => $reg->getErrors());
        }
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