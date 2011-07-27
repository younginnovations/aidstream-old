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
		foreach ($object->getElements() as $elements) {
			$tableClassMapper = new Iati_WEP_TableClassMapper();
			$tableName = $tableClassMapper->getTableName($elements->getType());
			$parentType = $elements->getType();
			if ($tableName) {
				$this->_name = $tablename;
				$attribs = $elements->getAttribs();
				$parentId = $attribs['id'];
				if ($parentId)
				$this->update($attribs);
				else
				$parentId = $this->insert($attribs);
			}
			if ($elements->getElements()) {
				foreach ($elements->getElements() as $childElements) {
					$tableName = $tableClassMapper->getTableName($parentType . "_" . $childElements->getType());
					if ($tableName) {
						$this->_name = $tablename;
						$childAttribs = $elements->getAttribs();
						$childAttribs[lcfirst($parentType) . "_id"] = $parentId;
						$childId = $childAttribs['id'];
						if ($childId)
						$this->update($childAttribs);
						else
						$childId = $this->insert($childAttribs);
					}
					if ($childElements->getElements()) {
						foreach ($childElements->getElements() as $childNodeElements) {
							$tableName = $tableClassMapper->getTableName($type . "_" . $childNodeElements->getType());
							if ($tableName) {
								$this->_name = $tablename;
								$childNodeAttribs = $elements->getAttribs();
								$childNodeAttribs[lcfirst($parentType) . "_id"] = $parentId;
								$childNodeId = $childNodeAttribs['id'];
								if ($childNodeId)
								$this->update($childNodeAttribs);
								else
								$childNodeId = $this->insert($childNodeAttribs);
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

	//        else
	//            throw
}