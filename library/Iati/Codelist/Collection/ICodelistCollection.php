<?php
abstract class Iati_Codelist_Collection_ICodelistCollection
{
    protected $data;
    protected $lang;
    protected $tableName;
    protected $resultSet;
    public function __construct(){
         

    }

    public function setTableName($data)
    {
        $this->tableName = $data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setResultSet($data)
    {
        $this->resultSet = $data;
    }

    public function getResultSet($data)
    {
        return $this->resultSet;
    }
    public function setLang($lang)
    {
        $this->lang = $lang;
    }


    public function fetchResultSet()
    {
        $db = new Model_Viewcode();
        $this->resultSet =  $db->getCode($this->tableName, null, $this->lang);
    }

    abstract protected function Process();

    public function getResult()
    {
        return $this->data;
    }
}