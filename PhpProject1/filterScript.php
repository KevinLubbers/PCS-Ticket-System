<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';


	
	
	if (isset($_GET['specialist'])) {
		global $conn;
		
              
                
		$specialist = $_GET['specialist'];
                $filterArray = $_GET['filterArray'];
                
              

                saveFilter($filterArray, $specialist);
                echo "Filter Preset Saved\nSpecialist: $specialist";
                //echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	}


?>

