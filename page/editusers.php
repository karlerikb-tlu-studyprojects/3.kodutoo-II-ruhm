<?php

require("../functions.php");

	if(!isset($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	if($_SESSION["userLevel"] != 2) {
		header("Location: user.php");
		exit();
	}
		
	if(!isset($_GET["id"])) {
		header("Location: data.php");
		exit();
	}
	
	
$singleUser = $User->getSingleUser($_GET["id"]);	
	
	
	if(isset($_POST["update"])) {
		$User->updateUserInfo($Helper->cleanInput($_POST["firstName"]), $Helper->cleanInput($_POST["lastName"]), $Helper->cleanInput($_POST["country"]), $Helper->cleanInput($_POST["address"]), $Helper->cleanInput($_POST["phoneNumber"]), $Helper->cleanInput($_POST["userLevel"]), $Helper->cleanInput($_POST["id"]));
		header("Location: editusers.php?id=".$_GET["id"]."&success=true");
		exit();
	}
	





?>


<p>
<a href="data.php"><- tagasi</a>
</p>


<h2>Muuda kasutajainfot</h2>

<form method="POST">
	<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
	
	<label>Eesnimi</label>
		<input type="text" name="firstName" value="<?php echo $singleUser->firstname; ?>">
	<br><br>
	
	<label>Perekonnanimi</label>
		<input type="text" name="lastName" value="<?php echo $singleUser->lastname; ?>">
	<br><br>
	
	<label>Riik</label>
		<input type="text" name="country" value="<?php echo $singleUser->country; ?>">
	<br><br>
	
	<label>Aadress</label>
		<input type="text" name="address" value="<?php echo $singleUser->address; ?>">
	<br><br>
	
	<label>Telefon</label>
		<input type="text" name="phoneNumber" value="<?php echo $singleUser->phonenumber; ?>">
	<br><br>
	
	<label>Kasutajaõigus (0 = ban, 1 = user, 2 = admin)</label>
		<input type="text" name="userLevel" value="<?php echo $singleUser->userlevel; ?>">
	<br><br>
	
	<input type="submit" name="update" value="Salvesta"><br><br>

</form>



