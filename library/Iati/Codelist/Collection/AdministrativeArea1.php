<?php
class Iati_Codelist_Collection_AdministrativeArea1 extends Iati_Codelist_Collection_ICodelistCollection
{
    public function __construct($lang = '1') {
	
        $this->tableName = "AdministrativeAreaCode(First-level)";
	
        $this->lang = $lang;
	
        $this->fetchResultSet();
	
        $this->Process();
	
    }

	
   
    public function Process()
	
    {
	
        foreach($this->resultSet['0'] as $eachData){
	
            $this->data[] = array('code' => $eachData['Code'], 'country' => $eachData['Country'], 'name'=> $eachData['Name']);
	
        }
	
    }


}