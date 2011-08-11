<?php

class Iati_WEP_DbLayer extends Zend_Db_Table_Abstract {

	protected $_name;

	/**
	 * Save data for the current object
	 * Input object is of ElementType
	 * Id of the object is embedded on function as atrrib
	 * Data is inserted into database if the Id doesnot exist for object and is updated if the Id exists
	 *
	 */

	public function checkIsEmptyAttribs($attribs)
	{
		foreach($attribs as $indiAttrib){
				if($indiAttrib){
					return true;
				}else{
				 $attribResult = false;
				}
			}
			return $attribResult;
	}
	public function save($object, $parentId = null) {

	if ($object) {
			$defaultParentId = $parentId;
			$objectType = $object->getType();
			$parentType = $object->getParentType();
			$attribs = $object->getAttribs();
			$attribResult = $this->checkIsEmptyAttribs($attribs);
			if($objectType == 'ContactInfo')
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

	/*
	 * method: getRowSet - process and retrive database datas
	 * params: className (string),
	 * 			fieldName (string),
	 * 			value (int/string),
	 * 			tree (boolean)
	 * returns: Element Object
	 */

	public function getRowSet($className, $fieldName, $value, $tree = false) {
		try{
			$tableClassMapper = new Iati_WEP_TableClassMapper();
			$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
			//activity
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
						$resultTree = $this->fetchRowTreeSet($parentClass, $className, $result);
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
						$resultTree = $this->fetchRowTreeSet($parentClass, $className, $result);
					}
					return $resultTree;
				}
			} else {
				$class = "Iati_Activity_Element_" . $className;
				$activity = new $class;
				$formattedResult = $this->getRows($className, $fieldName, $value);
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


	public function fetchRowTreeSet($parentClass, $className, $data)
	{
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
		$parentClass->setAttribs($data);
		$elementTree = $activityTreeMapper->getActivityTree($className);
		if(is_array($elementTree)){
			$primaryId = $data['id'];
			$conditionField = $this->conditionFormatter($className);
			foreach ($elementTree as $classElement) {
				$element = $parentClass->addElement($classElement);
				$flag = false;
				$resultRow = $this->getRows($classElement, $conditionField, $primaryId);
				if (($resultRow[$classElement])) {
					foreach ($resultRow[$classElement] as $eachRow) {
						if($flag)
						$element = $parentClass->addElement($classElement);
						$flag = true;
						$this->fetchRowTreeSet($element, $classElement, $eachRow);
					}
				}
			}
		}
		return $parentClass;
	}

	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}

	/*
	 * Interaction with Database
	 */

	public function getRows($className, $fieldName = null, $value = null, $tree = false) {
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$tableName = $tableClassMapper->getTableName($className);
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

	/*  params : classname
	 *  formats the classname to fetch parentId for the childElement
	 *  replaces the camel casing with _
	 *  returns : parentid
	 */
	public function conditionFormatter($className) {

		if(ucfirst($className) == "Result_Indicator")
		return "indicator_id";
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField."_id";
	}


	//  check for the condition id supplied is ownId or parentId

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

	public function deleteRows($className, $fieldName, $id)
	{
		$parentId = $this->checkConditionField($className, $fieldName);
		if($parentID){
			$this->deleteOnParentId($className, $fieldName, $id);
		}else{
			$this->deleteOnOwnId($className, $fieldName, $id);
		}
	}

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