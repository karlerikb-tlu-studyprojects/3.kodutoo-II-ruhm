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

	
	
	
	
	
	
$campSite = $Booking->getSingleCampSite($_GET["id"]);


	if(isset($_POST["update"])) {
		$Booking->updateCampsite($Helper->cleanInput($_GET["id"]), $Helper->cleanInput($_POST["campSite"]));
		header("Location: editsite.php?id=".$_GET["id"]."&success=true");
		exit();
	}
	
	if(isset($_POST["delete"])) {
		$Booking->deleteCampsite($Helper->cleanInput($_GET["id"]));
		header("Location: data.php");
		exit();
	}









?>


<p>
<a href="data.php"><- tagasi</a>
</p>


<h2>Muuda platsi</h2>

<form method="POST">
	<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
	
	<label>Plats</label>
		<input type="text" name="campSite" value="<?php echo $campSite->campsite; ?>">
	
	<br><br>
	<input type="submit" name="update" value="Salvesta"><br><br>
	<input type="submit" name="delete" value="Kustuta">

</form>


