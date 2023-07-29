<?php



require_once 'phpConnection.php';
require_once 'phpStoredProcedures.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex, nofollow" />
    <title>PCS Support Desk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="landing.css">
    <link rel="shortcut icon" type="image/jpg" href="/favicon.png"/>
    <!--<script>
	    
				
				function getModelId() {
					var division_id = $("#vehicleDivision").val();
					var year_id = $("#vehicleYear").val();
                                        if(division_id === 'Select Dropdown' || year_id === 'Select Dropdown'){
                                            return;
                                        }
                                        else{
                                           $.ajax({
						type: "GET",
						url: "modelCodeScript.php",
						data: {"division_id":division_id,"year_id":year_id},
						contentType: "application/json; charset=utf-8",
						dataType: "html",
						success: function(response){
							$('#vehicleModel').html(response);
						}
						}); 
                                        }
					
						
					
					
					
					}
                                $(document).ready(function(){        
                                $(".submitButton").click(function(){
                                    $("#hiddenText").css({"visibility": "visible"});
                                    
                                    var division_id = $("#vehicleDivision").val();
                                    var year_id = $("#vehicleYear").val(); 
                                    var vehicle_id = $("#vehicleModel").val();
                                    
                                    $.ajax({
						type: "GET",
						url: "MCScodeScript.php",
						data: {"division_id":division_id,"year_id":year_id,"vehicle_id":vehicle_id},
						contentType: "application/json; charset=utf-8",
						dataType: "html",
						success: function(response){
							$('#hiddenText').html(response);
                                                        
						}
						}); 
                                });
                                    
                                });    
                                    
                                       
                                
                                        
                            
				
	</script>-->	 
    
</head>

<body>
    
    <div class="banner" id="banner">
        <div id = "imgWrap">
            <img src="PCSLogo.jpg" />
        </div>
        <div id="titleWrap">
            <div id="navTitle1">Pricing</div>
            <div id="navTitle2">Compatibility</div>
            <div id="navTitle3">Specifications</div>
        </div>
        <div class="navButton">
            <a href="/form.php">
            <div id="navText">PCS Ticket Form</div>
            </a>
        </div>
        
    </div>



<div id="backWrap">
    

<div class="faqContainer" id="faq">
    
    <div class="chartContainer">
      <u><h2>Model Code Lookup</h2></u>
    
                <label for="vehicleYear">Model Year: </label>
                <div>
                        
			<select onchange="getModelId()" class="formDesign" id="vehicleYear" name="vehicleYear" required >

				<option>Select Dropdown</option>
				<?php 
					populateYear();
				?>		
			</select>
                </div>
                
                <label for="vehicleDivision">Vehicle Division: </label>
                <div>
                        
			<select onchange="getModelId()" id="vehicleDivision" class="formDesign" name="vehicleDivision" required >

				<option>Select Dropdown</option>
				<?php 
					populateDivision();
				?>		
			</select>
                </div>
        
                <label for="vehicleModel">Vehicle Name: </label>
                <div>
                    <select id="vehicleModel" class="formDesign" name="vehicleModel" required >
                        <option>Must Select Division</option>
                    </select>
                </div>
        
                
      
                <button class="submitButton">Display Information</button>
                
                
                <div id = "hiddenText">
                
                    
                    
                </div>
    </div>
    
    <ul class="instructionContainer">
        <u><h1>Ticket Instructions</h1></u>
        <li>Click the button in the top right corner to submit a PCS Ticket</li>
        <li>Fill out the form - every field is <u><b>Mandatory</b></u></li>
        <li>Click the submit button</li>
        <li>You will receive an email if your ticket was successful</li>
        <li>An error page will appear if there was an issue</li>
        <li>Please do NOT submit multiple tickets for the same issue</li>
    </ul>

    
</div>
</div>



</body>

</html>