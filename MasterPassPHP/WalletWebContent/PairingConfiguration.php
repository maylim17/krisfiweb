<?php
session_start();

require_once('Controller/MasterPassController.php');
require_once('Controller/MasterPassHelper.php');

$sad = unserialize($_SESSION['sad']);
// print_r($_SESSION);
$controller = new MasterPassController($sad);

$sad = $controller->setPairingDataTypes(explode(",", $_POST['dataTypes']));

$_SESSION['sad'] = serialize($sad);

header('Content-Type: application/json');
echo json_encode($sad);

?>