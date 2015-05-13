<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Config.php';
require_once 'HttpRequest.Class.php';

if(isset($_GET['op']) && $_GET['op']=="getauth") {
	
	header("Location: ".$urlconfig['authorization_url']."?".http_build_query($redirectConfig));
	exit;
}

session_start();

// Google redirected back with code in query string.
if(isset($_GET['code']) && !empty($_GET['code'])) {
    
    $code = $_GET['code'];
    // create http request object and initialize with access token url
    $httpRequest = new HttpRequest($urlconfig['accesstoken_url']);
    $authConfig['code'] = $code;
    // Set data to be posted
    $httpRequest->setPostData($authConfig);
    // Send request
    $httpRequest->send();
    // Get request response
    $response = $httpRequest->getResponse();
    // parse json data
    $responseObj = json_decode($response);
    $accessToken = $responseObj->access_token;
    $_SESSION['accessToken'] = $accessToken;
    
    header("Location: example.php");
}

?>