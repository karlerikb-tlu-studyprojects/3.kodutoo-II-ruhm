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
	
	if(!isset($_GET["id"])) {
		header("Location: data.php");
		exit();
	}

$q = "";
$sort = "";
$direction = "";


$booking = $Booking->getSingleUserBooking($_GET["id"]);

$campsites = $Booking->getAllCampSites($q, $sort, $direction);
$bookingDatesStart = $Booking->getAllStartDates();
$bookingDatesEnd = $Booking->getAllEndDates();
$userbookings = $Booking->getAllUserBookings($sort, $direction);




	if(isset($_POST["updateCampsite"]) && isset($_POST["updateStartDate"]) && isset($_POST["updateEndDate"])) {
		$Booking->updateBooking($Helper->cleanInput($_POST["updateCampsite"]), $Helper->cleanInput($_POST["updateStartDate"]), $Helper->cleanInput($_POST["updateEndDate"]), $_GET["id"]);
		header("Location: editbookings.php?id=".$_GET["id"]."&success=true");
		exit();
	}
	
	if(isset($_POST["delete"])) {
		$Booking->deleteBooking($_GET["id"]);
		header("Location: user.php");
		exit();
	}
	
















require("../header.php");
?>

<p>
	<a href="user.php"><- tagasi</a>
</p>

<h1>Sinu broneeringu muutmine</h1>

<h3>Broneering</h3>

<form>

	<input type="text" value="<?php echo $booking->campsite; ?>" disabled>
	<input type="text" value="<?php echo $booking->startdate; ?>" disabled>
	<input type="text" value="<?php echo $booking->enddate; ?>" disabled>

</form>

<h3>Vali uus broneering</h3>



<form method="POST">

	<label>Vali plats</label>
	<select name="updateCampsite" type="text">	
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
	<select name="updateStartDate" type="text">
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
	<select name="updateEndDate" type="text">
		<?php
		
			$listHtml = "";
			
			foreach($bookingDatesEnd as $e) {
				$listHtml .= "<option value='".$e->id."'>".$e->fulldate_end."</option>";
			}
			echo $listHtml;
			
		?>
	</select>
	<br><br>
	
	<input type="submit" name="update" value="Salvesta muudatused">
	<br><br>
	

</form>

<form method="POST">
	<input type="submit" name="delete" value="Kustuta broneering">
</form>




















<?php require("../footer.php"); ?>