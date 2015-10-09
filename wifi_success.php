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
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Kris-Fi</title>

<!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>

<!-- font awesome -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<!-- bootstrap -->
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />

<!-- animate.css -->
<link rel="stylesheet" href="assets/animate/animate.css" />
<link rel="stylesheet" href="assets/animate/set.css" />

<!-- gallery -->
<link rel="stylesheet" href="assets/gallery/blueimp-gallery.min.css">

<!-- favicon -->
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">


<link rel="stylesheet" href="assets/style.css">


	<!--<link rel="stylesheet" type="text/css" href="MasterPassPHP/WalletWebContent/Content/Site.css">-->
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/jquery-1.5.1.js"></script> 
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/index.js"></script>
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/jquery-1.3.2.min.js"></script> <!-- Needed for tooltips only -->
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="MasterPassPHP/WalletWebContent/Scripts/tooltips/commonToolTips.js"></script>
	
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
					
					document.getElementById("message").innerHTML = "Wi-Fi promo code obtained successfully! Please proceed to our <a href='http://oa-nxt.demo.onair.aero/OA/en/laptop' target='_blank'>OnAir Portal</a> to redeem your Inflight Internet with the promo code.";
					document.getElementById("promoCode").innerHTML = "<b>Promo code: </b>" + jsonObj.promocode;
					document.getElementById("expiryDate").innerHTML = "<b>Expiry Date: </b>" + jsonObj.expiryDate;
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
<div class="topbar animated fadeInLeftBig"></div>

<!-- Header Starts -->
<div class="navbar-wrapper">
      <div class="container">

        <div class="navbar navbar-default navbar-fixed-top" role="navigation" id="top-nav">
          <div class="container">
            <div class="navbar-header">
              <!-- Logo Starts -->
              <a class="navbar-brand" href="#home"><img src="images/logo.png" alt="logo" width="22%"></a>
              <!-- #Logo Ends -->


              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>

            </div>


            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
              <ul class="nav navbar-nav navbar-right scroll">
                 <li class="active"><a href="#works">Home</a></li>
                 <li ><a href="#about">About</a></li>
                 <li ><a href="#partners">Wi-Fi Usage</a></li>
                 <li ><a href="#contact">Contact</a></li>
              </ul>
            </div>
            <!-- #Nav Ends -->

          </div>
        </div>

      </div>
    </div>
<!-- #Header Starts -->

<!-- Cirlce Starts -->
<div id="about"  class="container spacer about">

<h2 class="text-center wowload fadeInUp"></h2>  
  
<h2 class="text-center wowload fadeInUp">Thank you for watching the advertisement!</h2>  
  <div class="process">
    
    
  <h3 class="text-center wowload fadeInUp">Redeem your Wi-Fi</h3>
  <div class="row text-center list-inline  wowload bounceInUp">
        
        <button onclick="getWifi('Hack')" class="btn btn-primary" style="width:50%">Get your Wi-Fi promo code for inflight Internet!</button>
		
        
				<br><br><br>
				
				<div id="message"><b></b></div>
				<br>
				<div id="promoCode"><b></b></div>
				<div id="expiryDate"><b></b></div>
        
    </div>
    
	<br>
	
	
	
      <h3 class="text-center wowload fadeInUp">Interested in the item?</h3>
  <div class="row text-center list-inline  wowload bounceInUp">
        
			<form id="merchantInfo" name="merchantInfo" method="POST">
				
				<!-- START OF HIDDEN DIV -->
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
        <!-- END OF HIDDEN DIV -->
				
				<fieldset>
					<br>
					<p>
						<img width="450" src="MasterPassPHP/WalletWebContent/images/cv.jpg"></img>
					</p>
					<br>
					<p>
						<b>Festive Aura Set</b> by <b>SK-II</b>
						<br>
						<i>SGD 532</i>
					</p>
					<input id="krisfipurchase" class="btn btn-primary" style="width:50%" value="Kris-Fi Purchase" type="submit">
				</fieldset>
			</form>
    </div>
  </div>
</div>
<!-- #Circe Ends -->

<!-- Footer Starts -->
<div class="footer text-center spacer">
<p class="wowload flipInX"><a href="#"><i class="fa fa-facebook fa-2x"></i></a> <a href="#"><i class="fa fa-instagram fa-2x"></i></a> <a href="#"><i class="fa fa-twitter fa-2x"></i></a> <a href="#"><i class="fa fa-flickr fa-2x"></i></a> </p>
Copyright 2015 Kris-Fi. All rights reserved.
</div>
<!-- # Footer Ends 
<a href="#works" class="gototop "><i class="fa fa-angle-up  fa-3x"></i></a> -->





<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title">Title</h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->    
</div>



<!-- jquery -->
<script src="assets/jquery.js"></script>

<!-- wow script -->
<script src="assets/wow/wow.min.js"></script>


<!-- boostrap -->
<script src="assets/bootstrap/js/bootstrap.js" type="text/javascript" ></script>

<!-- jquery mobile -->
<script src="assets/mobile/touchSwipe.min.js"></script>
<script src="assets/respond/respond.js"></script>

<!-- gallery -->
<script src="assets/gallery/jquery.blueimp-gallery.min.js"></script>

<!-- custom script -->
<script src="assets/script.js"></script>
	<script>
	$('#krisfipurchase').click(function(event) {
		var checkedCheckboxes = $('input[name="acceptedCardsCheckbox[]"]:checked').length;
		 
		// if(checkedCheckboxes == 0){
		// 	alert("There are no Cards selected");
		// 		event.preventDefault();
		// }
		// else{
			$("#merchantInfo").attr("action", "MasterPassPHP/WalletWebContent/KrisFi_Purchase.php");
			// $("#merchantInfo").attr("action", "cart.php");
			$("#merchantInfo").submit();
		// }
	});
	
	
	</script>

</body>
</html>