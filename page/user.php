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
	
	if($_SESSION["userLevel"] == 0) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])) {
		$msg = $_SESSION["message"];
		unset($_SESSION["message"]);
	}
	
	if(isset($_POST["selectCampSite"]) && !empty($_POST["selectCampSite"]) &&
	isset($_POST["bookStartDate"]) && !empty($_POST["bookStartDate"]) &&
	isset($_POST["bookEndDate"]) && !empty($_POST["bookEndDate"])
	) {
		$Booking->saveUserCampSite($Helper->cleanInput($_POST["selectCampSite"]), $Helper->cleanInput($_POST["bookStartDate"]), $Helper->cleanInput($_POST["bookEndDate"]));
	}

$q = "";


$bookingDatesStart = $Booking->getAllStartDates();
$bookingDatesEnd = $Booking->getAllEndDates();



	if(isset($_GET["sort"]) && isset($_GET["direction"])) {
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	} else {
		$sort = "id";
		$direction = "ascending";
	}
	

	

$userbookings = $Booking->getAllUserBookings($sort, $direction);
$campsites = $Booking->getAllCampSites($q, $sort, $direction);
	
	

require("../header.php");
?>

<h1>Kasutaja leht</h1>

<?=$msg;?>
<p>
	Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?>!
	<br>
	<a href="?logout=1">Logi välja</a>
</p>
<br>


<!-- **** Platsi broneerimisvorm **** -->

<h2>Broneeri plats</h2>

<form method="POST">

	<label>Vali plats</label>
	<select name="selectCampSite" type="text">	
		<?php
		
			$listHtml = "";
			
			foreach($campsites as $c) {
				$listHtml .= "<option value='".$c->id."'>".$c->campsite."</option>";
			}
			echo $listHtml;
		
		?>
	</select>
	<br><br>
	
	<label>Vali algusaeg</label>
	<select name="bookStartDate" type="text">
		<?php
		
			$listHtml = "";
			
			foreach($bookingDatesStart as $s) {
				$listHtml .= "<option value='".$s->id."'>".$s->fulldate_start."</option>";
			}
			echo $listHtml;
			
		?>
	</select>
	<br><br>
	
	<label>Vali lõpuaeg</label>
	<select name="bookEndDate" type="text">
		<?php
		
			$listHtml = "";
			
			foreach($bookingDatesEnd as $e) {
				$listHtml .= "<option value='".$e->id."'>".$e->fulldate_end."</option>";
			}
			echo $listHtml;
			
		?>
	</select>
	<br><br>
	
	<input type="submit" value="Broneeri">
	
<h2>Kasutaja broneeringud</h2>
	
<?php

$direction = "ascending";
	
	if(isset($_GET["direction"])) {
		if($_GET["direction"] == "ascending") {
			$direction = "descending";
		}
	}

	$html = "<table>";
	$html .= "<tr>";
		$html .= "<th><a href='?sort=campsite&direction=".$direction."'>Plats</a></th>";
		$html .= "<th><a href='?sort=fulldate_start&direction=".$direction."'>Algus</a></th>";
		$html .= "<th><a href='?sort=fulldate_end&direction=".$direction."'>Lõpp</a></th>";
	$html .= "</tr>";

	foreach($userbookings as $b) {
		$html .= "<tr>";
			$html .= "<td>".$b->campsite."</td>";
			$html .= "<td>".$b->fulldate_start."</td>";
			$html .= "<td>".$b->fulldate_end."</td>";
			$html .= "<td><a href='editbookings.php?id=".$b->id."'>muuda</a></td>";
		$html .= "</tr>";
	}
	$html .= "</table>";
	echo $html;
	
?>
	
	

</form>


















<?php require("../footer.php"); ?>