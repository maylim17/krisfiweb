<?php
	// returns a JSON object containing all menu items

	ini_set('default_charset', 'utf-8');

	$response = array();
	$response["success"] = 0;
	$response["message"] = "Unspecified error."; 
	
	include "db_connect.php"; 	

	if (mysqli_connect_errno()) {
		$sqlError = "Connection to database failed: %s\n" + mysqli_connect_error();
		$response["message"] = $sqlError;
	} else  {
		$result = $mysqli->query("SELECT * from CATALOG_TABLE");

		if ($result && mysqli_num_rows($result) > 0) {
			//successful
			$response["success"] = 1;
			$response["message"] = "Successful";
			$response["items"] = array();
			
			while($row = mysqli_fetch_array($result)) {
				$item = array();
				$item["id"] = $row["ID"];
				$item["category"] = $row["CATEGORY"];
				$item["subcategory"] = $row["SUBCATEGORY"];
				$item["title"] = $row["TITLE"];
				$item["location"] = $row["LOCATION"];
				$item["description"] = $row["DESCRIPTION"];
				$item["price"] = $row["PRICE"];
				$item["reward"] = $row["REWARD"];
				$item["ad_type"] = $row["AD_TYPE"];
				$item["ad_content"] = $row["AD_CONTENT"];
				array_push($response["items"], $item);
			}
			
		} else {
			$response["message"] = "Error: Query did not return a result";
		}
	}
	//echo $response["items"];
	//echo '<html><head>content-type: text/html; charset: utf-8</head><body>'+json_encode($response)+'</body></html>';
	echo json_encode($response);
?>