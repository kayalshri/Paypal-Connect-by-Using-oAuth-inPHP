<?php 
/*
@topic 	: 	PAYPAL PROFILE ACCESS DEMO
@type 	: 	DEMO
@domain	:	http://ngiriraj.com
@path	:	http://ngiriraj.com/socialMedia/paypal_oauth/index.php
@author	:	Giriraj Namachivayam
@date	:	FEB 28, 2013
@license:	FREE to Use
*/
session_start();

/* Paypal app details */
$client_id = 'xxxxxxxxxxxx';			// Update your paypal client id
$client_secret = 'xxxxxxxxxxxxxxxxxxxx';	// Update your client secret
$scopes = 'email profile';                    //e.g. openid email profile https://uri.paypal.com/services/paypalattributes
$app_return_url = 'http://ngiriraj.com/socialMedia/paypal_oauth/paypal.php';  // Change
$nonce = time() . rand();

$code = $_REQUEST["code"];

if(empty($code)) {
	#IF the code paramater is not available, load the auth url.
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    $paypal_auth_url = "https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?"
			."client_id=".$client_id
			."&response_type=code"
			."&scope=".$scopes
			."&nonce=".$nonce
			."&state=".$_SESSION['state']
        	."&redirect_uri=".urlencode($app_return_url);
	
	header("Location: $paypal_auth_url");     
}else{
	/* GET Access TOKEN */
    $token_url = "https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice";    
	$postvals = "client_id=".$client_id
			."&client_secret=".$client_secret
			."&grant_type=authorization_code"
			."&code=".$code;


    $ch = curl_init($token_url);
	$options = array(
                CURLOPT_POST => 1,
                CURLOPT_VERBOSE => 1,
                CURLOPT_POSTFIELDS => $postvals,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSLVERSION => 3
	);
	curl_setopt_array( $ch, $options );
	$response = curl_exec($ch);
	curl_close($ch);
	$atoken = json_decode($response);

	/* GET PROFILE DETAILS */
	$profile_url = "https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo?"
			."schema=openid"
			."access_token=".$atoken->access_token;
			
	$ch = curl_init($profile_url);
	$options = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSLVERSION => 3
	);
	curl_setopt_array( $ch, $options );
	$response = curl_exec($ch);
	curl_close($ch);
	$profile= json_decode($response,true);	// PROFILE DETAILs in Array format


	/* Update user details in session */
	$_SESSION['paypal_user'] = "true";
	$_SESSION['profile'] = $profile;
    
	/* Redirect home page*/
	echo("<script> top.location.href='index.php'</script>");
}
?>