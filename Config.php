<?php
/*
PHP implementation of Google Cloud Print
Author, Yasir Siddiqui

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
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