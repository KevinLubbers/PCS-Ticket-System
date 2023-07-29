<?php
require 'phpStoredProcedures.php';
require 'phpConnection.php';


	
	
	if (isset($_GET['division_id']) && isset($_GET['year_id'])) {
            
		global $conn;
		
		$divID = $_GET['division_id'];
                $year = $_GET['year_id'];
                
               
                    populateModelCode($year, $divID);
                
	}




?>