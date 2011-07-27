<?php

class Iati_WEP_DbLayer extends Zend_Db_Table_Abstract
{

	protected $_name;

	/**
	 * Save data for the current object
	 *
	 *
	 */
	public function save($object)
	{
		if($object){
			$superType = $object->getType();
			$superAttribs = $object->getAttribs();
			$superId = $superAttribs['id'];
			foreach ($object->getElements() as $elements) {
				$tableClassMapper = new Iati_WEP_TableClassMapper();
				$tableName = $tableClassMapper->getTableName($elements->getType());
				$parentType = $elements->getType();
				if ($tableName) {
					$this->_name = $tableName;
					$attribs = $elements->getAttribs();
					$parentId = $attribs['id'];
					$attribs[$this->lcfirst($superType)."_id"] = $superId;
					if ($parentId==NULL || $parentId == 0){
						$attribs['id'] = NULL;
						$parentId = $this->insert($attribs);
					}else{
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
							if ($childId == NULL || $childId == 0){
								$childAttribs['id'] = NULL;
								$childId = $this->insert($childAttribs);
							}else{
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
									if ($childNodeId==NULL || $childId==0){
										$childNodeAttribs['id'] = NULL;
										$childNodeId = $this->insert($childNodeAttribs);
									}else {
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

	public function update($data)
	{
		// try to update data with $tablename and id
		try {
			return parent::update($data, array('id= ?' => $object->getPrimary()));
		} catch (Exception $e) {
			/* $object->setError(True);
			 $object->setErrorMessage($e); */
			return False;
		}
	}

	public function insert($data)
	{
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

	public function getRowSet($className, $fieldName, $value, $tree = false)
	{
		$tableClassMapper = new Iati_WEP_TableClassMapper();
		$tableName = $tableClassMapper->getTableName($className);
		if ($tableName) {
			$this->_name = $tableName;
			$query = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
			->where($fieldName, $value);
			$result = $this->fetchAll($query);
			$result = $result->toArray();

            return $result;
		}
	}

	function lcfirst($string) {
		$string{0} = strtolower($string{0});
		return $string;
	}

	//        else
	//            throw
}