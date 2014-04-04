<?php
include_once("Config.php");
class AidstreamConnector{
    protected $connection;
    protected $tableName;

    public function __construct()
    {
        $this->tableName = DB_TABLE;
        $con = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection = $con;
    }

    public function getFileUrls($lastDate = '')
    {
        $activityLastUpdate = strtotime($lastDate);

        $stmt = $this->connection->prepare("SELECT filename , response FROM  `{$this->tableName}` ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $urls = array();
        foreach($rows as $file){
            $metadata = unserialize($file['response']);
            // Responses
            if (isset($metadata->download_url)) {   // CKAN 1.0               
                $urls[$file['filename']] = $metadata->download_url;
            } elseif (isset($metadata->resources[0]->url)) {    // CKAN 2.0
                $urls[$file['filename']] = $metadata->$metadata->resources[0]->url;
            } elseif (isset($metadata->result->resources[0]->url)) {    // CKAN 3.0
                $urls[$file['filename']] = $metadata->result->resources[0]->url;
            }
        }
        return $urls;
    }

    public function getNames($eleName , $code)
    {
        switch($eleName){
            case 'sector' :
                $tablename = 'Sector';
                break;
            case 'activity_status' :
                $tablename = 'ActivityStatus';
                break;
            case 'recipient_region':
                $tablename = 'Region';
                break;
            case 'recipient_country':
                $tablename = 'Country';
                break;
            default : return false;
        }
        $stmt = $this->connection->prepare("SELECT * FROM `{$tablename}` where Code = '{$code}' and lang_id = '1'");
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($rows)){
            return $rows['Name'];
        } else {
            return '';
        }
    }
}