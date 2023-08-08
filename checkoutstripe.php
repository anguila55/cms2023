<?php
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';

require_once 'vendor/autoload.php';
require_once GLBRutaFUNC . '/constants.php'; //Idioma
$stripeSecretKey = 'sk_test_CGGvfNiIPwLXiDwaOfZ3oX6Y';
\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');
$token = $_POST['stripeToken'];
$email = $_POST['stripeEmail'];

$customer = \Stripe\Customer::create([
  'email' => $email,
  'source'  => $token,
]);

$charge = \Stripe\Charge::create([
  'customer' => $customer->id,
  'amount'   => 100,
  'currency' => 'eur',
]);

echo '<h1>Successfully charged 1€!</h1>';
?>