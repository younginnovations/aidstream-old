<?php

/**
 * @author rohan
 *
 *
 */

class Iati_WEP_TableClassMapper
{

    protected $classMapper;

    /**
     * @param string $classname
     * @param string $parentName
     * @return string $tableName concatenated with iati_
     */
    public function getTableName($className,$parentName = null)
    {
    	/*
    	 * checks if parentName is activity
    	 * if False concatenate parentname "_" with classname and replace classname with result
    	 */

    	if($parentName!= 'Activity' && $parentName != NULL){
    		$className = $parentName."_".$className;
    	}
        $strippedClassName = str_replace('Iati_WEP_Activity_Elements_', "", $className);
        $classNames = explode("_",$strippedClassName,3);
		foreach($classNames as $eachClassName){
			$result = $this->convertCamelCaseToDash($eachClassName);
			if(isset($tableName)){
			$tableName = $tableName."/".$result;
			}else{
			$tableName = $result;
			}
		}
		return "iati_".$tableName;

    }

	/**
	 * @param string $className
	 * @return string $conditionField camelcase replaced by dash
	 */
	public function convertCamelCaseToDash($className) {
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField;
	}

	public function convertUnderscoreToCamelCase($name)
	{
	     $underscoreToCamelCase = new Zend_Filter_Word_UnderscoreToCamelCase();
	     return $underscoreToCamelCase->filter($name);
	}

	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}

}