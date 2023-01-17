<?php
require_once('require.php');

$messages = array();
array_push($messages, 'manageObjects');
$clientsDB = new clientsDB;
if (!$clientsDB->connectDB()) {
	array_push($messages, '$clientsDB->getDBError(): ' . $clientsDB->getDBError());
}
$clientsTree = new clientsTree($clientsDB);
$clientsActionManager = new clientsActionManager($clientsTree);
$success = $clientsActionManager->checkParams($permission);
array_push($messages, '$success: ' . print_r($success, true));
$clientsActionManager->showLog($messages);
echo json_encode($success);

?>