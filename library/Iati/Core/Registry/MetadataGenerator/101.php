<?php

class Iati_Core_Registry_MetadataGenerator_101
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
            "author_email"=>$publisherInfo['email'],
            "resources" => array(
                                array(
                                    "url"=>$file->get('url'),
                                    "format"=>"IATI-XML"
                                    )
                                ),
            "extras"=>array(
                    "filetype" => "activity",
                    "country" => $file->get('countryCode'),
                    "activity_period-from" => '',
                    "activity_period-to" => '',
                    "data_updated"=> date('Y-m-d',strtotime($file->get('dataUpdatedDatetime'))),
                    "record_updated" => date('Y-m-d'),
                    "activity_count" => $file->get('activitCount'),
                    "verified" => "no",
                    "language" => "en",
                    ),
            "groups" => array($publisherId)
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
            "resources" => array(
                                array(
                                    "url"=>$file->get('url'),
                                    "format"=>"IATI-XML"
                                    )
                                ),
            "extras"=>array(
                    "filetype" => "organisation",
                    "country" => '',
                    "activity_period-from" => '',
                    "activity_period-to" => '',
                    "data_updated"=> date('Y-m-d',strtotime($file->get('dataUpdatedDatetime'))),
                    "record_updated" => date('Y-m-d'),
                    "activity_count" => '',
                    "verified" => "no",
                    "language" => "en",
                    ),
            "groups" => array($publisherId)
        );
        $jsonData =  json_encode($data);
        return$jsonData;
    }
}