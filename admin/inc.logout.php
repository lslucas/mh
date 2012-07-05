<?php
 
	session_start();
	include_once '_inc/global.php';
	unset($_SESSION);
	session_destroy();

	header('location: '.$rp.'index.php');
