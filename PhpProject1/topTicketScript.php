<?php
require './phpStoredProcedures.php';
require './phpConnection.php';




                getLastEnteredAjax();
                echo json_encode(populateDynamicTable($sortType, $column));
		//json_encode(populateVehicle($divID));
	


?>

