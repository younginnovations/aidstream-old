<?php
include_once(__dir__."/../Config.php");

class IatiDatasets {
    protected $fileDir = INPUT_FILE_DIR;
    protected $filename = COMBINED_ORG_FILENAME;
    protected $aidstreamConnector;
    protected $fileList;
    
    public function __construct(Array $fileList)
    {
        $this->fileList = $fileList;
    }

    public function updateIatiData()
    { 
        $files = $this->fileList;
        if(!$files) return;
        //outfile
        $combinedFilename= $this->fileDir .$this->filename;
        //get all datasets    
        foreach($files as $filename => $fileUrl)
        {
            //get dataset
            $csvFile=$this->getCsvFile($fileUrl);
            if(preg_match('/<html>/' , $csvFile)) continue;
            if($csvFile)
            {
                //write single csv
                $singlefile= $this->fileDir ."files/".$filename.".csv";
                $singlefileh = fopen($singlefile, 'w+');
                fwrite($singlefileh, $csvFile);
                fclose($singlefileh);
            }
        }
        
        if(file_exists($combinedFilename)){
            unlink($combinedFilename);
            touch($combinedFilename);
        }
        $count = 1;
        foreach($files as $filename=>$fileUrl){
            $file = $this->fileDir ."files/".$filename.".csv";
            if(!file_exists($file)) continue;
           
            $fp = fopen($file , 'r');
            $data = fread($fp , filesize($file));
            fclose($fp);

            if($count != 1){
                $data = preg_replace('/^.*\n?/',"",$data);
            }
            $fp = fopen($combinedFilename , 'a');
            fwrite($fp , $data);
            fclose($fp);
            $count ++;
        }
    }
    
    public function getCsvFile($downloadUrl)
    {
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, "http://tools.aidinfolabs.org/csv/direct_from_registry/?search=&xml=".urlencode($downloadUrl)."&download=true&id=true&format=simple");
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   $data=curl_exec($ch);
	   curl_close($ch);
	   return $data;
    }
}