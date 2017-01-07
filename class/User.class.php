<?php
class User {
	
	private $connection;
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	// funktsioonid
	
	
	function signUp ($email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber, $userlevel) {
		
		$stmt = $this->connection->prepare("INSERT INTO booking_users (email, password, firstname, lastname, day, month, year, gender, country, address, phonenumber, dateofbirth, userlevel) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		echo $this->connection->error;
				
		$dateofbirth = $year."-".$month."-".$day;
		
		$stmt->bind_param("ssssiiisssssi", $email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber, $dateofbirth, $userlevel);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";	
		} else {
			echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	
	function login ($email, $password) {
		$error = "";
		$stmt = $this->connection->prepare("SELECT id, email, password, firstname, lastname, userlevel FROM booking_users WHERE email = ?");
		
		echo $this->connection->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $firstNameDb, $lastNameDb, $userlevel);
		$stmt->execute();
		
		if($stmt->fetch()) {
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["userFirstName"] = $firstNameDb;
				$_SESSION["userLastName"] = $lastNameDb;
				$_SESSION["userLevel"] = $userlevel;
				
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
				if($userlevel == 1) {
					header("Location: user.php");
					exit();
				} else {
					if($userlevel == 2) {
						header("Location: data.php");
						exit();
					} else {
						$error = "oled bännitud!";
					}
				}
			} else {
				$error = "vale parool";
			}
		} else {
			$error = "ei ole sellist emaili";
		}
		return $error;
	}
	
	
	function getAllUsers($q, $sort, $direction) {
		
		$allowedSortOptions = ["id", "firstname", "lastname", "email", "gender", "dateofbirth", "country", "address", "phonenumber", "userlevel"];
		
		if(!in_array($sort, $allowedSortOptions)) {
			$sort = "id";
		}
		
		$orderBy = "ASC";
		if($direction == "descending") {
			$orderBy = "DESC";
		}
		
		if($q == "") {
			
			$stmt = $this->connection->prepare("
				SELECT id, firstname, lastname, email, gender, dateofbirth, country, address, phonenumber, userlevel FROM booking_users
				ORDER BY $sort $orderBy
			");
			echo $this->connection->error;
		} else {
			
			$searchWord = "%".$q."%";
			
			$stmt = $this->connection->prepare("
				SELECT id, firstname, lastname, email, gender, dateofbirth, country, address, phonenumber, userlevel FROM booking_users
				WHERE (firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR gender LIKE ? OR dateofbirth LIKE ? OR country LIKE ? OR address LIKE ?)
				ORDER BY $sort $orderBy
			");
			$stmt->bind_param("sssssss", $searchWord, $searchWord, $searchWord, $searchWord, $searchWord, $searchWord, $searchWord);
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $firstname, $lastname, $email, $gender, $dateofbirth, $country, $address, $phonenumber, $userlevel);
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
			$user->userlevel = $userlevel;
			array_push($result, $user);
		}
		$stmt->close();
		return $result;
	}
	
	function getSingleUser($currentid) {
		$stmt = $this->connection->prepare("SELECT firstname, lastname, country, address, phonenumber, userlevel FROM booking_users WHERE id = ?");
		$stmt->bind_param("i", $currentid);
		$stmt->bind_result($firstname, $lastname, $country, $address, $phonenumber, $userlevel);
		$stmt->execute();
		
		$singleUser = new StdClass();
		if($stmt->fetch()) {
			$singleUser->firstname = $firstname;
			$singleUser->lastname = $lastname;
			$singleUser->country = $country;
			$singleUser->address = $address;
			$singleUser->phonenumber = $phonenumber;
			$singleUser->userlevel = $userlevel;
		} else {
			header("Location: data.php");
			exit();
		}
		$stmt->close();
		return $singleUser;
	}
	
	function updateUserInfo($firstname, $lastname, $country, $address, $phonenumber, $userlevel, $currentid) {
		$stmt = $this->connection->prepare("UPDATE booking_users SET firstname = ?, lastname = ?, country = ?, address = ?, phonenumber = ?, userlevel = ? WHERE id = ?");
		$stmt->bind_param("ssssiii", $firstname, $lastname, $country, $address, $phonenumber, $userlevel, $currentid);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus!";
		}
		$stmt->close();
	}
	
	
	
	




































	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>