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
		$result = $mysqli->query("SELECT * from MenuItems");

		if ($result && mysqli_num_rows($result) > 0) {
			//successful
			$response["success"] = 1;
			$response["message"] = "Successful";
			$response["menu"] = array();
			
			while($row = mysqli_fetch_array($result)) {
				$item = array();
				$item["id"] = $row["id"];
				$item["recommend"] = $row["recommend"];
				$item["name"] = $row["name"];
				$item["category"] = $row["category"];
				$item["options"] = $row["options"];
				$item["cost"] = $row["cost"];
				$item["costLarge"] = $row["cost_large"];
				array_push($response["menu"], $item);
			}
			
		} else {
			$response["message"] = "Error: Query did not return a result";
		}
	}
	//echo '<html><head>content-type: text/html; charset: utf-8</head><body>'+json_encode($response)+'</body></html>';
	echo json_encode($response);
?>