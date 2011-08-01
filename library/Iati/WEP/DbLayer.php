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
			return;
		}
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

	public function conditionFormatter($className) {
		$conditionField = $this->lcfirst($className);
		return $conditionField . "_id";
	}


	public function checkConditionField($className,$fieldName){
		if($this->conditionFormatter($className) == $fieldName || $fieldName == 'id')
		return false;
		else{
			$uCaseField = ucfirst($fieldName);
			$className = str_replace("_id","",$uCaseField);
			return $className;
		}
	}

	public function deleteRows($className, $fieldName = 'id', $id)
	{
		$activityTreeMapper = new Iati_WEP_ActivityTreeMapper();
		$tableNames = $activityTreeMapper->getActivityTree($className);
		foreach ($tableNames as $tableName){
			$this->_name = $tableName;
			$where = $this->getAdapter()->quoteInto($fieldName . "= ?", $id);
			$this->delete($where);
		}




	}
}