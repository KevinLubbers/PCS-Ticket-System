<?php
require 'phpStoredProcedures.php';
require 'phpConnection.php';


	
	
	if (isset($_GET['vehicle_id']) ) {
            
		global $conn;
		
		$divID = $_GET['division_id'];
                $vehID = $_GET['vehicle_id'];
                $year = $_GET['year_id'];
                
               
                    populateHiddenText($vehID, $year, $divID);
                
	}




?>