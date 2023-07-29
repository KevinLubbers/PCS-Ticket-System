<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';


	
	
	if (isset($_GET['specialist'])) {
		global $conn;
		
              
                
		$specialist = $_GET['specialist'];
                loadFilter($specialist);
                
                //echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	}


?>

