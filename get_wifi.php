<?php

	// create curl resource 
	$ch = curl_init(); 

	// set url 
	curl_setopt($ch, CURLOPT_URL, "http://57.191.0.124/ProviderProxy/Promotions/promocode/Hack"); 
	// set header
	curl_setopt($ch, CURLOPT_HEADER, "X-apiKey:bb11252a491d04006f318180ab5cf559");
	curl_setopt($ch, CURLOPT_HEADER, "Accept: application/json");

	//return the transfer as a string 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	// $output contains the output string 
	$output = curl_exec($ch); 
	echo $output;

	// close curl resource to free up system resources 
	curl_close($ch);  

?>