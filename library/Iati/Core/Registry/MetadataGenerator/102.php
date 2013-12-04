<?php

class Iati_Core_Registry_MetadataGenerator_102
{
    public function prepareRegistryInputJson($publisherId , $publisherInfo , $file)
    {
        $publisherName = $publisherInfo['name'];
        $filename =explode('.',$file->get('name'));
        $title = ($publisherName)? $publisherName . ' Activity File' : 'Activity File ' .$filename[0];
        if ($file->get('countryCode') && $publisherName){
            $countryName = $file->get('countryName');
            if($countryName){
                $title .= " for ".$countryName;
            }
        }


        $data = array(
            "name"=>$filename[0],
            "title"=>$title,
            "author_email"=> $publisherInfo['email'],
            "owner_org" => $publisherId,
            "license_id" => "other-open",
            "resources" => array(
                                array(
                                    "url"=>$file->get('url'),
                                    "mimetype" => "application/xml",
                                    "format"=>"IATI-XML"
                                    )
                                ),
            "extras"=>array(
                        array('key' => "filetype"  , 'value' => "activity"),
                        array('key' => "country" , 'value' => $file->get('countryCode')),
                        array('key' => "activity_period-from" , 'value'  => ''),
                        array('key' => "activity_period-to" , 'value' => ''),
                        array('key' => "data_updated" , 'value' => date('Y-m-d',strtotime($file->get('dataUpdatedDatetime')))),
                        array('key' => "record_updated" , 'value' => date('Y-m-d')),
                        array('key' => "activity_count" , 'value' => $file->get('activityCount')),
                        array('key' => "verified" , 'value' => "no"),
                        array('key' => "language" , 'value' => "en"),
                    )
        );
        $jsonData =  json_encode($data);
        return $jsonData;
    }
   
    public function prepareOrganisationRegistryInputJson($publisherId , $publisherInfo , $file)
    {
        $filename =explode('.' , $file->get('name'));
        $title = ($publisherInfo['name']) ? $publisherInfo['name']. ' Organisation File' : 'Organisation File '.$filename[0];

        $data = array(
            "name"=>$filename[0],
            "title"=>$title,
            "author_email"=>$publisherInfo['email'],
            "owner_org" => $publisherId,
            "license_id" => "other-open",
            "resources" => array(
                                array(
                                    "url"=>$file->get('url'),
                                    "format"=>"IATI-XML",
                                        "mimetype" => "application/xml",
                                    )
                                ),
            "extras"=>array(
                        array('key' => "filetype"  , 'value' => "organisation"),
                        array('key' => "country" , 'value' => ''),
                        array('key' => "activity_period-from" , 'value'  => ''),
                        array('key' => "activity_period-to" , 'value' => ''),
                        array('key' => "data_updated" , 'value' => date('Y-m-d',strtotime($file->get('dataUpdatedDatetime')))),
                        array('key' => "record_updated" , 'value' => date('Y-m-d')),
                        array('key' => "activity_count" , 'value' => ''),
                        array('key' => "verified" , 'value' => "no"),
                        array('key' => "language" , 'value' => "en")
                    )
        );
        $jsonData =  json_encode($data);
        return$jsonData;
    }
}