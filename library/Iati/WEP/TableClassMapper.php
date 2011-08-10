<?php

class Iati_WEP_TableClassMapper
{

    protected $classMapper;

    public function getTableName($classname,$parentName = null)
    {
    	if($parentName!= 'Activity' && $parentName != NULL){
    		$classname = $parentName."_".$classname;
    	}
        $strippedClassName = str_replace('Iati_WEP_Activity_Elements_', "", $classname);
        $classNames = explode("_",$strippedClassName,3);
		foreach($classNames as $eachClassName){
			$result = $this->convertCamelCaseToDash($eachClassName);
			if($tableName)
			$tableName = $tableName."/".$result;
			else
			$tableName = $result;
		}
		return "iati_".$tableName;

    }

	public function convertCamelCaseToDash($className) {
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField;
	}


	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}

}