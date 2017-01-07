<?php
class User {
	
	private $connection;
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	// funktsioonid
	
	
	function signUp ($email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber) {
		
		$stmt = $this->connection->prepare("INSERT INTO booking_users (email, password, firstname, lastname, day, month, year, gender, country, address, phonenumber, dateofbirth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		echo $this->connection->error;
				
		$dateofbirth = $year."-".$month."-".$day;
		
		$stmt->bind_param("ssssiiisssss", $email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber, $dateofbirth);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";	
		} else {
			echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	
	function login ($email, $password) {
		$error = "";
		$stmt = $this->connection->prepare("SELECT id, email, password, firstname, lastname FROM booking_users WHERE email = ?");
		
		echo $this->connection->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $firstNameDb, $lastNameDb);
		$stmt->execute();
		
		if($stmt->fetch()) {
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {
				echo "Kasutaja logis sisse ".$id;
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["userFirstName"] = $firstNameDb;
				$_SESSION["userLastName"] = $lastNameDb;
				
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
				header("Location: data.php");
				exit();
				
			} else {
				$error = "vale parool";
			}
		} else {
			$error = "ei ole sellist emaili";
		}
		return $error;
	}
	
	
	function getAllUsers() {
		
		$stmt = $this->connection->prepare("
			SELECT id, firstname, lastname, email, gender, dateofbirth, country, address, phonenumber FROM booking_users
		");
		
		$stmt->bind_result($id, $firstname, $lastname, $email, $gender, $dateofbirth, $country, $address, $phonenumber);
		$stmt->execute();
		
		$result = array();
		
		while($stmt->fetch()) {
			$user = new StdClass();
			$user->id = $id;
			$user->firstname = $firstname;
			$user->lastname = $lastname;
			$user->email = $email;
			$user->gender = $gender;
			$user->dateofbirth = $dateofbirth;
			$user->country = $country;
			$user->address = $address;
			$user->phonenumber = $phonenumber;
			array_push($result, $user);
		}
		$stmt->close();
		return $result;
	}
	
	
	
	




































	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>