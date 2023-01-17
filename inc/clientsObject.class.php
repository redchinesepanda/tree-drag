<?php

class clientsObject extends clientsDebug implements JsonSerializable {
    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
	public function __construct(string $name = 'default name', string $description = '', int $id = 0) {
		$messages = array();
		array_push($messages, 'clientsObject::__construct');
		array_push($messages, '$this->getID(): ' . $this->getID());
		if ($this->getID() == 0) {
			$this->setID($id);
		}
		array_push($messages, '$this->getName(): ' . $this->getName());
		if ($this->getName() == '') {
			$this->setName($name);
		}
		array_push($messages, '$this->getDescription(): ' . $this->getDescription());
		if ($this->getDescription() == '') {
			$this->setDescription($description);
		}
		$this->showLog($messages);
	}
	public function __set($name, $value ) {
		$messages = array();
		array_push($messages, 'clientsObject::__set');
        array_push($messages, '$name: ' . $name);
        array_push($messages, '$value: ' . $value);
		if ($name == clientsObject::$field_id) {
			$this->setID($value);
		}
		if ($name == clientsObject::$field_name) {
			$this->setName($value);
		}
		if ($name == clientsObject::$field_description) {
			$this->setDescription($value);
		}
		$this->showLog($messages);
    }
	private int $id = 0;
	public function getID(): int {
		return $this->id;
	}
	public function setID(int $id): void {
		$this->id = $id;
	}
	private string $name = '';
	public function getName(): string {
		return $this->name;
	}
	public function setName(string $name): void {
		$this->name = $name;
	}
	private string $description = '';
	public function getDescription(): string {
		return $this->description;
	}
	public function setDescription(string $description): void {
		$this->description = $description;
	}
	private array $children = array();
	public function getChildren(): array {
		return $this->children;
	}
	public function setChildren(array $children): void {
		$this->children = $children;
	}
	private int $parent_id = 0;
	public function getParentID(): int {
		return $this->parent_id;
	}
	public function setParentID(int $parent_id): void {
		$this->parent_id = $parent_id;
	}
	public static string $db_objects = 'clients_objects';
	public static string $field_id = 'object_id';
	public static string $field_name = 'object_name';
	public static string $field_description = 'object_description';
	protected bool $debug = false;
}

?>