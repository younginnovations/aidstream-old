<?php
class Iati_Codelist_Collection_Sector extends Iati_Codelist_Collection_ICodelistCollection
{
    public function __construct($lang = '1') {
        $this->tableName = Iati_Codelist_Constants::$Sector;
        $this->lang = $lang;
        $this->fetchResultSet();
        $this->Process();

    }


     
    public function Process()

    {

        foreach($this->resultSet['0'] as $eachData){
            $catDb = new Model_CategoryManager();
            $category =  $catDb->getByCategory($this->tableName."Category", trim($eachData['CategoryCode']), $this->lang);
            $this->data[] = array('code' => $eachData['Code'], 'name'=> $eachData['Name'], 'description'=> $eachData['Description'], 'category' => $eachData['CategoryCode'], 'category-name' => $category[0]['Name'], 'category-description' => $category[0]['Description']);

        }

    }


}