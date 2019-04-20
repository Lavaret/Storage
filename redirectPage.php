<?php

 require_once('Services/DatabaseService.php');
 require_once('Services/MessageService.php');
 require_once('StorageController.php');
 session_start();

//set amount 
$amount = null;
if (array_key_exists('amount', $_POST)) {
	$amount = $_POST['amount'];
}

//set price
$price = null;
if (array_key_exists('price', $_POST)) {
	$price = $_POST['price'];
}

$storageController = new StorageController();
$message = new MessageService();

if ( (int) $amount && (float) $price) {
	$storageController->store($amount, $price);
}

if (array_key_exists("pull-amount", $_POST)) {

	try {
		$price = $storageController->pull($_POST["pull-amount"]);
	} catch (Exception $e) {
		$message->setErrorMessage($e->getMessage());
	}
	
	$message->setMessage('sell-info', 'Wartość sprzedaży: '.$price);
}

header("Location: index.php");