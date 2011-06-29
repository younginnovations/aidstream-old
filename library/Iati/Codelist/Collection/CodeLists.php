<?php
class Iati_Codelist_Collection_CodeLists extends Iati_Codelist_Collection_ICodelistCollection
{
    public function __construct($lang = '1') {
	
        $this->tableName = Iati_Codelist_Constants::$CodeLists;
	
        $this->lang = $lang;
	
        $this->fetchResultSet();
	
        $this->Process();
	
    }

	
   
    public function Process()
    {
        foreach($this->resultSet['0'] as $eachData){
            if($eachData['codelist'] == 'Administrative Area (First-level)'){
                $uri = "AdministrativeArea1";
            }elseif($eachData['codelist'] == 'Administrative Area (Second-level)'){
                $uri = "AdministrativeArea2";
            }else{
                $uri = str_replace(' ', '', $eachData['codelist']);
            }
            
            $this->data[] = array('name' =>$eachData['codelist'], 'url'=> "http://".$_SERVER['HTTP_HOST']."/iati-aims/public/api/codelists/$uri");
	}
	
    }


}
