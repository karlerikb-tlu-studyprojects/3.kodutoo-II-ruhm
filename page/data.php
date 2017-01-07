<?php

	require("../functions.php");
	
	if(!isset($_SESSION["userId"] )) {
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"] )) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	if($_SESSION["userLevel"] != 2) {
		header("Location: user.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"] )) {
		$msg = $_SESSION["message"];
		unset($_SESSION["message"] );
	}
	
$createCampSiteError = "";

	
	if(isset($_POST["createCampSite"] )) {
		if(empty($_POST["createCampSite"] )) {
			$createCampSiteError = "Sisesta platsi nimi";
		} else {
			$createCampSite = $_POST["createCampSite"];
		}
	}
	
	if(isset($_POST["createCampSite"]) && !empty($_POST["createCampSite"])) {
		$Booking->saveCampSite($Helper->cleanInput($_POST["createCampSite"]));
	}
	
	
	
	if(isset($_GET["sort"]) && isset($_GET["direction"])) {
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	} else {
		$sort = "id";
		$direction = "ascending";
	}
	
	if(isset($_GET["q"])) {
		$q = $Helper->cleanInput($_GET["q"]);
		$campSiteData = $Booking->getAllCampSites($q, $sort, $direction);
		$userData = $User->getAllUsers($q, $sort, $direction);
	} else {
		$q = "";
		$campSiteData = $Booking->getAllCampSites($q, $sort, $direction);
		$userData = $User->getAllUsers($q, $sort, $direction);
	}
	
	
	


	
?>

<head>
<style>
table, td, th {    
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: auto;
}
</style>
</head>

<h1>Data</h1>

<?=$msg;?>


<p>
	Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?>!
	<br>
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Admin tööriistad</h2>

<form method="POST">
	
	<h3>Plats</h3>
	<label>Sisesta plats</label><br>
	<input name="createCampSite" type="text"> <?php echo $createCampSiteError; ?>
	
	<input type="submit" value="Sisesta">

</form>
<br>

<form>
	<label>Otsi</label><br>
	<input type="search" name="q" value="<?php echo $q; ?>">
	<input type="submit" name="search" value="Otsi">
</form>


<?php




$direction = "ascending";

	if(isset($_GET["direction"])) {
		if($_GET["direction"] == "ascending") {
			$direction = "descending";
		}
	}


	$html = "<table>";
	$html .= "<tr>";
		$html .= "<th><a href='?q=".$q."&sort=id&direction=".$direction."'>id</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=campsite&direction=".$direction."'>plats</a></th>";
	$html .= "</tr>";
	
	foreach($campSiteData as $c) {
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->campsite."</td>";
			$html .= "<td><a href='editsite.php?id=".$c->id."'>muuda</a></td>";
			
		$html .= "</tr>";
	}
	$html .= "</table>";
	
	echo $html;


?>







<h2>Kasutajatabel</h2>
<p>userlevel 0 = ban, 1 = user, 2 = admin</p>


<?php
	
	
	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th><a href='?q=".$q."&sort=id&direction=".$direction."'>id</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=firstname&direction=".$direction."'>firstname</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=lastname&direction=".$direction."'>lastname</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=email&direction=".$direction."'>email</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=gender&direction=".$direction."'>gender</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=dateofbirth&direction=".$direction."'>date of birth</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=country&direction=".$direction."'>country</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=address&direction=".$direction."'>address</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=phonenumber&direction=".$direction."'>phonenumber</a></th>";
		$html .= "<th><a href='?q=".$q."&sort=userlevel&direction=".$direction."'>userlevel</a></th>";
	$html .= "</tr>";
	
	
	foreach($userData as $u) {
		
		$html .= "<tr>";
			$html .= "<td>".$u->id."</td>";
			$html .= "<td>".$u->firstname."</td>";
			$html .= "<td>".$u->lastname."</td>";
			$html .= "<td>".$u->email."</td>";
			$html .= "<td>".$u->gender."</td>";
			$html .= "<td>".$u->dateofbirth."</td>";
			$html .= "<td>".$u->country."</td>";
			$html .= "<td>".$u->address."</td>";
			$html .= "<td>".$u->phonenumber."</td>";
			$html .= "<td>".$u->userlevel."</td>";
			$html .= "<td><a href='editusers.php?id=".$u->id."'>muuda</a></td>";
		$html .= "</tr>";
		
	}
		
	$html .= "</table>";
	
	echo $html;
	

	$listHtml = "<br><br><br>";
	
	




?>

<br>
























