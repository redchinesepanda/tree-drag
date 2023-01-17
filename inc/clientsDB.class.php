<?php

class clientsDB extends clientsDebug{
	public function queryDBOther(string $query): bool {
		$messages = array();
		array_push($messages, 'clientsDB::queryDBOther');
		array_push($messages, '$query: ' . print_r($query, true));
		$result = false;
		if ($this->connectDB()) {
			$result = $this->getLinkID()->query($query);
			if (!$result) {
			    $this->setDBError($this->getLinkID()->error, $this->getLinkID()->errno);
			}
		} else {
            $this->setDBError('clientsDB Error 0: db not connected');
		}
		array_push($messages, '$this->getLinkID()->error: ' . print_r($this->getLinkID()->error, true));
		array_push($messages, '$this->getLinkID()->errno: ' . print_r($this->getLinkID()->errno, true));
		$this->showLog($messages);
		return $result;
	}
	public function queryDBSelect(string $query): ?mysqli_result {
		$result = null;
		if ($this->connectDB()) {
			$result = $this->getLinkID()->query($query);
			if (!$result) {
			    $this->setDBError($this->getLinkID()->error);
				$result = null;
			}
		} else {
            $this->setDBError('clientsDB Error 0: db not connected');
		}
		return $result;
	}
	public function connectDB():bool {
		$messages = array();
		array_push($messages, 'clientsDB::connectDB');
		$result = true;
		if (is_null($this->getLinkID())) {
			$mysqli = new mysqli(
				$this->getDBHost(),
				$this->getDBUser(),
				$this->getDBPassword(),
				$this->getDBName()
			);
			array_push($messages, '$mysqli->connect_errno: ' . print_r($mysqli->connect_errno, true));
			if ($mysqli->connect_errno == 0) {
				$mysqli->set_charset('utf8_general_ci');
				$this->setLinkID($mysqli);
			} else {
				array_push($messages, '$mysqli->connect_error: ' . print_r($mysqli->connect_error, true));
				$this->setDBError((string) $mysqli->connect_error);
				$result = false;
			}
		} else {
			if (!$this->getLinkID()->ping()) {
				$this->setDBError($mysqli->error);
				$result = false;
			}
		}
		$this->showLog($messages);
		return $result;
	}
	public function __construct() {
	}
	private ?mysqli $link_id = null;
	public function getLinkID(): ?mysqli  {
		return $this->link_id;
	}
	public function setLinkID(?mysqli $link_id): void {
		$this->link_id = $link_id;
	}
	private string $db_host = 'localhost';
	private function getDBHost(): string {
		return $this->db_host;
	}
	private string $db_name = 'vlukas6o_wp6';
	private function getDBName(): string {
		return $this->db_name;
	}
	private string $db_user = 'vlukas6o_wp6';
	private function getDBUser(): string {
		return $this->db_user;
	}
	private string $db_password = '2*vChJSc';
	private function getDBPassword(): string {
		return $this->db_password;
	}
	private string $db_error = '';
	private int $db_errno = 0;
	public function getDBError(): string {
		return $this->db_error;
	}
	public function getDBErrno(): int {
		return $this->db_errno;
	}
	private function setDBError(string $error, int $errno = 0): void {
		$this->db_error = $error;
		$this->db_errno = $errno;
	}
	protected bool $debug = false;
}

?>