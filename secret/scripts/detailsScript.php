<?php
require 'phpConnection.php';
require 'phpStoredProcedures.php';


	
	
	if (isset($_GET['ticketNumber'])) {
		global $conn;
		
              
                
		$ticketNum = $_GET['ticketNumber'];

                populateDetails($ticketNum);
                //echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	}


?>

