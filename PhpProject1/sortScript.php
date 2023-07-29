<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';


	
	
	if (isset($_GET['sort_Type'])) {
		global $conn;
		
                
                //echo var_dump($_GET['sort_Type']);
                //echo var_dump($_GET['column_Name']);
                
		$sortType = $_GET['sort_Type'];
                $column = $_GET['column_Name'];
                $limit = $_GET['ticketsPerPage'];
                
               
                
                populateDynamicTableHeader();
                if(($_GET['ticketsPerPage']) !== 'Select') {
                    populateDynamicTableBodyLimit($sortType, $column, $limit);  
                }
                else{
                    populateDynamicTableBody($sortType, $column);
                }
                
                echo "<script>hideUserText();</script>";
               
                //echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	}




?>