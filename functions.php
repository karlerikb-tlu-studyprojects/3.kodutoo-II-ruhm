<?php

	require("../../../../config.php");

	session_start();
	
	
	
	//ühendus
	$database = "if16_karlerik";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Booking.class.php");
	$Booking = new Booking($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	

	
	
	
	
	
	
	
	
	




?>