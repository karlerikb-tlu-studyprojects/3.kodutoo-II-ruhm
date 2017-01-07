<?php

	require("../functions.php");
	
	if(isset($_SESSION["userId"] )) {
		header("Location: data.php");
		exit();
	}
	


	$loginEmailError = "";
	$loginPasswordError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$nameError = "";
	$dateofBirthError = "";
	$addressError = "";
	$phoneNumberError = "";
	$gender = "";
	$loginEmail = "";
	$signupEmail = "";
	$firstName = "";
	$lastName = "";
	$dateDay = "";
	$dateMonth = "";
	$dateYear = "";
	$country = "";
	$address = "";
	$phoneNumber = "";
	$userLevel = "";
	
	

	
	
	//****LOGIN KASUTAJAGA****

	if(isset($_POST["loginEmail"] )) {
		if(empty($_POST["loginEmail"] )) {
			$loginEmailError = "Sisesta oma E-mail";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}

	if(isset($_POST["loginPassword"] )) {
		if(empty($_POST["loginPassword"] )) {
			$loginPasswordError = "Sisesta oma parool";
		} else {
			$loginPassword = $_POST["loginPassword"];
		}
	}
	
	//****REGISTREERI KASUTAJA****
	
	if(isset($_POST["signupEmail"] )) {
		if(empty($_POST["signupEmail"] )) {
			$signupEmailError = "See väli on kohustuslik";
		} else {
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if(isset($_POST["signupPassword"] )) {
		if(empty($_POST["signupPassword"] )) {
			$signupPasswordError = "Parool on kohustuslik";
		} else {
			if(strlen($_POST["signupPassword"] ) <8 ) {
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki";
			}
		}
	}
	
	
	if(isset($_POST["firstName"] )) {
		if(empty($_POST["firstName"] )) {
			$nameError = "Palun sisestage oma täisnimi";
		} else {
			$firstName = $_POST["firstName"];
		}
	}
	
	if(isset($_POST["lastName"] )) {
		if(empty($_POST["lastName"] )) {
			$nameError = "Palun sisestage oma täisnimi";
		} else {
			$lastName = $_POST["lastName"];
		}
	}
	
	if(isset($_POST["dateDay"] )) {
		if(empty($_POST["dateDay"] )) {
			$dateofBirthError = "Palun sisestage sünniaeg";
		} else {
			$dateDay = $_POST["dateDay"];
		}
	}
	
	if(isset($_POST["dateMonth"] )) {
		if(empty($_POST["dateMonth"] )) {
			$dateofBirthError = "Palun sisestage sünniaeg";
		} else {
			$dateMonth = $_POST["dateMonth"];
		}
	}
		
	if(isset($_POST["dateYear"] )) {
		if(empty($_POST["dateYear"] )) {
			$dateofBirthError = "Palun sisestage sünniaeg";
		} else {
			$dateYear = $_POST["dateYear"];
		}
	}
	
	if(isset($_POST["country"] )) {
		if(empty($_POST["country"] )) {
			$addressError = "Palun sisestage oma aadress";
		} else {
			$country = $_POST["country"];
		}
	}
	
	if(isset($_POST["address"] )) {
		if(empty($_POST["address"] )) {
			$addressError = "Palun sisestage oma aadress";
		} else {
			$address = $_POST["address"];
		}
	}
	
	if(isset($_POST["phoneNumber"] )) {
		if(empty($_POST["phoneNumber"] )) {
			$phoneNumberError = "Palun sisestage oma telefoninumber";
		} else {
			$phoneNumber = $_POST["phoneNumber"];
		}
	}
	
	if(isset($_POST["gender"] )) {
		if(!empty($_POST["gender"] )) {
			$gender = $_POST["gender"];
		}
	}
	
	if(isset($_POST["userLevel"] )) {
		if(!empty($_POST["userLevel"] )) {
			$userLevel = $_POST["userLevel"];
		}
	}
	
	if(isset($_POST["reset"])) {
		header("Location: login.php");
		exit();
	}
	
	
	
	
	//******
	
	
	if($signupEmailError == "" &&
		empty($signupPasswordError) &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["signupPassword"]) &&
		isset($_POST["firstName"]) &&
		isset($_POST["lastName"]) &&
		isset($_POST["dateDay"]) &&
		isset($_POST["dateMonth"]) &&
		isset($_POST["dateYear"]) &&
		isset($_POST["country"]) &&
		isset($_POST["address"]) &&
		isset($_POST["phoneNumber"]) &&
		isset($_POST["userLevel"])
		) {
			$password = hash("sha512", $_POST["signupPassword"]);
			$User->signUp($Helper->cleanInput($signupEmail), $Helper->cleanInput($password), $Helper->cleanInput($firstName), $Helper->cleanInput($lastName), $Helper->cleanInput($dateDay), $Helper->cleanInput($dateMonth), $Helper->cleanInput($dateYear), $Helper->cleanInput($gender), $Helper->cleanInput($country), $Helper->cleanInput($address), $Helper->cleanInput($phoneNumber), $Helper->cleanInput($userLevel));
		}
	
	
	$error = "";
	if(isset($_POST["loginEmail"]) && isset($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) && !empty($_POST["loginPassword"])
	) {
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
	}
	
	
	

?>









 <!DOCTYPE html>
<html>
<head>
	<title>Logi sisse või loo kasutaja</title>
</head>
<body>
	<h1>Logi sisse</h1>
	<form method="POST">
		<input name="loginEmail" type="text" placeholder="E-mail" value="<?=$loginEmail;?>"> <?php echo $loginEmailError; ?>
		<br><br>
	
		<input name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPasswordError; ?> <?php echo $error; ?>
		<br><br>
		
		<input type="submit" value="Logi sisse">
	
	</form>
	
	
	<h1>Loo kasutaja</h1>
	<form method="POST">
			<input name="signupEmail" type="text" placeholder="E-mail" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
		<br><br>
		
			<input name="signupPassword" type="password" placeholder="Parool"> <?php echo $signupPasswordError; ?>
		<br><br>
		
		<label>Nimi</label>
		<br>
			<input name="firstName" type="text" placeholder="Eesnimi" value="<?=$firstName;?>">
			<input name="lastName" type="text" placeholder="Perekonnanimi" value="<?=$lastName;?>"> <?php echo $nameError; ?>
		<br><br>
		
		<label>Sugu</label>
		<br>
			<?php if($gender == "male") { ?>
			<input name="gender" type="radio" value="male" checked> Mees	
			<?php } else { ?>
			<input name="gender" type="radio" value="male"> Mees
			<?php } ?>
			
			<?php if($gender == "female") { ?>
			<input name="gender" type="radio" value="female" checked> Naine
			<?php } else { ?>
			<input name="gender" type="radio" value="female"> Naine
			<?php } ?>
			
			<?php if($gender == "other") { ?>
			<input name="gender" type="radio" value="other" checked> Ei soovi öelda
			<?php } else { ?>
			<input name="gender" type="radio" value="other"> Ei soovi öelda
			<?php } ?> 
		<br><br>
		
		
		<label>Sünniaeg</label>
		<br>
			<input name="dateDay" type="number" min="1" max="31" placeholder="Päev" value="<?=$dateDay;?>">
			<input name="dateMonth" type="number" min="1" max="12" placeholder="Kuu" value="<?=$dateMonth;?>">
			<input name="dateYear" type="number" min="1900" max="2016" placeholder="Aasta" value="<?=$dateYear;?>"> <?php echo $dateofBirthError; ?>
		<br><br>
		
		<label>Aadress</label>
		<br>
			<input name="country" type="text" placeholder="Riik" value="<?=$country;?>">
			<input name="address" type="text" placeholder="Aadress" value="<?=$address;?>"> <?php echo $addressError; ?>
		<br><br>
		
		<label>Kontakttelefon</label>
		<br>
			<input name="phoneNumber" type="text" value="<?=$phoneNumber;?>"> <?php echo $phoneNumberError; ?>
		<br><br>
		
		<label>Kasutajaõigused (testimiseks)</label>
		<br>
			<?php if($userLevel == 1) { ?>
			<input name="userLevel" type="radio" value="1" checked> Tavakasutaja
			<?php } else { ?>
			<input name="userLevel" type="radio" value="1"> Tavakasutaja
			<?php } ?>
			
			<?php if($userLevel == 2) { ?>
			<input name="userLevel" type="radio" value="2" checked> Admin kasutaja
			<?php } else { ?>
			<input name="userLevel" type="radio" value="2"> Admin kasutaja
			<?php } ?>
		<br><br>
		
		<input type="submit" name="submit" value="Loo kasutaja">
		<input type="submit" name="reset" value="Kustuta andmed">
		
	</form>
	
</body>
</html>
		
		
		
		
		
		
		
		
		
		
		
		