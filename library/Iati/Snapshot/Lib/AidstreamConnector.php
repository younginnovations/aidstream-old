<?php
class AidstreamConnector{
    protected $connection;
    protected $tableName;
    
    public function __construct($user = 'root' , $password = 'yipl123' , $dbname= 'iati_aims_db' , $tablename = 'registry_published_data')
    {
        $this->tableName = $tablename;
        $con = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $password);
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
        foreach( $rows as $file){
            $metadata = unserialize($file['response']);
            if($metadata->extras->activity_count > 0 || !preg_match('/activities/', $file['filename'])){
                $urls[$file['filename']] = $metadata->download_url;
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