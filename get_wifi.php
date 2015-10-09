<?php

	// create curl resource 
	$ch = curl_init(); 

	// set url 
	curl_setopt($ch, CURLOPT_URL, "http://57.191.0.124/ProviderProxy/Promotions/promocode/Hack"); 
	
	// set headers
	$headers = array( 
		"Accept: application/json", 
		"X-apiKey: bb11252a491d04006f318180ab5cf559"
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	//return the transfer as a string 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HTTPGET, 1);

	// $output contains the output string 
	$output = curl_exec($ch); 
	echo $output;

	// close curl resource to free up system resources 
	curl_close($ch);  

?>