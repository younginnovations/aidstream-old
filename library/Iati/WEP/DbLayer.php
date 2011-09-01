<?php

/**
 * @author rohan
 *
 */

class Iati_WEP_DbLayer extends Zend_Db_Table_Abstract {

	protected $_name;

	/**
	 * @param array $attribs
	 * Checks if the attrib contained in the element is empty or not
	 * @return Boolean
	 */
	public function checkIsEmptyAttribs($attribs)
	{
		$count = 0;
		foreach($attribs as $indiAttrib){
				if($indiAttrib){
					return true;
				}else{
					$count++;
				 $attribResult = false;
				}
			}

			//if the only attrib is null and that only attribs key is id then return true
			if(array_key_exists('id', $attribs) && $count == 1)
			return true;
			return $attribResult;
	}

	/**
	 * @param object $object ElementObject
	 * @param int $parentId  DataBase Primary Id of Parent Class
	 * Id of the object is embedded on function as atrrib
	 * Data is inserted into database if the Id doesnot exist for object and is updated if the Id exists
	 * @return unknown_type
	 */
	public function save($object, $parentId = null) {

	if ($object) {
			$defaultParentId = $parentId;
			$objectType = $object->getType();
			$parentType = $object->getParentType();
			$attribs = $object->getAttribs();
			$attribResult = $this->checkIsEmptyAttribs($attribs);
			//if there is not attributes in the object but contains childElements then ProcessIt (insert/update)
			if($attribResult == false && $object->getElements())
			$attribResult = true;
			if($attribResult){
				if($parentId){
				$parentField = $this->conditionFormatter($parentType);
				$attribs[$parentField] = $parentId;
				}
				$primaryId = $attribs['id'];
				$tableClassMapper = new Iati_WEP_TableClassMapper();
				$tableName = $tableClassMapper->getTableName($objectType, $parentType);
				if ($tableName){
					$this->_name = $tableName;
					if ($primaryId == NULL || $primaryId == 0) {
						$attribs['id'] = NULL;
						if($defaultParentId != null)
						$primaryId = $this->insert($attribs);
					} else {
						if($defaultParentId != null)
						$this->update($attribs);
					}
					foreach ($object->getElements() as $elements) {
						//recursive function save
						$this->save($elements, $primaryId);
					}
				}
			}
		}
	}


	public function update($data) {
		// try to update data with $tablename and id
		try {
			return parent::update($data, array('id= ?' => $data['id']));
		} catch (Exception $e) {
			/* $object->setError(True);
			 $object->setErrorMessage($e); */
			return False;
		}
	}

	/**
	 * @param array $data
	 * @return Boolean|int
	 */
	public function insert($data) {
		// try to insert data with $tablename
		try {
			parent::insert($data);
			$lastId = $this->getAdapter()->lastInsertId();
			return $lastId;
		} catch (Exception $e) {
			/* $object->setError(True);
			 $object->setErrorMessage($e); */
			return False;
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param string|int $value
	 * @param boolean $tree
	 * @return object|boolean elementTree
	 */
	public function getRowSet($className, $fieldName, $value, $tree = false, $validAttribs = false) {
		try{
			$tableClassMapper = new Iati_WEP_TableClassMapper();
			$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
			/*
			 * Conditions : If tree is true fetch whole of the tree
			 * 					Conditions : if fieldName is parentId (eg: activity_id)
			 * 							   : OR fieldName is primaryId (eg : id)
			 * 			  : If tree is False just fetch a row and return elementObject
			 */
			if ($tree) {
				//check If the field supplied is own Id or parent_id;
				$conditionalClass = $this->checkConditionField($className,$fieldName);
				if($conditionalClass){
					$class = "Iati_Activity_Element_" . $conditionalClass;
					$activityType  = new $class;
					$class = "Iati_Activity_Element_" . $className;
					$activity = new $class;
					$resultTree = $activityType;
					$formattedResult = $this->getRows($className, $fieldName, $value, $tree);
					if(!$formattedResult[$className])
					$activityType->addElement($className);
					foreach ($formattedResult[$className] as $result) {
						$activity = new $class;
						$parentClass = $activity;
						$resultTree = $this->fetchRowTreeSet($parentClass, $className, $result, $validAttribs);
						$activityType->addElement($resultTree);
					}
					return $activityType;
				}else{
					$class = "Iati_Activity_Element_" . $className;
					$activity = new $class;
					$parentClass = $activity;
					$resultTree = $activity;
					$formattedResult = $this->getRows($className, $fieldName, $value, $tree);
					foreach ($formattedResult[$className] as $result) {
						$resultTree = $this->fetchRowTreeSet($parentClass, $className, $result, $validAttribs);
					}
					return $resultTree;
				}
			} else {
				$class = "Iati_Activity_Element_" . $className;
				$activity = new $class;
				$formattedResult = $this->getRows($className, $fieldName, $value);
				//@Todo validAttribs part
				$result = $formattedResult[$className];
				$activity->setAttribs($result[0]);
				return $activity;
			}
		}
		catch (Exception $e)
		{
			return false;
		}
	}


	/**
	 * @param object $parentClass Elment object
	 * @param string $className
	 * @param arrya $data
	 * @return object element object
	 */
	public function fetchRowTreeSet($parentClass, $className, $data, $validAttribs = false)
	{
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
		if($validAttribs)
		{
			//@todo
			$frontEndClassName = $parentClass->getName();


		}
		$parentClass->setAttribs($data);
		$elementTree = $activityTreeMapper->getActivityTree($className);
		if(is_array($elementTree)){
			$primaryId = $data['id'];
			$conditionField = $this->conditionFormatter($className);
			foreach ($elementTree as $classElement) {
				$element = $parentClass->addElement($classElement);
				$flag = false;
				$resultRow = $this->getRows($classElement, $conditionField, $primaryId, false, $parentClass);
				if ((is_array($resultRow[$classElement]))) {
					foreach ($resultRow[$classElement] as $eachRow) {
						if($flag)
						$element = $parentClass->addElement($classElement);
						$flag = true;
						$result = $this->fetchRowTreeSet($element, $classElement, $eachRow);
					}
				}
			}
		}
		return $parentClass;
	}

	/**
	 * @param unknown_type $string
	 * @return string
	 */
	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}



	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int|string $value
	 * @param boolean $tree
	 * @param object $parentType
	 * @return array fetched datas
	 * Actual interaction with Data base is made here and returns datas as an array of className
	 */
	public function getRows($className, $fieldName = null, $value = null, $tree = false, $parentType = null)
	{
		//instead of using classname to fetch the tableName using the type off the class.
		$objectType = $this->getType($className, $parentType);
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$tableName = $tableClassMapper->getTableName($objectType);
		if ($tableName) {
			$this->_name = $tableName;
			if (!$fieldName || !$value) {
				$query = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false);
			} else {
				$query = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
				->where($fieldName . "= ?", $value);
			}
			$result = $this->fetchAll($query);
			$result = $result->toArray();
			$arrayResult[$className] = $result;
			return $arrayResult;
		}
	}


	/**
	 * @param string $className
	 * @param object $parentType
	 * @return string
	 *
		 * Get the type from the Element Class
		 * If Parent class exists get Parent type from the Element Class
		 * Process it and use it as Class Name to get tableName
	 */
	public function getType($className, $parentType)
	{
		$class = "Iati_Activity_Element_" .$className;
		$element = new $class;
		$type = $element->getType();
		$parentClass = $element->getParentType();
		if($parentType != null && $parentClass != 'Activity'){
		$objectType = $parentClass."_".$type;
		}else
		$objectType = $type;

		return $objectType;
	}


	/**
	 * @param string $className
	 * @return string|string
	 * formats the classname to fetch parentId for the childElement
	 * replaces the camel casing with _
	 */

	public function conditionFormatter($className) {

		if(ucfirst($className) == "Result_Indicator")
		return "indicator_id";
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField."_id";
	}




	/**
	 * @param string $className
	 * @param string $fieldName
	 * @return string|string|mixed
	 *  check for the condition id supplied is ownId or parentId
	 */
	public function checkConditionField($className,$fieldName){
		if($this->conditionFormatter($className) == $fieldName || $fieldName == 'id')
		return false;
		else{
			if($fieldName == 'indicator_id')
			return "Result_Indicator";
			$uCaseField = ucfirst($fieldName);
			$className = str_replace("_id","",$uCaseField);
			return $className;
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int $id
	 * @return unknown_type
	 */
	public function deleteRows($className, $fieldName, $id)
	{
		$parentId = $this->checkConditionField($className, $fieldName);
		if($parentID){
			$this->deleteOnParentId($className, $fieldName, $id);
		}else{
			$this->deleteOnOwnId($className, $fieldName, $id);
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int $id
	 * @return unknown_type
	 */
	public function deleteOnParentId($className, $fieldName, $id)
	{
		$this->delete($className, $fieldName, $id);
		$tree = new Iati_WEP_ActivityTreeMapper();
		$elementTree = $tree->getActivityTree($className);
		if(is_array($elementTree)){
			$ownRows = $this->getRows($className, $fieldName, $id);
			$ownField = $this->conditionFormatter($className);
			foreach($ownRows[$className] as $ownRow){
				$ownId = $ownRow['id'];
				$this->deleteChildElements($elementTree, $ownField, $ownId);
			}
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int $id
	 * @return unknown_type
	 */
	public function deleteOnOwnId($className, $fieldName, $id)
	{
		$this->delete($className, $fieldName, $id);
		$tree = new Iati_WEP_ActivityTreeMapper();
		$elementTree = $tree->getActivityTree($className);
		if(is_array($elementTree)){
			$fieldName = $this->conditionFormatter($className);
			$this->deleteChildElements($elementTree, $fieldName, $id);
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int $id
	 * @return unknown_type
	 */
	public function deleteChildElements($elementTree, $fieldName, $id)
	{
		foreach($elementTree as $elementClassName){
			$this->delete($elementClassName, $fieldName, $id);
			$tree = new Iati_WEP_ActivityTreeMapper();
			$childElementTree = $tree->getActivityTree($elementClassName);
			if(is_array($childElementTree)){
				$ownRows = $this->getRows($elementClassName, $fieldName, $id);
				$ownField = $this->conditionFormatter($elementClassName);
				foreach($ownRows[$elementClassName] as $ownRow){
					$ownId = $ownRow['id'];
					$this->deleteChildElements($childElementTree, $ownField, $ownId);
				}
			}
		}
	}

	/**
	 * @param string $className
	 * @param string $fieldName
	 * @param int $id
	 * @return unknown_type
	 */
	public function delete($className, $fieldName, $value)
	{
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$tableName = $tableClassMapper->getTableName($className);
		if ($tableName) {
			$this->_name = $tableName;
			$where = $this->getAdapter()->quoteInto($fieldName . "= ?", $value);
//						var_dump("Deleting From table ".$tableName. " where ".$fieldName." is equal to ".$value);
			parent::delete($where);
		}
	}

}