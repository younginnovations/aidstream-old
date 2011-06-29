<?php
class Iati_Codelist_Collection_Vocabulary extends Iati_Codelist_Collection_ICodelistCollection
{
    public function __construct($lang = '1') {
	
        $this->tableName = Iati_Codelist_Constants::$Vocabulary;
	
        $this->lang = $lang;
	
        $this->fetchResultSet();
	
        $this->Process();
	
    }

	
   
    public function Process()
	
    {
	
        foreach($this->resultSet['0'] as $eachData){
	
            $this->data[] = array('code' => $eachData['Code'], 'name'=> $eachData['Name']);
	
        }
	
    }


}