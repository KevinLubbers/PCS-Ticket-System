<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';

        
    
    
            
		
            if (isset($_GET['specialist'])) {  
                
                
                global $conn;
		$specialist = $_GET['specialist'];
                $ticket = $_GET['ticket'];
                
              
                
                 reassignRow($ticket, $specialist); 
            }
    
    
    
    

	
	
	

?>

