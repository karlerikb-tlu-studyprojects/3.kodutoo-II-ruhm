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
	
	
	// **** saveCampSite ****
	
	if(isset($_POST["createCampSite"]) && !empty($_POST["createCampSite"])) {
		$Booking->saveCampSite($Helper->cleanInput($_POST["createCampSite"]));
	}
	
	$campSiteData = $Booking->getAllCampSites();
	$userData = $User->getAllUsers();

	
	
	
?>



<h1>Data</h1>

<?=$msg;?>


<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?></a>!
	<br>
	<a href="?logout=1">Logi välja</a>
</p>


<br><br>
<h2>Admin tööriistad</h2>

<form method="POST">
	
	<h3>Plats</h3>
	<label>Sisesta plats</label><br>
	<input name="createCampSite" type="text"> <?php echo $createCampSiteError; ?>
	<br><br>
	
	<input type="submit" value="Sisesta">

</form>
<br>

<?php

	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th>ID</th>";
		$html .= "<th>Plats</th>";
	$html .= "</tr>";
	
	foreach($campSiteData as $c) {
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->campsite."</td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;


?>





<h2>Kasutajatabel</h2>


<?php
	
	
	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th>id</th>";
		$html .= "<th>firstname</th>";
		$html .= "<th>lastname</th>";
		$html .= "<th>email</th>";
		$html .= "<th>gender</th>";
		$html .= "<th>date of birth</th>";
		$html .= "<th>country</th>";
		$html .= "<th>address</th>";
		$html .= "<th>phonenumber</th>";
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
		$html .= "</tr>";
		
	}
		
	$html .= "</table>";
	
	echo $html;
	

	$listHtml = "<br><br><br>";
	
	




?>


























