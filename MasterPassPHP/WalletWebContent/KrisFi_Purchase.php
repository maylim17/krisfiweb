<?php
require_once('Controller/MasterPassController.php');
require_once('Controller/MasterPassHelper.php');

$profiles = MasterPassController::getShippingProfiles();
$data = array();

foreach($profiles as $value)
{
	$settings = parse_ini_file(MasterPassData::RESOURCES_PATH.MasterPassData::PROFILE_PATH.$value.MasterPassData::CONFIG_SUFFIX);

	$data[$value][] = $settings;
}

$sad = new MasterPassData();
$controller = new MasterPassController($sad);

session_start();
$_SESSION['sad'] = serialize($sad);
print_r($_SESSION);

?>