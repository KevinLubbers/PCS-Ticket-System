<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';


	
	
	if (isset($_GET['ticketNumber'])) {
		global $conn;
		
              
                
		$ticketNum = $_GET['ticketNumber'];

                populateDetails($ticketNum);
                //echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	}


?>

