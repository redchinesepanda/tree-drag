<?php

class clientsTree extends clientsDebug {
	private function deleteRelationQuery(int $child_id):string {
		$query = 'DELETE '
			. 'FROM '
			. '`' . clientsTree::$db_relations . '` '
			. 'WHERE '
			. '`' . clientsTree::$field_child_id . '` = '
			. '\'' . $child_id . '\'';
		$messsages = array('deleteRelationQuery');
		array_push($messsages, '$query: ' . $query);
		$this->showLog($messsages);
		return $query;
	}
	public function deleteRelation(int $child_id):bool {
		$query = $this->deleteRelationQuery($child_id);
		$result = $this->getClientsDB()->queryDBOther($query);
		//$result = true;
		return $result;
	}
	private function updateRelationQuery(int $new_parent_id, int $child_id):string {
		$query = 'UPDATE '
			. '`' . clientsTree::$db_relations . '` '
			. 'SET '
			. '`' . clientsTree::$field_parent_id . '` = '
			. '\'' . $new_parent_id . '\''
			. 'WHERE '
			. '`' . clientsTree::$field_child_id . '` = '
			. '\'' . $child_id . '\'';
		return $query;
	}
	public function updateRelation(int $new_parent_id,int $child_id):bool {
		$query = $this->updateRelationQuery($new_parent_id, $child_id);
		$result = $this->getClientsDB()->queryDBOther($query);
		return $result;
	}
	private function insertRelationQuery(int $parent_id, int $child_id):string {
		$query = 'INSERT INTO ' . '`' . clientsTree::$db_relations . '` ('
			. '`' . clientsTree::$field_parent_id . '`, '
			. '`' . clientsTree::$field_child_id . '`)'
			. 'VALUES ('
			. '\'' . $parent_id . '\', '
			. '\'' . $child_id . '\''
			. ')';
		return $query;
	}
	public function insertRelation(int $parent_id, int $child_id):bool {
		$query = $this->insertRelationQuery($parent_id, $child_id);
		$result = $this->getClientsDB()->queryDBOther($query);
		return $result;
	}
	private function updateObjectQuery(int $child_id, string $name, string $description):string {
		$query = 'UPDATE '
			. '`' . clientsObject::$db_objects . '` '
			. 'SET '
			. '`' . clientsObject::$field_name . '` = '
			. '\'' . $name . '\', '
			. '`' . clientsObject::$field_description . '` = '
			. '\'' . $description . '\' '
			. 'WHERE '
			. '`' . clientsObject::$field_id . '` = '
			. '\'' . $child_id . '\'';
		return $query;
	}
	public function updateObject(int $child_id, string $name, string $description):bool {
		$query = $this->updateObjectQuery($child_id, $name, $description);
		$result = $this->getClientsDB()->queryDBOther($query);
		return $result;
	}
	
	private function insertObjectQuery(clientsObject &$clientsObject):string {
		$query = 'INSERT INTO ' . '`' . clientsObject::$db_objects . '` ('
			. '`' . clientsObject::$field_name . '`, '
			. '`' . clientsObject::$field_description . '`)'
			. 'VALUES ('
			. '\'' . $clientsObject->getName() . '\', '
			. '\'' . $clientsObject->getDescription() . '\''
			. ')';
		return $query;
	}
	public function insertObject(clientsObject &$clientsObject):bool {
		$query = $this->insertObjectQuery($clientsObject);
		$result = $this->getClientsDB()->queryDBOther($query);
		if ($result) {
			$clientsObject->setID($this->getClientsDB()->getLinkID()->insert_id);
		}
		return $result;
	}
	private function deleteObjectQuery(int $child_id):string {
		$query = 'DELETE '
			. 'FROM '
			. '`' . clientsObject::$db_objects . '` '
			. 'WHERE '
			. '`' . clientsObject::$field_id . '` = '
			. '\'' . $child_id . '\'';
		return $query;
	}
	public function deleteObject(int $child_id): bool {
		$messages = array();
		array_push($messages, 'clientsTree::deleteObject');
		array_push($messages, '$child_id: ' . print_r($child_id, true));
		$query = $this->loadObjectsQuery($child_id);
		array_push($messages, '$query: ' . print_r($query, true));
		$result = $this->getClientsDB()->queryDBSelect($query);
		if (!is_null($result)) {
			while ($object = $result->fetch_object('clientsObject')) {
				$this->deleteObject($object->getID());
			}
		}
		$result = $this->deleteRelation($child_id);
		$query = $this->deleteObjectQuery($child_id);
		array_push($messages, '$query: ' . print_r($query, true));
		$result = $this->getClientsDB()->queryDBOther($query);
		//$result = true;
		$this->showLog($messages);
		return $result;
	}
	private function loadObjectsQuery(int $parent_id = 0):string {
		$join = '';
		$condition = '`' . clientsObject::$field_id . '` NOT IN ('
			. 'SELECT '
			. '`' . clientsObject::$field_id . '` '
			. 'FROM ' . '`' . clientsObject::$db_objects . '` '
			. 'INNER JOIN '
			. '`' . clientsTree::$db_relations . '` ON '
			. '`' . clientsObject::$field_id . '` = '
			. '`' . clientsTree::$field_child_id . '`'
		. ') ';
		if ($parent_id != 0) {
			$join = 'INNER JOIN '
				. '`' . clientsTree::$db_relations . '` ON '
				. '`' . clientsObject::$field_id . '` = '
				. '`' . clientsTree::$field_child_id . '` ';
			$condition = '`' . clientsTree::$field_parent_id . '` = \'' . $parent_id . '\' ';
		}
		$query = 'SELECT '
			. '`' . clientsObject::$field_id . '`, '
			. '`' . clientsObject::$field_name . '`, '
			. '`' . clientsObject::$field_description . '` '
			. 'FROM ' . '`' . clientsObject::$db_objects . '` '
			. $join
			. 'WHERE ' . $condition
			. 'ORDER BY `' . clientsObject::$field_id . '`';
		return $query;
	}
	public function loadObjects(?clientsObject &$clientsObject = null): void {
		$messages = array();
		array_push($messages, 'clientsTree::loadObjects');
		$objects = array();
		$parent_id = 0;
		if (!is_null($clientsObject)) {
			$parent_id = $clientsObject->getID();
		}
		array_push($messages, '$parent_id: ' . print_r($parent_id, true));
		$query = $this->loadObjectsQuery($parent_id);
		array_push($messages, '$query: ' . print_r($query, true));
		$result = $this->getClientsDB()->queryDBSelect($query);
		if (!is_null($result)) {
			while ($object = $result->fetch_object('clientsObject')) {
				array_push($messages, '$object: ' . print_r($object, true));
				array_push($objects, $object);
				$this->loadObjects($object);
			}
		}
		if ($parent_id == 0) {
			$this->setObjects($objects);
		} else {
			$clientsObject->setChildren($objects);
			$clientsObject->setParentID($parent_id);
		}
		$this->showLog($messages);
	}
	public function __construct(clientsDB $cliensDB){
		$this->setCliensDB($cliensDB);
	}
	private array $objects = array();
	public function getObjects(): array {
		return $this->objects;
	}
	public function setObjects(array $objects): void {
		$this->objects = $objects;
	}
	private ?clientsDB $cliensDB = null;
	public function getClientsDB(): ?clientsDB {
		return $this->cliensDB;
	}
	public function setCliensDB(clientsDB $cliensDB): void {
		$this->cliensDB = $cliensDB;
	}
	protected bool $debug = false;
	public static string $db_relations = 'clients_object_relations';
	public static string $field_id = 'relation_id';
	public static string $field_parent_id = 'relation_parent_id';
	public static string $field_child_id = 'relation_child_id';
}

?>