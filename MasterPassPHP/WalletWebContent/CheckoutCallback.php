<?php
session_start();

require_once('Controller/MasterPassController.php');
require_once('Controller/MasterPassHelper.php');

$sad = unserialize($_SESSION['sad']);
// print_r($_SESSION);
$controller = new MasterPassController($sad);

$errorMessage = null;
try {

	$sad = $controller->getRequestToken();
	$sad = $controller->postShoppingCart();

} catch (Exception $e){
	$errorMessage = MasterPassHelper::formatError($e->getMessage());
}

$_SESSION['sad'] = serialize($sad);

header('Content-Type: application/json');
echo json_encode($sad);

?>