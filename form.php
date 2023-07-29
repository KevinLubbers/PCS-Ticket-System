<?php




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


require_once 'phpConnection.php';
require_once 'phpStoredProcedures.php';



if(isset($_POST['submit'])){
			
			//set variables
			$errorArray = array();
                        $userEmail = checkEmail($_POST['email']);          
                        if($userEmail == -1){
                            $errorArray[] = "Invalid Email Address, Please Use Your Company Email";
                            unset($userEmail);
                            unset($_POST['email']);
                        }
                        
                        
                            
                            
                        
                        
			$userTask = $_POST['task'];
                        
			
			$userTaskID = "";
			$specialist = "";
			$divisionID = "";
			$specialistEmail = "";
			
			//Check Input based off of Radio Selection - Must Add
                        
                        if (isset($_POST['numberType']) && ($_POST['numberType'] == "Customer Number" || $_POST['numberType'] == "F.O. Number" || $_POST['numberType'] == "New Customer")){
                                $userInputNumberType = $_POST['numberType'];
                        }
                        else {
                                $errorArray[] = "Radio Button Error - Does Not Match";
                                unset($_POST['numberType']);
                        }
			if (isset($_POST['number'])){
				$userInputNumber = htmlspecialchars($_POST['number'], ENT_QUOTES);
			}
			else {
				$errorArray[] = "Input Number or Name Error";
                                unset($_POST['number']);
			}
			
			
			$userText = htmlspecialchars($_POST['userText'], ENT_QUOTES);
                        if (filter_var($_POST['vehicleYear'], FILTER_VALIDATE_INT)){
                            $modelYear = $_POST['vehicleYear'];
                        }
                        else if($_POST['vehicleYear'] == "Other"){
                            $modelYear = $_POST['vehicleYear'];
                        }
                        else{
                           $errorArray[] = "Model Year was not selected - Please Select a Valid Dropdown Option";
                           unset($_POST['vehicleYear']);
                        }
                        
			if (strlen($_POST['vehicleString']) <= 75 ){
                            if (!is_null($_POST['vehicleString'])){
                                 $vehicleString = htmlspecialchars($_POST['vehicleString'], ENT_QUOTES);
                            }
                            
                        }
                        else{
                            $errorArray[] = "Bad Trim Level - Too Many Characters";
                            unset($_POST['vehicleString']);
                        }
			
                        
                        
                        
                        if (!is_numeric($_POST['vehicleDivision']) || is_null($_POST['vehicleDivision'])){
                            $errorArray[] = "Vehicle Division was not selected - Please Select a Valid Dropdown Option";
                        }
                        else{
                            $userDivision = $_POST['vehicleDivision'];
                        }
                        
                        
                        if (!is_numeric($_POST['vehicleModel']) || is_null($_POST['vehicleModel'])){
                            $errorArray[] = "Vehicle Model was not selected - Please Select a Valid Dropdown Option";
                        }
                        else {
                            $vehicleModel = $_POST['vehicleModel'];
                        }
			
			
			
			$taskArray = getTaskId($userTask);
			$userTaskID = $taskArray[0];
			$specialist = $taskArray[1];
			
			
			
			if ($userTaskID == NULL || !is_numeric($userTaskID)) {
				$errorArray[] = "Ticket Task was not selected - Please Select a Valid Dropdown Option";
			}
			
			if ($specialist == NULL) {
				$specialist = getDivId($userDivision);
				if ($specialist == NULL) {
					$specialist = getModelId($vehicleModel);
					if ($specialist == NULL || !is_numeric($specialist) ) {
						$errorArray[] = "Failed to assign Specialist - Contact PCS Group";
					}
				}
			}
			
			
                        if (isset($errorArray) && sizeof($errorArray) > 0){
                            populateError($errorArray);
      
                        }
                        
                        
			$queryArray = array($userTaskID, $specialist, $userEmail, $userInputNumber, $userInputNumberType,
			$userText, $modelYear, $userDivision, $vehicleModel, $vehicleString);
			
			foreach($queryArray as $key => $value){
				if (!$queryArray[$key] == NULL) {
					$queryArray[$key] = sanitize_data($value);
                                        
				}
				else {
					$errorArray[] = "User Error - Input Failed Sanitize";
                                        break;
				}
				
			}
			
			if (isset($errorArray) && sizeof($errorArray) > 0){
                            populateError($errorArray);
      
                        }
			
			
			
			$tickResult = "";
			$lastIDEntered = getLastEntered();
                        
			$tickResult = insertTicket($queryArray);
			if ($lastIDEntered == $tickResult) {				
                                $alertFlag = false;
			}
			else {
				sendTicketMessage($tickResult);
                                $alertFlag = true;
			}
			
                        
                        
		}



?>
<!DOCTYPE html>







<html>
<head>
    <meta name="robots" content="noindex, nofollow" />
    <meta 
    name='viewport' 
    content='user-scalable=1' 
/>
    <title>PCS Support Desk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="formPage.css?update=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="functions.js?update=34"></script>
    <link rel="shortcut icon" type="image/jpg" href="/favicon.png"/>
    <script>      
                                       
                                    //adds minor input restrictions based on user selection and business rules
                                    $(document).on('keydown', '#userInputNumber', function(event) {
                                        var length = this.value.length;
                                       
                                        
                                   if ($('#customerNumber').prop('checked')){ 
                                              
                                                while ($('#userInputNumber').val().length === 1 || $('#userInputNumber').val().length === 5 || $('#userInputNumber').val().length === 8){
                                                    if (event.keyCode === 8 || event.keyCode === 46){
                                                        break;
                                                    }
                                                    else{
                                                           
                                                            this.value = this.value + "-";
                                                            
                                                        }
                                                    
                                                    
                                                }
                                                
                                            }
        
                                    $(document).on('keyup', '#userInputNumber', function(event) {
                                       var v = this.value;
                                        
                                        if ($('#foNumber').prop('checked')){
                                            $('#userInputNumber').prop('minLength', 6);
                                            if ($('#userInputNumber').val().length > 6) {
                                                //chops off the last char
                                                this.value = this.value.slice(0,-1);
                                            }
                                            if($.isNumeric(v) === false) {
                                                this.value = this.value.slice(0,-1);
                                            }
                                        }   
                                        if ($('#customerNumber').prop('checked')){ 
                                                $('#userInputNumber').prop('minLength', 13);
                                                
                                                
                                                if($.isNumeric(v.charAt(v.length-1)) === false) {
                                                    this.value = this.value.slice(0,-1);

                                                }else if(v.charAt(v.length-1) === '-'){
                                                        if($.isNumeric(v.charAt(v.length-2)) === false){
                                                            this.value = this.value.slice(0,-1);
                                                        }
                                                    }
                                                if ($('#userInputNumber').val().length > 13){
                                                        this.value = this.value.slice(0,-1);
                                                        
                                                    }
                                                    
                                                
                                                
                                              
                                        }
                                        if ($('#vin').prop('checked')){
                                            $('#userInputNumber').prop('minLength', 3);
                                            
                                            if($.isNumeric(v.charAt(v.length-1)) === true) {
                                                this.value = this.value.slice(0,-1);
                                                
                                            }
                                        }
                                    });
                                    
                                                                  
                                        
                                });
                        $(document).ready(function() {
                            $('#loader').hide();
                        });
                        
                        $(window).bind("pageshow", function(event) {
                            $("#loader").hide();
                        });
                                
                                
                        //Adds the prefix 9-102 to text box when you select Customer Number radio btn           
                         function prependNine(){
                             if ($('#customerNumber').prop('checked')){
                               $('#userInputNumber').val("");  
                         
                             $('#userInputNumber').focus();
                         }
                         }
                         
                         //Clears textbox of input when other radio btns are selected
                         function prependOther(){
                             if (!$('#customerNumber').prop('checked')){
                             $('#userInputNumber').val("");
                             $('#userInputNumber').focus();
                                
                             }
                         }
                         
                         function checkForm(){
                             
                             if(confirm("Are you sure you want to submit this ticket?\n\n(NOTE: The more attachments you add, the longer you must wait)") === true){
                                $('#loader').show();
                                $("#formForm").submit.disabled = true; 
                                 return true;
                             }
                             else {
                                 
                                 $("#formForm").submit.disabled = false; 
                                 return false;
                             }
                             
                             
                         }
                         
                        function ninePaste(){
                            if ($('#customerNumber').prop('checked')){
                                
                            var inputNum = $("#userInputNumber").val();
                            var sortCount = 0;
                            var sortedNum = [];
                            var formatNum = "";
                                for (let i = 0 ;i <= inputNum.length; i++){
                                    if($.isNumeric(inputNum.charAt(i)) === false) {
                                    
                                    }
                                    else {
                                      sortedNum[sortCount] = inputNum.charAt(i);
                                      sortCount++;
                                    }
                                }
                                for (let i = 0 ;i <= 9; i++){
                                    
                                    if (sortedNum[i] == null){
                                            
                                    }
                                    else{
                                        if(i === 1 || i === 4 || i === 6){
                                            formatNum += "-";
                                            formatNum += sortedNum[i];
                                        }
                                        else{
                                            formatNum += sortedNum[i];
                                        }
                                         
                                    }   
                                }
                                
                            $('#userInputNumber').empty().val(formatNum);    
                            }
                            
                        }
                                
                        
                               
    </script>
    <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>
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
            <a href="/">
            <div id="navText">PCS Home</div>
            </a>
        </div>
        
    </div>






<div id="backWrap">
<div class="formContainer">


 
<form method="POST" id="formForm" enctype="multipart/form-data" onsubmit="return checkForm()">

    <div class="field">
		<label class="label" for="email">E-mail Address: </label>
		<div class="control">
			<input class="input toggleBorderRed" placeholder="Enter Email Address" onfocusout="checkEmail()" type="email" id="email" name="email" required autofocus />
		</div>
	</div>
    
        <div class="field">
		<label class="label" for="formDesign">Vehicle Year: </label>
		<div class="control">
                <div class="select">
			<select  class="input" id="vehicleYear" onchange="checkDropdown(this)" style="border: red solid 2px" class="formDesign" name="vehicleYear" required >

				<option>Select Dropdown</option>
				<?php 
					populateYear();
				?>		
			</select>
		 
		</div>
		</div>
	</div>     
    
    
	
<div class="field">
	<label class="label" for="formDesign">Vehicle Division: </label>
	<div class="control">
                <div class="select">
			<select onchange="checkDropdownDivision(this)" class="input" id="vehicleDivision" style="border: red solid 2px" class="formDesign" name="vehicleDivision" required >

				<option>Select Dropdown</option>
				<?php 
					populateDivision();
				?>		
			</select>
                </div>
        </div>
</div>    
    
	
	
	
		 
		 
	<script>
	    
				
				function getDivisionId() {
					var division_id = $("#vehicleDivision").val();
					
					
						$.ajax({
						type: "GET",
						url: "modelScript.php",
						data: {"division_id" : division_id},
						contentType: "application/json; charset=utf-8",
						dataType: "html",
						success: function(response){
							$('#vehicleModel').html(response);
                                                        
						}
						});
					
					
					
					}
                                
                                        
                            
				
	</script>	 
		 
	 
		 
	<div class="field">
		<label class="label" for="vehicleModel">Vehicle Model: </label>
		<div class="control">
		<div class="select">
			<select id="vehicleModel" class="input" class="formDesign" style="border: red solid 2px" name="vehicleModel" required >
	    	   <option>Must Select Division</option>
			</select>
	    </div>
		</div>
	</div>
	
	
	
	
	
	<div class="field">
		<label class="label"for="vehicleString">Trim Level / Special Packages / Drivetrain:</label>
		<div class="control">
                    <input class="input toggleBorderRed" type="text" placeholder="Willys Sport 4x4" minlength="1" maxlength="75" onfocusout="checkTrim()" id="vehicleString" name="vehicleString" required />
		</div>
	</div>
	
	
	
	

    <div class="field">
		<label class="label" for="task">Ticket Task: </label>
		<div class="control">
	    <div class="select">
			<select onchange="checkDropdown(this)" style="border: red solid 2px" class="input" id="tasks" class="formDesign" name="task" required >
				<option>Select Dropdown</option>
				<?php 
					populateTask();
				?>		
			</select>
	
		</div>
		</div>
	</div>
	
	
	
	<div class="field">
                <label class="label" for="numberType">Customer / Vehicle Information: </label>
		<div class="control">
                    <input  type="radio" id="customerNumber" name="numberType" value="Customer Number" onchange="prependNine()">
			<label class="radio" for="customerNumber">- Customer Number</label>
		</div>
	</div>
		
	<div class="field">
		<div class="control">
                    <input type="radio" id="foNumber" name="numberType" value="F.O. Number" onchange="prependOther()">
			<label class="radio" for="foNumber">- FO Number</label>
		</div>
	</div>
	
	<div class="field">
		<div class="control">
			<input type="radio" id="vin" name="numberType" value="New Customer" onchange="prependOther()" required>
			<label class="radio" for="vin">- New Customer Name</label>
		</div>
	</div>
		
		
		

	<div class="field">
		<input class="input toggleBorderRed" id="userInputNumber" onfocusout="checkInfo()" type="text" placeholder="Enter Number or Customer Name" name="number" required />
	</div>

	
	<div class="field">
		<label class="label" for="userText">Details: </label>

		<div class="control">
			<textarea  class="textarea toggleBorderRed" id="userText" name="userText" onchange="checkDetails()" placeholder="Tell us your troubles..." rows="10" cols="60" required ></textarea>
		</div>
	</div>
	
			

<div id="fileUpload" class="">
  <label class="file-label">
      <input multiple="multiple" class="file-input" type="file" id="file-upload" name="file-upload[]" accept="image/png, .jpeg, .jpg, image/gif, .pdf, .gif, .png"/>
    <span class="fileLabel">
      
      <span class="file-label">
        Choose a file…
      </span>
    </span>
    <span class="fileName">
      No file uploaded
    </span>
  </label>
</div>

<script>
  const fileInput = document.querySelector('#fileUpload input[type=file]');
  fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
      const fileName = document.querySelector('#fileUpload .fileName');
      var index = 0;
      fileName.textContent = "";
      while (index < fileInput.files.length){
          fileName.textContent += " [" + fileInput.files[index].name + "] ";
          index++;
      }
      
      
      
    }
  }
</script>

	

		<div class="field">
		    	 <div class="control">
	<button type="submit" name="submit" class="submitButton" >Submit</button>
	</div>
	 </div>
    
</form>
<div id="loader" style="display:none; position: fixed; top:0; left:0; width:100%; height: 100%; background: no-repeat url('loading.gif') center/80% #efefef"></div>
</div>
    
</div>

</body>

</html>


<?php 
            if (isset($alertFlag) && ($alertFlag == true)){
                echo '<script>alert("Your ticket has been successfully submitted\nYou should receive an email notification shortly");</script>';
                
            }
?>


