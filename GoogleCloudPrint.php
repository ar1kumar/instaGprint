<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

/*
PHP implementation of Google Cloud Print
*/

require_once 'HttpRequest.Class.php';

class GoogleCloudPrint {
	
	const PRINTERS_SEARCH_URL = "https://www.google.com/cloudprint/search";
	const PRINT_URL = "https://www.google.com/cloudprint/submit";
	
	private $authtoken;
	private $httpRequest;
	
	/**
	 * Function __construct
	 * Set private members varials to blank
	 */
	public function __construct() {
		
		$this->authtoken = "";
		$this->httpRequest = new HttpRequest();
	}
	
	/**
	 * Function setAuthToken
	 *
	 * Set auth tokem
	 * @param string $token token to set
	 */
	public function setAuthToken($token) {
		$this->authtoken = $token;
	}
	
	/**
	 * Function getAuthToken
	 *
	 * Get auth tokem
	 * return auth tokem
	 */
	public function getAuthToken() {
		return $this->authtoken;
	}
	
	/**
	 * Function getPrinters
	 *
	 * Get all the printers added by user on Google Cloud Print. 
	 * Follow this link https://support.google.com/cloudprint/answer/1686197 in order to know how to add printers
	 * to Google Cloud Print service.
	 */
	public function getPrinters() {
		
		// Check if we have auth token
		if(empty($this->authtoken)) {
			// We don't have auth token so throw exception
			throw new Exception("Please first login to Google");
		}
		
		// Prepare auth headers with auth token
		$authheaders = array(
		"Authorization: Bearer " .$this->authtoken
		);
		
		$this->httpRequest->setUrl(self::PRINTERS_SEARCH_URL);
		$this->httpRequest->setHeaders($authheaders);
		$this->httpRequest->send();
		$responsedata = $this->httpRequest->getResponse();
		// Make Http call to get printers added by user to Google Cloud Print
		$printers = json_decode($responsedata);
		// Check if we have printers?
		if(is_null($printers)) {
			// We dont have printers so return balnk array
			return array();
		}
		else {
			// We have printers so returns printers as array
			return $this->parsePrinters($printers);
		}
		
	}
	
	/**
	 * Function sendPrintToPrinter
	 * 
	 * Sends document to the printer
	 * 
	 * @param Printer id $printerid    // Printer id returned by Google Cloud Print service
	 * 
	 * @param Job Title $printjobtitle // Title of the print Job e.g. Fincial reports 2012
	 * 
	 * @param File Path $filepath      // Path to the file to be send to Google Cloud Print
	 * 
	 * @param Content Type $contenttype // File content type e.g. application/pdf, image/png for pdf and images
	 */
	public function sendPrintToPrinter($printerid,$printjobtitle,$filepath,$contenttype) {
		
	// Check if we have auth token
		if(empty($this->authtoken)) {
			// We don't have auth token so throw exception
			throw new Exception("Please first login to Google by calling loginToGoogle function");
		}
		// Check if prtinter id is passed
		if(empty($printerid)) {
			// Printer id is not there so throw exception
			throw new Exception("Please provide printer ID");	
		}
		// Open the file which needs to be print
		$handle = fopen($filepath, "rb");
		if(!$handle)
		{
			// Can't locate file so throw exception
			throw new Exception("Could not read the file. Please check file path.");
		}
		// Read file content
		
		$contents = file_get_contents($filepath);
		//$contents = fread($handle, filesize($filepath));
		fclose($handle);
		
		// Prepare post fields for sending print
		$post_fields = array(
				
			'printerid' => $printerid,
			'title' => $printjobtitle,
			'contentTransferEncoding' => 'base64',
			'content' => base64_encode($contents), // encode file content as base64
			'contentType' => $contenttype		
		);
		// Prepare authorization headers
		$authheaders = array(
			"Authorization: Bearer " . $this->authtoken
		);
		
		// Make http call for sending print Job
		$this->httpRequest->setUrl(self::PRINT_URL);
		$this->httpRequest->setPostData($post_fields);
		$this->httpRequest->setHeaders($authheaders);
		$this->httpRequest->send();
		$response = json_decode($this->httpRequest->getResponse());
		
		// Has document been successfully sent?
		if($response->success=="1") {
			
			return array('status' =>true,'errorcode' =>'','errormessage'=>"");
		}
		else {
			
			return array('status' =>false,'errorcode' =>$response->errorCode,'errormessage'=>$response->message);
		}
	}
	
	/**
	 * Function parsePrinters
	 * 
	 * Parse json response and return printers array
	 * 
	 * @param $jsonobj // Json response object
	 * 
	 */
	private function parsePrinters($jsonobj) {
		
		$printers = array();
		if (isset($jsonobj->printers)) {
			foreach ($jsonobj->printers as $gcpprinter) {
				$printers[] = array('id' =>$gcpprinter->id,'name' =>$gcpprinter->name,'displayName' =>$gcpprinter->displayName,
						    'ownerName' => $gcpprinter->ownerName,'connectionStatus' => $gcpprinter->connectionStatus,
						    );
			}
		}
		return $printers;
	}
}
