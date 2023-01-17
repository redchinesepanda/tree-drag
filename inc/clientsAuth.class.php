<?php

class clientsAuth extends clientsDebug {
	public function sessionEnd():bool {
		$messages = array();
		array_push($messages, 'clientsAuth::sessionEnd');
		$result = false;
		array_push($messages, '$this->sessionCheck(): ' . ($this->sessionCheck() ? 'true' : 'false'));
		if ($this->sessionCheck()) {
			$_COOKIE['session_id'] = '';
			setcookie('session_id', null, -1, '/'); 
			if (session_status() !== PHP_SESSION_NONE) {
				session_destroy();
			}
		}
		$result = true;
		$this->showLog($messages);
		return $result;
	}
	public function sessionStart():bool {
		$messages = array();
		array_push($messages, 'clientsAuth::sessionStart');
		$result = false;
		if (!$this->sessionCheck()) {
			if (session_status() === PHP_SESSION_NONE) {
				$session_id = session_create_id();
				session_id($session_id);
				array_push($messages, '$session_id: ' . $session_id);
				if (array_key_exists('session_id', $_COOKIE)) {
					$_COOKIE['session_id'] = $session_id;
				} else {
					setcookie('session_id', $session_id, time() + 3600);
				}
				$this->setKey($session_id);
				if (empty($this->getUsers())) {
					$this->loadUsers();
				}
				if (!empty($this->getUsers())) {
					$this->updateUsers();
				}
				$result = true;
			}
		}
		$this->showLog($messages);
		return $result;
	}
	public function sessionCheck():bool {
		$messages = array();
		array_push($messages, 'clientsAuth::sessionCheck');
		$result = false;
		if (array_key_exists('session_id', $_COOKIE)) {
			array_push($messages, '$_COOKIE[\'session_id\']: ' . $_COOKIE['session_id']);
			$this->setKey($_COOKIE['session_id']);
			$result = true;
		}
		$this->showLog($messages);
		return $result;
	}
	private function updateUsersQuery():string {
		$query = 'UPDATE '
			. '`' . clientsAuth::$db_users . '` '
			. 'SET '
			. '`' . clientsAuth::$field_key . '` = '
			. '\'' . $this->getKey() . '\' '
			. 'WHERE '
			. '`' . clientsAuth::$field_id . '` = '
			. '\'' . $this->getID() . '\'';
		return $query;
	}
	public function updateUsers():bool {
		$messages = array();
		array_push($messages, 'clientsAuth::updateUsers');
		if ($this->getClientsDB() == null) {
			$this->loadClientsDB();
		}
		if ($this->getClientsDB() != null) {
			$query = $this->updateUsersQuery();
			array_push($messages, '$query: ' . print_r($query, true));
			$result = $this->getClientsDB()->queryDBOther($query);
		}
		$this->showLog($messages);
		return $result;
	}
	public function authCheck():bool {
		$result = false;
		if (empty($this->getUsers())) {
			$this->loadUsers();
		}
		if (!empty($this->getUsers())) {
			$result = true;
		}
		return $result;
	}
	private function loadUsersQuery():string {
		$query = 'SELECT '
			. '`' . clientsAuth::$field_id . '`, '
			. '`' . clientsAuth::$field_name . '`, '
			. '`' . clientsAuth::$field_password . '` '
			. 'FROM ' . '`' . clientsAuth::$db_users . '` '
			. 'WHERE '
			. '(`' . clientsAuth::$field_name . '` = \'' . $this->getName() . '\' '
			. 'AND `' . clientsAuth::$field_password . '` = \'' . $this->getPassword() . '\') '
			. 'OR (`' . clientsAuth::$field_key . '` = \'' . $this->getKey() . '\') '
			. 'ORDER BY `' . clientsAuth::$field_id . '`';
		return $query;
	}
	private function loadClientsDB(): void {
		$clientsDB = new clientsDB();
		if ($clientsDB->connectDB()) {
			$this->setCliensDB($clientsDB);
		}
	}
	public function loadUsers(): void {
		$messages = array();
		array_push($messages, 'clientsAuth::loadUsers');
		if ($this->getClientsDB() == null) {
			$this->loadClientsDB();
		}
		if ($this->getClientsDB() != null) {
			$users = array();
			$query = $this->loadUsersQuery();
			array_push($messages, '$query: ' . print_r($query, true));
			$result = $this->getClientsDB()->queryDBSelect($query);
			if (!is_null($result)) {
				while ($user = $result->fetch_object()) {
					array_push($messages, '$user: ' . print_r($user, true));
					$this->setID($user->{clientsAuth::$field_id});
					array_push($users, $user);
				}
			}
			$this->setUsers($users);
			array_push($messages, '$this->getUsers(): ' . print_r($this->getUsers(), true));
		}
		$this->showLog($messages);
	}
	/*public function __construct(clientsDB $cliensDB){
		$this->setCliensDB($cliensDB);
	}*/
	/*public function __construct(string $user_name = '', string $user_password = '', string $user_key = '', ?clientsDB $cliensDB){
		$this->setName($user_name);
		$this->setPassword($user_password);
		$this->setKey($user_key);
		$this->setCliensDB($cliensDB);
	}*/
	public function __construct(string $user_name = '', string $user_password = '', string $user_key = ''){
		$this->setName($user_name);
		$this->setPassword($user_password);
		$this->setKey($user_key);
	}
	private int $user_id = 0;
	private function getID(): int {
		return $this->user_id;
	}
	private function setID(int $user_id): void {
		$this->user_id = $user_id;
	}
	private string $user_name = '';
	private function getName(): string {
		return $this->user_name;
	}
	private function setName(string $user_name): void {
		$this->user_name = $user_name;
	}
	private string $user_password = '';
	private function getPassword(): string {
		return $this->user_password;
	}
	private function setPassword(string $user_password): void {
		//$this->user_password = md5($user_password);
		$this->user_password = $user_password;
	}
	private string $user_key = '';
	private function getKey(): string {
		return $this->user_key;
	}
	private function setKey(string $user_key): void {
		$this->user_key = $user_key;
	}
	private array $users = array();
	public function getUsers(): array {
		return $this->users;
	}
	public function setUsers(array $users): void {
		$this->users = $users;
	}
	private ?clientsDB $cliensDB = null;
	public function getClientsDB(): ?clientsDB {
		return $this->cliensDB;
	}
	public function setCliensDB(clientsDB $cliensDB): void {
		$this->cliensDB = $cliensDB;
	}
	private string $message = '';
	public function getMessage(): string {
		return $this->message;
	}
	private function setMessage(string $message): void {
		$this->message = $message;
	}
	protected bool $debug = false;
	public static string $db_users = 'clients_users';
	public static string $field_id = 'clients_users_id';
	public static string $field_name = 'clients_users_name';
	public static string $field_password = 'clients_users_password';
	public static string $field_key = 'clients_users_key';
}

?>