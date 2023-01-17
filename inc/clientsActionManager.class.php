<?php

class clientsActionManager extends clientsDebug {
	public function checkParams(bool $permission = false): array {
		$messages = array();
		array_push($messages, 'clientsActionManager::checkParams');
		array_push($messages, '$permission: ' . ($permission ? 'true' : 'false'));
		$json = array('messageCode' => -1, 'messageText' => 'Ошибка иницализации');
		$result = false;
		$customCode = 0;
		$params = $this->getParams(array('mode' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		if ($params['mode'] == 'auth') {
			$result = $this->auth();
			$this->setMessage('Авторизация не удалась');
			if ($result) {
				$this->setMessage('Авторизация прошла успешно');
				$customCode = 3;
			}
			$json['messageText'] = $this->getMessage();
		}
		if ($params['mode'] == 'logout') {
			$result = $this->logout();
			$this->setMessage('Выйти не удалась');
			if ($result) {
				$this->setMessage('Выход прошел успешно');
				$customCode = 4;
			}
			$json['messageText'] = $this->getMessage();
		}
		if ($permission) {
			if ($params['mode'] == 'parent') {
				$result = $this->updateObjectRelation();
				if ($result) {
					array_push($messages, 'Родитель изменен');
					$this->setMessage('Родитель изменен');
				}
			}
			if ($params['mode'] == 'root') {
				$result = $this->deleteObjectRelation();
				if ($result) {
					array_push($messages, 'Связь с родителем удалена');
					$this->setMessage('Связь с родителем удалена');
				}
			}
			if ($params['mode'] == 'child') {
				$result = $this->insertObjectRelation();
				if ($result) {
					array_push($messages, 'Связь с родителем добавлена');
					$this->setMessage('Связь с родителем добавлена');
				}
			}
			if ($params['mode'] == 'add') {
				$clientsObject = $this->addObject();
				if (!is_null($clientsObject)) {
					$result = true;
					array_push($messages, '$clientsObject: ' . print_r($clientsObject, true));
					$this->setMessage('Обьект обьект добавлен, связь добавлена');
					$this->setClientsObject($clientsObject);
					$json['clientsObject'] = $this->getClientsObject()->jsonSerialize();
					$customCode = 1;
				} else {
					$json['messageText'] = $this->getMessage();
				}
			}
			if ($params['mode'] == 'data') {
				$result = $this->updateObject();
				if ($result) {
					array_push($messages, 'Данные обьекта обновлены');
					$this->setMessage('Данные обьекта обновлены');
					$customCode = 2;
					$json['clientsObject'] = $this->getClientsObject()->jsonSerialize();
				} else {
					$json['messageText'] = $this->getMessage();
				}
			}
			if ($params['mode'] == 'delete') {
				$result = $this->deleteObject();
				if ($result) {
					array_push($messages, 'Обьект и его потомки удалены');
					$this->setMessage('Обьект и его потомки удалены');
				}
			}
		} else {
			$this->setMessage('Авторизуйтесь');
		}
		if ($result) {
			$json['messageCode'] = $customCode;
			$json['messageText'] = $this->getMessage();
		}
		$this->showLog($messages);
		return $json;
	}
	public function getParams(array $params): array {
		$messages = array();
		array_push($messages, 'clientsActionManager::getParams');
		foreach ($params as $param_key => $param_value) {
			$params[$param_key] = '';
			if (array_key_exists($param_key, $_GET)) {
				$params[$param_key] = $_GET[$param_key];
			}
			array_push($messages, '$params[' . $param_key . ']: ' . $params[$param_key]);
		}
		$this->showLog($messages);
		return $params;
	}
	public function logout(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::logout');
		$clientsAuth = new clientsAuth();
		$this->setClientsAuth($clientsAuth);
		$result = $this->getClientsAuth()->sessionEnd();
		$this->showLog($messages);
		return $result;
	}
	public function auth(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::auth');
		$params = $this->getParams(array('user_name' => '', 'user_password' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$clientsAuth = new clientsAuth($params['user_name'], $params['user_password'], '');
		$clientsAuth->setCliensDB($this->getClientsTree()->getClientsDB());
		$this->setClientsAuth($clientsAuth);
		$result = $this->getClientsAuth()->authCheck();
		if ($result) {
			$this->getClientsAuth()->sessionStart();
		}
		$this->showLog($messages);
		return $result;
	}
	public function insertObjectRelation(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::insertObjectRelation');
		$params = $this->getParams(array('parent_id' => '', 'child_id' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$result = $this->getClientsTree()->insertRelation($params['parent_id'], $params['child_id']);
		$this->showLog($messages);
		return $result;
	}
	public function deleteObjectRelation(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::deleteObjectRelation');
		$params = $this->getParams(array('child_id' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$result = $this->getClientsTree()->deleteRelation($params['child_id']);
		$this->showLog($messages);
		return $result;
	}
	public function updateObjectRelation(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::updateObjectRelation');
		$params = $this->getParams(array('child_id' => '', 'parent_id' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$result = $this->getClientsTree()->updateRelation($params['parent_id'], $params['child_id']);
		$this->showLog($messages);
		return $result;
	}
	public function deleteObject(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::deleteObjectRelation');
		$params = $this->getParams(array('child_id' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$result = $this->getClientsTree()->deleteObject($params['child_id']);
		$this->showLog($messages);
		return $result;
	}
	public function updateObject(): bool {
		$messages = array();
		array_push($messages, 'clientsActionManager::updateObject');
		$params = $this->getParams(array('child_id' => '', 'name' => '', 'description' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		$result = $this->getClientsTree()->updateObject($params['child_id'], $params['name'], $params['description']);
		if (!$result) {
			$this->setMessage('Нельзя поменять имя на существующее');
		} else {
			$this->setClientsObject(new clientsObject($params['name'], $params['description'], $params['child_id']));
		}
		$this->showLog($messages);
		return $result;
	}
	public function addObject(): ?clientsObject {
		$messages = array();
		array_push($messages, 'clientsActionManager::addObject');
		$result = null;
		$params = $this->getParams(array('name' => '', 'description' => '', 'parent_id' => ''));
		array_push($messages, 'params: ' . print_r($params, true));
		if (!empty($params['name']) && !empty($params['description'])) {
			$clientsObject = new clientsObject($params['name'], $params['description']);
			$success = $this->getClientsTree()->insertObject($clientsObject);
			array_push($messages, '$success: ' . print_r($success, true));
			$errno = $this->getClientsTree()->getClientsDB()->getDBErrno();
			array_push($messages, '$errno: ' . print_r($errno, true));
			if (!$success && $errno == 1062) {
				array_push($messages, 'Обьект с таким названием уже существует');
				$this->setMessage('Обьект с таким названием уже существует');
			} else {
				array_push($messages, '$clientsObject: ' . print_r($clientsObject, true));
				if ($params['parent_id'] != 0) {
					$success = $this->getClientsTree()->insertRelation($params['parent_id'], $clientsObject->getID());
					$clientsObject->setParentID($params['parent_id']);
				}
				$result = $clientsObject;
			}
		}
		$this->showLog($messages);
		return $result;
	}
	public function __construct(clientsTree &$clientsTree){
		$this->setClientsTree($clientsTree);
	}
	private ?clientsTree $clientsTree = null;
	private function getClientsTree(): clientsTree {
		return $this->clientsTree;
	}
	private function setClientsTree(clientsTree $clientsTree):void {
		$this->clientsTree = $clientsTree;
	}
	private ?clientsObject $clientsObject = null;
	public function getClientsObject(): ?clientsObject {
		return $this->clientsObject;
	}
	private function setClientsObject(?clientsObject $clientsObject):void {
		$this->clientsObject = $clientsObject;
	}
	private ?clientsAuth $clientsAuth = null;
	public function getClientsAuth(): ?clientsAuth {
		return $this->clientsAuth;
	}
	private function setClientsAuth(?clientsAuth $clientsAuth):void {
		$this->clientsAuth = $clientsAuth;
	}
	private string $message = '';
	public function getMessage(): string {
		return $this->message;
	}
	private function setMessage(string $message): void {
		$this->message = $message;
	}
	protected bool $debug = false;
}

?>