<?php

$server=""; // usually locahost
$dbname="hcode_crud";
$username=""; // usually root on the local machine
$port=3306;
$charset="utf8mb4";
$password=""; // root or username corresponding password

try{
	$connection = new PDO("mysql:host=$server; dbname=$dbname; port=$port; charset=$charset", $username, $password); 
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
} catch (PDOException $e){
	
	echo "Failed to connect to database...<br>";
	// echo $e->getMessage(); // For a further log
	exit;
}
