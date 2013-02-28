/*
@topic 	: 	PAYPAL PROFILE ACCESS DEMO
@type 	: 	DEMO
@domain	:	http://ngiriraj.com
@path		:	http://ngiriraj.com/socialMedia/paypal_oauth/index.php
@author	:	Giriraj Namachivayam
@date		:	FEB 28, 2013
@license	:	FREE to Use
*/

<h1>Paypal profile access by using oAuth in PHP Demo </h1>
<?php
session_start();

// LOGOUT
if ($_GET['logout'] == 'true'){
	$_SESSION['paypal_user']="";	
}


if (strlen($_SESSION['paypal_user'])){
	// LOGGED USER
	echo "<pre>";
	print_r($_SESSION['profile']);
	echo "</pre>";
	echo "<br><BR> <a href='?logout=true'>LOGOUT</a>";
}else{
	// LOGIN
?>
	<a href='paypal.php' title='Paypal oAuth Login'>
	<img src='https://www.paypalobjects.com/en_US/Marketing/i/btn/login-with-paypal-button.png'>
	</a>
<?
}
?>
?>