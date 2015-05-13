<?php

require_once 'GoogleCloudPrint.php';

session_start();

include "check.php";

//print_r($output);

function update_db($new_id){

	// making connection with db
	 $conn = database_connection();
	
	if($conn) {
		//echo "Connection successful";
		$new_sql = 'UPDATE insta_print SET print = "true" WHERE img_id ='."'$new_id'" ;
		$pro = $conn->prepare($new_sql);
		$pro->execute();
		
		echo 'print done';
		
	} else {
	    echo "DataBase connection error";
	}
}

// Create object
$gcp = new GoogleCloudPrint();
$gcp->setAuthToken($_SESSION['accessToken']);

$printers = $gcp->getPrinters();

$printerid = "";
if(count($printers)==0) {
	
	echo "Could not get printers";
	//exit;
}
else {
	
	//Define Printer
	$printerid = $printers[0]['id']; // Pass id of any printer to be used for print
	//$printerid = "Enter the printer ID manually if it doesn't work";
	
	// Send document to the printer
	for($k=0;$k<count($output);$k++){
		$new_data = explode("#&", $output[$k]);
		$img_url = $new_data[1];
		$img_id = $new_data[0];
		
		//$img_url = "https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/e15/11189553_828112557266632_991049624_n.jpg";
		
		$resarray = $gcp->sendPrintToPrinter($printerid, "hashtag pull", $img_url, "image/jpeg");
	
		if($resarray['status']==true) {
		
			echo "Document has been sent to printer and should print shortly.";
			update_db($img_id);
			
		}
		else {
			echo "An error occured while printing the doc. Error code:".$resarray['errorcode']." Message:".$resarray['errormessage'];
		}
	}
}

?>
<!-- Keep Running the job for every 15 seconds -->
<!-- Set a CRON on server side for automatic running -->
<meta http-equiv="refresh" content="15" />