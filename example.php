<?php

// To add printers to your account follow the following link 
// https://support.google.com/cloudprint/answer/1686197
/**
 * PHP implementation of Google Cloud Print
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */


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