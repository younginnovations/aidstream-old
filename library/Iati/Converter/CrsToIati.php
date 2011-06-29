<?php
class Iati_Converter_CrsToiati{
	
	protected $donorName;
	protected $agency;
	protected $recipient;
	protected $filename;
	protected $destination;
	protected $rootpath;
	protected $normalizedCrsid;
	protected $sourcePath;
	protected $data;
	protected $crsid;
	protected $column;
	protected $files;
	protected $counter;
	
	public function _destruct()
	{
		unset($this->donorName);
		unset($this->agency);
		unset($this->recipient);
		unset($this->filename);
		unset($this->destination);
		unset($this->rootpath);
		unset($this->normalizedCrsid);
		unset($this->sourcePath);
		unset($this->data);
		unset($this->crsid);
		unset($this->column);
		unset($this->files);
	}
	
	public function setDonor($donorName)
	{
		$donorName = strtolower($donorName);
		$donorName = preg_replace("/[^a-zA-Z0-9]/", "-", $donorName);
		$donorName = preg_replace("/-+/", "-", $donorName);
		$donorName = rtrim($donorName,"-");
		$this->donorName = $donorName;
		
	}
	
	public function setAgency($agency)
	{
		$agency = strtolower($agency);
		$agency = preg_replace("/[^a-zA-Z0-9]/", "-", $agency);
		$agency = preg_replace("/-+/", "-", $agency);
		$agency = rtrim($agency,"-");
		$this->agency = $agency;	
	}
	
	public function setRecipient($recipient)
	{
		$recipient = strtolower($recipient);
		$recipient = preg_replace("/[^a-zA-Z0-9]/", "-", $recipient);
		$recipient = preg_replace("/-+/", "-", $recipient);
		$recipient = rtrim($recipient,"-");
		$this->recipient=$recipient;
	}
	
	public function getDonor()
	{
		return $this->donorName;
	}
	
	public function getAgency()
	{
		return $this->agency;
	}
	
	public function getRecipient()
	{
		return $this->recipient;
	}
	
	public function setFilename()
	{
		$donor = $this->getDonor();
		$agency = $this->getAgency();
		$receipient = $this->getRecipient();
		if($agency)
		{
			$this->filename = $donor."_".$agency."_".$receipient;	
		}
		else 
		{
			$this->filename = $donor."_".$receipient;
		}
	
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function getDestination()
	{
		return $this->destination;			
	}
	
	public function getPath()
	{
		$donor = $this->getDonor();
		$agency = $this->getAgency();
		if($agency)
		{
			return "/".$donor."/".$agency;
		}
		else
		{
			return "/".$donor;
		}
			
	}
	
	public function setDestination()
	{
		
		$filename = $this->getFilename();
		$path =$this->getPath();
		$this->destination = $path."/".$filename.".csv";	
	}
	
	public function setDestRoot($path)
	{
		$this->rootpath = $path;
	}
	
	public function getDestRoot()
	{
		return $this->rootpath;
	}
		
	public function writeToFile($array)
	{
		$filepath = $this->getPath();
		$rootpath = $this->getDestRoot();
		$filepath = $rootpath.$filepath;
		if(!file_exists($filepath))
		{
			mkdir($filepath,0777,true);
		}
		$path = $rootpath.$this->getDestination();
		//write to file 
		$fp= fopen($path,'a');
		fputcsv($fp,$array);
		fclose($fp);	
		//echo number_format(memory_get_usage()) . "\n";	
			
		
	}
	public function setNormalizedCrsid($id)
	{
		if(strlen($id)==10||strlen($id)==11)
		{
			$this->normalizedCrsid = $id;
		}
		elseif(strlen($id)>5 && strlen($id)<10)
		{
			$yearDigit=substr($id,0,2);
			$counter= substr($id,2);
			if(intval($yearDigit)<10)
			{
				$year="20".$yearDigit;
				$count=6-strlen($counter);
				for($i=0;$i<$count;$i++)
				{
					$counter = "0".$counter;
				}
				$this->normalizedCrsid = $year.$counter;
			}
			else 
			{
				$this->normalizedCrsid = "Exclude";	
			}
			
		}
		elseif(strlen($id)==5)
		{
			$year=substr($id,0,1);
			$id= substr($id,1);
			$year="200".$year;
			$this->normalizedCrsid = $year."00".$id;
		}
		else 
		{
			$idlength = strlen($id);
			$addition = 4-$idlength;
			for($i=0;$i<$addition;$i++)
			{
				$id="0".$id;
			}
			$this->normalizedCrsid = "2000"."00".$id;
		}
		
		if(is_numeric($this->normalizedCrsid))
		{
			$this->normalizedCrsid = $this->normalizedCrsid."a";
		}
		
	}
	public function getNormalizedCrsid()
	{
		return $this->normalizedCrsid;
	}
	//for reading files
	public function setSource($sourcePath)
	{
		$this->sourcePath = $sourcePath;
	}
	
	public function getSource()
	{
		return $this->sourcePath;
	}
	
	public function readFile()
	{
	
		$source = $this->getSource();
		if (($handle = fopen($source, "r")) !== FALSE) {
    		while (($data = fgetcsv($handle))!== FALSE) {			
    			$this->data = $data;
    			//var_dump($this->data);
    			$this->prepareFileWrite();
    			$data = $this->addNormalizedCrsid();
    			if(($data) && ($this->donorName))
    			{
    				$this->writeToFile($this->data);
    			}
    			else 
    			{
    				continue;
    			}
    		}
		
		}
		fclose($handle);
		//$this->_destruct();		
	
		
	}
	
	public function prepareFileWrite()
	{
		$this->counter++;
		if((isset($this->data[2])) && ($this->data[2] != 'donorname'))
		{
			$this->setDonor($this->data[2]);
		}
		else 
		{
		   $this->donorName = NULL;
			/*echo $this->counter."\n";
			var_dump($this->data);*/
		}
		if((isset($this->data[9])) && ($this->data[9] != 'agencyname'))
		{
			$this->setRecipient($this->data[9]);
		}
		/*else 
		{
			echo $this->counter."\n";
			var_dump($this->data);
		}*/
		if((isset($this->data[4])) && ($this->data[4] != 'recipientname'))
		{
			$this->setAgency($this->data[4]);
		}
		/*else {
			echo $this->counter."\n";
			var_dump($this->data);
		}*/
		if(isset($this->data[5]) && $this->data[5] != 'crsid')
		{
			$this->setCrsid($this->data[5]);
		}
		/*else 
		{
			echo $this->counter."\n";
			var_dump($this->data);
		}*/
		/*if(($this->data[2] != 'donorname') 
		    && ($this->data[9] != 'agencyname') 
		    && ($this->data[4] != 'recipientname')){
		        $this->setFilename();
                $this->setDestination();
		    }*/
		$this->setFilename();
		$this->setDestination();
	}
	public function setCrsid($crsid)
	{
		$this->crsid = $crsid;
	}
	public function getCrsid()
	{
		return $this->crsid;
	}
	public function getData()
	{
		return $this->data;
	}
	public function addNormalizedCrsid()
	{
		$this->setNormalizedCrsid($this->getCrsid());
		$id = $this->getNormalizedCrsid();
		if($id=="Exclude")
		{
			return Null;
		}
		else 
		{
			$arrayLast = (sizeof($this->data)-1);
			for($i=$arrayLast;$i<75;$i++)
			{
				$this->data[$i]="";
			} 
			$this->data[75]= $id;
			return $this->data;
		}
		
	}
	
	public function sortFileByCode()
	{
		$file = $this->getSource();
		$dataToSort=array();
		if (($handle = fopen($file, "r")) !== FALSE){
			while (($data = fgetcsv($handle))!== FALSE) {
				$dataToSort[] = $data;
				//echo number_format(memory_get_peak_usage()) . "\n";		 
			}
				
		}
		fclose($handle);
		$this->setColumn(75);
		$sortedData = $this->sort($dataToSort);
		unlink($file);
		foreach ($sortedData as $row)
		{
			$fp= fopen($file,'a');
			fputcsv($fp,$row);
			fclose($fp);				
		}
		unset($dataToSort);
		unset($data);
		unset($sortedData);
		
		
		
	}
	public function setColumn($value)
	{
		$this->column = $value;
	}
	public function sort($table) {
	   usort($table, array($this, 'compare'));
	   return $table;
	}
	public function compare($a, $b) {
    	
    	
		if ($a[$this->column] == $b[$this->column]) {  		
    		$this->setColumn(0);
    		if($a[$this->column] == $b[$this->column])
    		{
    			$this->setColumn(75);
    			return 0;
    		}
    		$temp = ($a[$this->column] < $b[$this->column]) ? -1 : 1;
    		$this->setColumn(75);
    		return $temp;
    		unset($temp);
    	}
    	return ($a[$this->column] < $b[$this->column]) ? -1 : 1;
	}	
	public function getCsvFiles($path)
	{

		if($handle = opendir($path))
	        {
	        while (false !== ($name = readdir($handle))) 
	        {     
	            if(!preg_match("/^\./", $name))
	            {
		            $newpath = $path."/".$name;    
	            	if(is_dir($newpath))
		                {
		                	$this->getCsvFiles($newpath);		                	
		                }
		            else 
		                {
		                	$path_parts = pathinfo($newpath);
		                	if($path_parts['extension'] == "csv")
		                	{
		                		$this->files[] = $newpath;	
		                	}	
		                }	
		                
	        	}
	                
	        }
	        closedir($handle);
	        }
	    sort($this->files);	    
    	return $this->files;
	}
	public function fileSize()
	{
		return $this->counter;
	}
	public function sortFileByCommand()
	{
		$file = $this->getSource();
		$tempfile = tempnam("/tmp", "FOO.csv");;
		$cmd = "csvfix sort -f 76,1 -o ".$tempfile." -smq ".$file;
		exec($cmd);
		rename($tempfile,$file);
		unset($file);
	}
	public function convertCrsToIati()
	{
		$source = $this->getSource();
		$destinationRoot = $this->getDestRoot();
		
		//file converting
		$filesToConvert = $this->getCsvFiles($source);//get all csv files from source
		foreach($filesToConvert as $file)
		{
			$this->setSource($file);
			//echo "\nfile read: $file";
			$this->readFile();
		}
		
		
		unset($this->files);//unset files to reset the csv files
		
		//sorting
        $filesToSort = $this->getCsvFiles($destinationRoot);//get all csv files to sort
        foreach($filesToSort as $filename)
        {
        	$this->setSource($filename);
        	//echo "\nfile sort ".$this->getSource();
        	//$this->sortFileByCode();
        	$this->sortFileByCommand();
        }        	
	}
}
