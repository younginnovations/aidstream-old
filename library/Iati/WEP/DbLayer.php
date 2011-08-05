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
	public function save($object) {
		if ($object) {
			$superType = $object->getType();
			$superAttribs = $object->getAttribs();
			$superId = $superAttribs['id'];
			$tableClassMapper = new Iati_WEP_TableClassMapper();
			if ($superType != "Activity") {
				$tableName = $tableClassMapper->getTableName($superType);
				if ($tableName) {
					$this->_name = $tableName;
					if ($superId == NULL || $superId == 0) {
						$superAttribs['id'] = NULL;
						$superId = $this->insert($superAttribs);
					} else {
						$this->update($superAttribs);
					}
				}
			}
			foreach ($object->getElements() as $elements) {
				$tableName = $tableClassMapper->getTableName($elements->getType());
				$parentType = $elements->getType();
				if ($tableName) {
					$this->_name = $tableName;
					$attribs = $elements->getAttribs();
					$parentId = $attribs['id'];
					if($attribs)
					$attribs[$this->lcfirst($superType) . "_id"] = $superId;
					if ($parentId == NULL || $parentId == 0) {
						$attribs['id'] = NULL;
						$parentId = $this->insert($attribs);
					} else {
						$this->update($attribs);
					}
				}
				if ($elements->getElements()) {
					foreach ($elements->getElements() as $childElements) {
						$tableName = $tableClassMapper->getTableName($parentType . "_" . $childElements->getType());
						if ($tableName) {
							$this->_name = $tableName;
							$childType = $childElements->getType();
							$childAttribs = $childElements->getAttribs();
							if($childAttribs)
							$childAttribs[$this->lcfirst($parentType) . "_id"] = $parentId;
							$childId = $childAttribs['id'];
							if ($childId == NULL || $childId == 0) {
								$childAttribs['id'] = NULL;
								$childId = $this->insert($childAttribs);
							} else {
								$this->update($childAttribs);
							}
						}
						if ($childElements->getElements()) {
							foreach ($childElements->getElements() as $childNodeElements) {
								$tableName = $tableClassMapper->getTableName($childType . "_" . $childNodeElements->getType());
								if ($tableName) {
									$this->_name = $tableName;
									$childNodeAttribs = $childNodeElements->getAttribs();
									if($childNodeAttribs)
									$childNodeAttribs[$this->lcfirst($parentType) . "_id"] = $parentId;
									$childNodeId = $childNodeAttribs['id'];
									if ($childNodeId == NULL || $childId == 0) {
										$childNodeAttribs['id'] = NULL;
										$childNodeId = $this->insert($childNodeAttribs);
									} else {
										$this->update($childNodeAttribs);
									}
								}
							}
						}
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

	public function getRowSet($className, $fieldName, $value, $tree = false) {
		try{
			$tableClassMapper = new Iati_WEP_TableClassMapper();
			$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
			//activity
			if ($tree) {
				$conditionalClass = $this->checkConditionField($className,$fieldName);
				if($conditionalClass){
					$class = "Iati_Activity_Element_" . $conditionalClass;
					$activityType  = new $class;
					$activity = $activityType->addElement($className);
					$classFlag = False;
				}else{
					$class = "Iati_Activity_Element_" . $className;
					$activity = new $class;
				}
				//
				var_dump($activityType);exit;
				$formattedResult = $this->getRows($className, $fieldName, $value, $tree);
				foreach ($formattedResult[$className] as $result) {
					if($conditionalClass && $classFlag == TRUE){
						$activity = $activityType->addElement($className);
					}
					$classFlag = TRUE;
					$activity->setAttribs($result);
					$primaryId = $result['id'];
					$conditionField = $this->conditionFormatter($className);

					$elementTree = $activityTreeMapper->getActivityTree($className);

					if(is_array($elementTree)){
						foreach ($elementTree as $classElement) {
							$nodeTree = $activityTreeMapper->getActivityTree($classElement);
							$resultRow = $this->getRows($classElement, $conditionField, $primaryId, $tree);
							if (is_array($resultRow)) {
								$element = $activity->addElement($classElement);
								$flag = false;
								foreach ($resultRow[$classElement] as $eachRow) {
									if($flag)
									$element = $activity->addElement($classElement);
									$element->setAttribs($eachRow);
									$flag = true;
									$nodeflag = false;
									if(is_array($nodeTree)){
										foreach ($nodeTree as $nodeElement){
											$nodeId = $eachRow['id'];
											$nodeConditionField = $this->conditionFormatter($classElement);


											$nodeResultRow = $this->getRows($nodeElement, $nodeConditionField, $nodeId, $tree);

											if (is_array($nodeResultRow)) {
												$nodeElementClass = $element->addElement($nodeElement);
												foreach ($nodeResultRow[$nodeElement] as $row) {
													if($nodeflag)
													$nodeElementClass = $element->addElement($nodeElement);
													$nodeElementClass->setAttribs($row);
													$nodeflag = true;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			} else {
				$class = "Iati_Activity_Element_" . $className;
				$activity = new $class;
				$formattedResult = $this->getRows($className, $fieldName, $value);
				$result = $formattedResult[$className];
				$activity->setAttribs($result[0]);
			}
			if($conditionalClass){
				return $activityType;
			}
			return $activity;
		}
		catch (Exception $e)
		{
			var_dump('caught an error');exit;
			return ;
		}
	}

	public function fetchRowTreeSet()
	{

	}

	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}

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
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField."_id";
	}


	//  check for the condition id supplied is ownId or parentId

	public function checkConditionField($className,$fieldName){
		if($this->conditionFormatter($className) == $fieldName || $fieldName == 'id')
		return false;
		else{
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
//			var_dump("Deleting From table ".$tableName. " where ".$fieldName." is equal to ".$value);
			parent::delete($where);
		}
	}

}