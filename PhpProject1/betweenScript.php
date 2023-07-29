<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';


	
	
	if (isset($_GET['sort_Type'])) {
		global $conn;

		$sortType = $_GET['sort_Type'];
                $column = $_GET['column_Name'];
                $highNum = $_GET['highNum'];
                $lowNum = $_GET['lowNum'];
                
               
                
                populateDynamicTableHeader();
                
                populateDynamicTableBodyBetween($sortType, $column, $highNum, $lowNum);
                echo "<script>hideUserText();</script>";
	}




?>
