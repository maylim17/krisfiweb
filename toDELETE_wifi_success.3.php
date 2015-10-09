<?php
require_once('MasterPassPHP/WalletWebContent/Controller/MasterPassController.php');

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
// print_r($_SESSION);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>MasterPass SDK Sample Application</title>
	<link rel="stylesheet" type="text/css" href="MasterPassPHP/WalletWebContent/Content/Site.css">
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/jquery-1.5.1.js"></script> 
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/index.js"></script>
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/jquery-1.3.2.min.js"></script> <!-- Needed for tooltips only -->
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/commonToolTips.js"></script>
	<script type="text/javascript">
	</script>
	
	<script>
	function getWifi(plan) {
		
		if (plan=="") {
			document.getElementById("txtHint").innerHTML="";
			return;
		} 
		
		if (plan=="Hack") {
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					jsonResp = xmlhttp.responseText;   
					jsonObj = JSON.parse(jsonResp);
					
					document.getElementById("message").innerHTML = "WiFi promocode obtained successfully! Please proceed to our <a href='http://oa-nxt.demo.onair.aero/OA/en/laptop' target='_blank'>OnAir Portal</a> to redeem your Inflight Internet with the promocode.";
					document.getElementById("promoCode").innerHTML = "Promocode: " + jsonObj.promocode;
					document.getElementById("expiryDate").innerHTML = "Expiry Date: " + jsonObj.expiryDate;
				}
				else if (xmlhttp.status==404) {
					document.getElementById("message").innerHTML = "Oops, there is a problem with the system! Please contact an inflight staff for assistance.";
				}
			}
			xmlhttp.open("GET","http://projectkrisfi.com:8888/get_wifi.php",true);
			xmlhttp.send();
		} 
	}
</script>
	
</head>
<body>
	<div class="page">
		<div id="header">
			<div id="title">
				<h1>MasterPass SDK Sample Application</h1>
			</div>
			<div id="logindisplay">&nbsp;</div>
		</div>
		
		<div id="main">
			<h1>Thanks for watching the advertisement!</h1>
			<!--<p>Following is the information that will be used to interact with
				the MasterCard API:</p>-->
				
				<br><br>

			<fieldset>
				<legend>Redeem your WiFi</legend>
				<br>
				<button onclick="getWifi('Hack')">Get your WiFi promocode for inflight Internet!</button>
        
				<br><br>
				
				<div id="message"><b>message info will be listed here.</b></div>
				<div id="promoCode"><b>promoCode info will be listed here.</b></div>
				<div id="expiryDate"><b>expiryDate info will be listed here.</b></div>
			</fieldset>
			
			<form id="merchantInfo" name="merchantInfo" method="POST">
				
				
				<div style="display: none;">
					<fieldset>
						<legend>Redirect Options</legend>
						<table id="merchantOptions" class="ui-responsive">
							<tr>
								<td>
								</td>
								<td class="errorText">
									<span id="shippingSuppressionErrorMessage">Shipping Suppression cannot be used when Xml Version is less then v2.</span>
								</td>
								<td class="errorText">
									<span id="rewardsProgramErrorMessage">Rewards Program cannot be used when Xml Version is less then v4.</span>
								</td>
								<td class="errorText">
									<span id="authLevelErrorMessage">Authentication Level Basic cannot be used when Xml Version is less then v3.</span>
								</td>
								<td class="errorText">
									<span id="shippingProfileErrorMessage">Shipping Profiles cannot be used when Xml Version is less then v4.</span>
								</td>
								<td>
								</td>
							</tr>
							<tr>
								<td>
									XML Version
									<span class='tooltip' id='xmlversion'>[?]</span>
								</td>
								<td>
									<select name="xmlVersionDropdown" id="xmlVersionDropdown">
											<option selected="selected"  value="v6">v6</option>
											<!--<option value="v5">v5</option>
											<optionvalue="v4">v4</option>
											<option value="v3">v3</option>
											<option value="v2">v2</option>
											<option value="v1">v1</option>
											-->
									</select>
								</td>
								<td>
									Suppress Shipping Address Enable
									<span class='tooltip' id='shippingsuppression'>[?]</span>
								</td>
								<td>
									<select name="shippingSuppressionDropdown" id="shippingSuppressionDropdown">
										<option value="true">True</option>
										<option selected="selected" value="false">False</option>
									</select>
								</td>
								<td>
									Loyalty Enabled
									<span class='tooltip' id='rewards'>[?]</span>
								</td>
								<td>
									<select name="rewardsDropdown" id="rewardsDropdown">
										<option value="true">True</option>
										<option selected="selected" value="false">False</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									Request Basic Checkout
									<span class='tooltip' id='authlevel'>[?]</span>
								</td>
								<td>
									<input type="checkbox" name="authenticationCheckBox" id="authenticationCheckBox">
								</td>						
							</tr>
							<tr>
								<td>
									Allowed Card Types
									<span class='tooltip' id='acceptedcards'>[?]</span>
								</td>
								<td width=150>
									<table >
										<tr>
											<td>
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="master" id="master" checked="checked">MasterCard 
											</td>
											<td>
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="amex" id="amex" checked="checked">Amex
											</td>
											<td>	
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="diners" id="diners" checked="checked">Diners
											</td>
										</tr>
										<tr>
											<td>	 
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="discover" id="discover" checked="checked">Discover
											</td>
											<td>	 
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="maestro" id="maestro" checked="checked">Maestro
											</td>
											<td>	 
												<input type="checkbox" name="acceptedCardsCheckbox[]" value="visa" id="visa" checked="checked">Visa
											</td>
										</tr>
									</table>
								</td>
								<td>
									Private Label Card
									<span class='tooltip' id='privatelabel'>[?]</span>
								</td>
								<td>
									<input type="text" name="privateLabelText" id="privateLabelText">
								</td>	
							</tr>
						</table>
					</fieldset>
				</div>
				
				<fieldset>
					<legend>Item Details</legend>
					<p>ITEM DESCRIPTION</p>
					<input id="krisfipurchase" value="KrisFi Purchase" type="submit">
				</fieldset>
			</form>
			
		</div>

		<div id="footer"></div>
	</div>
	
    
	<script>
	$('#krisfipurchase').click(function(event) {
		var checkedCheckboxes = $('input[name="acceptedCardsCheckbox[]"]:checked').length;
		 
		// if(checkedCheckboxes == 0){
		// 	alert("There are no Cards selected");
		// 		event.preventDefault();
		// }
		// else{
			$("#merchantInfo").attr("action", "MasterPassPHP/WalletWebContent/KrisFi_Purchase.php");
			$("#merchantInfo").submit();
		// }
	});
	
	
	</script>

</body>
</html>