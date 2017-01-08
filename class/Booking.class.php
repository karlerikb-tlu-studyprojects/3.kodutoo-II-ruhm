<?php
class Booking {
	
	private $connection;
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	// funktsioonid
	
	
	function saveCampSite ($campsite) {
				
		$stmt = $this->connection->prepare("INSERT INTO booking_campsites (campsite) VALUES (?)");
		echo $this->connection->error;
		
		$stmt->bind_param("s", $campsite);
		
		if($stmt->execute()) {
			echo "Platsi lisamine õnnestus!";
		} else {
			echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	
	function getAllCampSites($q, $sort, $direction) {
		
		$allowedSortOptions = ["id", "campsite"];
		
		if(!in_array($sort, $allowedSortOptions)) {
			$sort = "id";
		}
		
		$orderBy = "ASC";
		if($direction == "descending") {
			$orderBy = "DESC";
		}
		
		if($q == "") {
			
			$stmt = $this->connection->prepare("SELECT id, campsite FROM booking_campsites WHERE deleted IS NULL ORDER BY $sort $orderBy");
			echo $this->connection->error;
			
		} else {
			
			$searchWord = "%".$q."%";
			
			$stmt = $this->connection->prepare("SELECT id, campsite FROM booking_campsites WHERE deleted IS NULL AND campsite LIKE ? ORDER BY $sort $orderBy");
			$stmt->bind_param("s", $searchWord);
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $campsite);
		$stmt->execute();
		
		$result = array();
		while($stmt->fetch()) {
			$c = new StdClass();
			$c->id = $id;
			$c->campsite = $campsite;
			array_push($result, $c);
		}
		$stmt->close();	
		return $result;
	}
	
	function getSingleCampSite($currentid) {
		
		$stmt = $this->connection->prepare("SELECT campsite FROM booking_campsites WHERE id = ?");
		echo $this->connection->error;
		
		$stmt->bind_param("i", $currentid);
		$stmt->bind_result($campsite);
		$stmt->execute();
		
		$campSites = new StdClass();
		if($stmt->fetch()) {
			$campSites->campsite = $campsite;
		} else {
			header("Location: data.php");
			exit();
		}
		$stmt->close();
		return $campSites;
	}
	
	function updateCampsite($currentid, $campsite) {
		
		$stmt = $this->connection->prepare("UPDATE booking_campsites SET campsite = ? WHERE id = ? AND deleted IS NULL");
		$stmt->bind_param("si", $campsite, $currentid);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus!";
		}
		$stmt->close();
	}
	
	function deleteCampsite($currentid) {
		$stmt = $this->connection->prepare("UPDATE booking_campsites SET deleted = NOW() WHERE id = ? AND deleted IS NULL");
		$stmt->bind_param("i", $currentid);
		
		if($stmt->execute()) {
			echo "Kustutamine õnnestus!";
		}
		$stmt->close();
	}
	
	
	
	
	function saveDate ($dateday, $datemonth, $dateyear) {
		
		$stmt = $this->connection->prepare("INSERT INTO booking_dates (dateday, datemonth, dateyear, fulldate) VALUES (?, ?, ?, ?)");
		echo $this->connection->error;
		
		$fulldate = $dateyear."-".$datemonth."-".$dateday;
		$stmt->bind_param("iiis", $dateday, $datemonth, $dateyear, $fulldate);
		
		if($stmt->execute()) {
			echo "Kuupäeva salvestamine õnnestus!";
		} else {
			echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	
	function getAllStartDates() {
		
		$stmt = $this->connection->prepare("SELECT id, fulldate_start FROM booking_dates_start");
		$stmt->bind_result($id, $fulldate);
		$stmt->execute();
		
		$result = array();
		
		while($stmt->fetch()) {
			$uniquedate = new StdClass();
			$uniquedate->id = $id;
			$uniquedate->fulldate_start = $fulldate;
			array_push($result, $uniquedate);
		}
		$stmt->close();
		return $result;
	}
	
	
	function getAllEndDates() {
		
		$stmt = $this->connection->prepare("SELECT id, fulldate_end FROM booking_dates_end");
		$stmt->bind_result($id, $fulldate);
		$stmt->execute();
		
		$result = array();
		
		while($stmt->fetch()) {
			$uniquedate = new StdClass();
			$uniquedate->id = $id;
			$uniquedate->fulldate_end = $fulldate;
			array_push($result, $uniquedate);
		}
		$stmt->close();
		return $result;
	}
	
	
	function saveUserCampSite($campsite, $startdate, $enddate) {
		
		$stmt = $this->connection->prepare("INSERT INTO booking_reserv (userid, campsiteid, startdateid, enddateid) VALUES (?, ?, ?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("iiii", $_SESSION["userId"], $campsite, $startdate, $enddate);
		
		if($stmt->execute()) {
			echo "Broneerimine õnnestus!";
		} else {
			echo "ERROR ".$stmt->error;
		}
	}
	
	
	function getAllUserBookings($sort, $direction) {
		
		$allowedSortOptions = ["campsite", "fulldate_start", "fulldate_end"];
		
		if(!in_array($sort, $allowedSortOptions)) {
			$sort = "campsite";
		}
		
		$orderBy = "ASC";
		if($direction == "descending") {
			$orderBy = "DESC";
		}
		
		$stmt = $this->connection->prepare("
			SELECT r.id, campsite, fulldate_start, fulldate_end
			FROM booking_campsites c
			JOIN booking_reserv r ON c.id=r.campsiteid
			JOIN booking_dates_start s ON r.startdateid=s.id
			JOIN booking_dates_end e ON r.enddateid=e.id
			WHERE r.userid = ? AND r.deleted IS NULL ORDER BY $sort $orderBy
		");
		echo $this->connection->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);		
		$stmt->bind_result($id, $campsite, $startdate, $enddate);
		$stmt->execute();
		
		$result = array();
		while($stmt->fetch()) {
			$b = new StdClass();
			$b->id = $id;
			$b->campsite = $campsite;
			$b->fulldate_start = $startdate;
			$b->fulldate_end = $enddate;
			array_push($result, $b);
		}
		$stmt->close();
		return $result;
	}
	
	
	function getSingleUserBooking($currentid) {
		$stmt = $this->connection->prepare("
			SELECT r.id, campsite, fulldate_start, fulldate_end
			FROM booking_campsites c
			JOIN booking_reserv r ON c.id=r.campsiteid
			JOIN booking_dates_start s ON r.startdateid=s.id
			JOIN booking_dates_end e ON r.enddateid=e.id
			WHERE r.userid = ? AND r.id = ?
		");
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"], $currentid);
		$stmt->bind_result($id, $campsite, $startdate, $enddate);
		$stmt->execute();
		
		$userBooking = new StdClass();
		if($stmt->fetch()) {
			$userBooking->id = $id;
			$userBooking->campsite = $campsite;
			$userBooking->startdate = $startdate;
			$userBooking->enddate = $enddate;
		} else {
			header("Location: user.php");
			exit();
		}
		$stmt->close();
		return $userBooking;
	}
	
	
	function updateBooking($campsite, $startdate, $enddate, $currentid) {
		$stmt = $this->connection->prepare("UPDATE booking_reserv SET campsiteid = ?, startdateid = ?, enddateid = ? WHERE id = ?");
		echo $this->connection->error;
		$stmt->bind_param("iiii", $campsite, $startdate, $enddate, $currentid);
		
		if($stmt->execute()) {
			echo "Broneeringu muutmine õnnestus!";
		} else {
			echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	function deleteBooking($currentid) {
		$stmt = $this->connection->prepare("UPDATE booking_reserv SET deleted = NOW() WHERE id = ? AND deleted IS NULL");
		$stmt->bind_param("i", $currentid);
		
		if($stmt->execute()) {
			echo "Kustutamine õnnestus";
		}
		$stmt->close();
	}



























	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>