<?php
/*
PHP implementation of Google Cloud Print
*/
    
    $redirectConfig = array(
        'client_id' 	=> 'YOUR GOOGLE APP CLIENT ID',
        'redirect_uri' 	=> 'URL OF THE SITE',
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/cloudprint',
    );
    
    $authConfig = array(
        'code' => '',
        'client_id' 	=> 'YOUR GOOGLE APP CLIENT ID',
        'client_secret' => 'YOUR CLIENT SECRET',
        'redirect_uri' 	=> '..baseURL/oAuthRedirect.php',	//Redirect the app to oauth file after the user signs in
        "grant_type"    => "authorization_code"
    );
    
    $urlconfig = array(	
        'authorization_url' 	=> 'https://accounts.google.com/o/oauth2/auth',
        'accesstoken_url'   	=> 'https://accounts.google.com/o/oauth2/token',
    );
    
?>