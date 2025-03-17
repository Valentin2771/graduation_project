<?php

$n = 8;

function stringGenerator($n){
	$mystring = '';
	$alphaNum = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for($i = 0; $i < $n; $i++){
		$ind = rand(0, strlen($alphaNum) - 1);
		$mystring .= $alphaNum[$ind];
	}
	return $mystring;
}