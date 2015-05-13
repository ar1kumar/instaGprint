<?php
	
	//Cofig for instagram API
	$hastag = "instaCats";
	$insta_clientID = "YOUR INSTAGRAM CLIENT ID";
		
	echo "Running...";
	
	global $output;
	$output = array();
	
	//$result;
	
	$url = array(); 
	$id = array();
	$uname = array();
	
	$jsonurl = "https://api.instagram.com/v1/tags/".$hasgtag."/media/recent?client_id=".$insta_clientID;
	$json = file_get_contents($jsonurl);
	$json_output = json_decode($json);
	
	for($j=0;$j<count($json_output->data);$j++){
		//echo count($json_output->data);
		/*
		print_r ($json_output->data[$j]->images->standard_resolution->url);
		print_r ($json_output->data[$j]->caption->id);
		print_r ($json_output->data[$j]->user->username);
		*/
		
		array_push($id, $json_output->data[$j]->caption->id);
		array_push($url, $json_output->data[$j]->images->standard_resolution->url);
		array_push($uname, $json_output->data[$j]->user->username);
		
	}
	//exit;
	
	//connect to database

	// db configuration
	if(!function_exists('database_connection')) {
	
	     function database_connection() {
	         
	          /* DB Auth Details */
	          define("DB_SERVER", "YOUR DB SERVER");
	          define("DB_USER", "USERNAME");
	          define("DB_PASS", "PASSWORD");
	          define("DB_NAME", "DATABASE NAME");
	          /* DB Auth Details */
	    
	          $set = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
	          return $set;
	     }
	    
	}
	
	// making connection with db
	 $conn = database_connection();
	//var_dump($conn);
	
	if($conn) {
		//echo "Connection successful";
	} else {
	    echo "DataBase connection error";
	}
	
	for($i=0;$i<count($id);$i++){
		
		
		//check if the image already exist
		$query = "SELECT * FROM insta_print WHERE img_id='$id[$i]' OR print = 'false'";
		$del = $conn->prepare($query);
		$del->execute();
		$count = $del->rowCount();
		
		if($count==0)
		{
			//doesn't exist, new image. save it
			save_image($id[$i],$url[$i],$uname[$i]);
			
		}
		else
		{
			//echo "image already exist";
			echo 'null';
			//header("Location: example.php");
		}
		
	}

	//header('Content-Type: application/json');
	//$object = (object)$output;
	//echo json_encode($object);
	//header('Location: index.php?id='.$img_id.'&url='.$img_url.'&uname='.$uname);
	
	function save_image($getId, $getUrl, $getName){
	
		$params = array(
			$getName,   
			$getId,
			$getUrl
		);
		//submitting data
		$conn = database_connection();
		$stmt = $conn->prepare("INSERT INTO `insta_print` (user_name, img_id, img_url) VALUES (?,?,?)");
				 
		//execute the query
		$res = $stmt->execute($params);
	
		//check for successful query or error
		if($res){
			echo 'success';
			global $output;
			array_push($output, $getId.'#&'.$getUrl.'#&'.$getName);
			//header('Location: example.php?id='.$getId.'&url='.$getUrl);
			//$result = $getUrl;
		}else{
			//echo 'error';
			echo 'error';
		}
	}
		
		

?>