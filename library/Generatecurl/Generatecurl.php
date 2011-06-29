<?php
class Generatecurl {
    protected $url;
    protected $postvalues;
    function __construct() {
        //$this->action = "POST";   
    }
    
    /**
     * to get the url that is extracting it
     * @return url
     */
    public function setUrl($url) {
        $this->url = $url ;
    }   
    
    
    /**
     * to get the postvar that is extracting it
     * 
     * @param $map
     * @return map
     */
    public function setPostvar($postvalues) {
        $this->postvalues = $postvalues;
    }       
    
    public function setcurl($data) {
        $ch = curl_init();

//$data = array('name' => 'Foo', 'file' => '@/home/user/test.png');

curl_setopt($ch, CURLOPT_URL, 'http://localhost/webservice-iatixml/public/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_exec($ch);
    }
    
    public function getcurl() {
        
            $ch = curl_init('http://localhost/webservice-iatixml/public/iati/valdate');
            curl_setopt($ch, CURLOPT_POST      ,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS    ,'$this->postvalues');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
            curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
            curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
            return curl_exec($ch);

    }
}