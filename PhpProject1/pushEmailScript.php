<?php
require '../phpStoredProcedures.php';
require '../phpConnection.php';

        
    
    
            
		
            if (isset($_GET['ticket'])) {  
                
                
                global $conn;
		
                $ticket = $_GET['ticket'];
                
              
                
                sendPushTicketMessage($ticket); 
            }
    
    
    
    

	
	
	

?>
