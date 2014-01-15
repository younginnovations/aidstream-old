<?php
class AidstreamConnector extends Zend_Db_Table_Abstract{
    
    protected $_name = 'registry_published_data';
    
    public function getFileUrls($lastDate = '')
    {
        /*        
            $stmt = $this->connection->prepare("SELECT filename , response FROM  `{$this->tableName}` ");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        */

        $activityLastUpdate = strtotime($lastDate);
        $stmt = $this->select()
                    ->from($this, array('filename', 'response'));
        $rows = $this->fetchAll($stmt);

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

        /*
            $stmt = $this->connection->prepare("SELECT * FROM `{$tablename}` where Code = '{$code}' and lang_id = '1'");
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        */

        $stmt = $this->select()
                ->where('Code = ?', $code)
                ->where('lang_id = 1');
        $rows = $this->fetchRow($stmt);
        if(!empty($rows)){
            return $rows['Name'];
        } else {
            return '';
        }
    }
}