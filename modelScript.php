<?php
require 'phpStoredProcedures.php';
	
	if (isset($_GET['division_id'])) {
		
		
		$divID = $_GET['division_id'];		
		populateVehicle($divID);
	}




?>