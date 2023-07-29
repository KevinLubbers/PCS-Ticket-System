<?php
require 'phpConnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


//GET FUNCTIONS
function getTaskId($task){
    try{
        
	global $conn;
	$idInfo = array();
	
	$stmt = $conn->prepare('SELECT taskID, specialistID 
						FROM taskTable 
						WHERE taskName = :taskName');
	

	$stmt->bindValue(':taskName', $task);
	$stmt->execute();

	$rows = $stmt->fetchAll();


		foreach($rows as $input){
			$idInfo[0] = $input['taskID'];
			$idInfo[1] = $input['specialistID'];
			
		 
		    //echo $input['specialistID'];
		    //echo "<div>taskTest" . $input['taskID'] . "</div>";
			
		}
		
		
		return $idInfo;
		
		
    }  catch (Throwable $e){
         echo "Error Caught: " . $e->getMessage();
    }
	
}

function getDivId($divID){
	try{

	global $conn;
	
	
	$specialistID = "";
	
	$stmt = $conn->prepare('SELECT specialistID
							FROM divisionTable
							WHERE divisionID = :divisionID');
	
	$stmt->bindValue(':divisionID', $divID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		
		$specialistID = $input['specialistID'];
	}
	
	//echo "<div>Test:$specialistID:</div>";
	
	
	return $specialistID;
	} catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}	
	
}

function getModelId($modelID){
    try{

	global $conn;
	
	
	$specialistID = "";
	
	$stmt = $conn->prepare('SELECT specialistID
							FROM manDivModelAssocTable
							WHERE modelID = :modelID');
	
	$stmt->bindValue(':modelID', $modelID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		
		$specialistID = $input['specialistID'];
	}
	return $specialistID;
        
    } catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}	
	
}

function getTaskName($taskID){
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->prepare("SELECT taskName FROM taskTable WHERE taskID = :taskID");
	$stmt->bindValue(':taskID', $taskID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['taskName'];
	}
	
	return $returnVal;
}

function getSpecialistEmail($specialistID) {
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->prepare("SELECT specialistEmail FROM specialistTable WHERE specialistID = :specialistID");
	$stmt->bindValue(':specialistID', $specialistID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['specialistEmail'];
		
	}
	return $returnVal;	
}

function getSpecialistName($specialistID) {
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->prepare("SELECT specialistName FROM specialistTable WHERE specialistID = :specialistID");
	$stmt->bindValue(':specialistID', $specialistID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['specialistName'];
		
	}
	return $returnVal;	
}

function getDivisionName($divisionID) {
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->prepare("SELECT divisionName FROM divisionTable WHERE divisionID = :divisionID");
	$stmt->bindValue(':divisionID', $divisionID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['divisionName'];
		
	}
	//echo "<div>DivNameTest" . $returnVal . "</div>";
	
	return $returnVal;	
}

function getModelName($modelID) {
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->prepare("SELECT modelName FROM manDivModelAssocTable WHERE modelID = :modelID");
	$stmt->bindValue(':modelID', $modelID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['modelName'];
		
	}
	//echo "<div>ModelNameTest" . $returnVal . "</div>";
	
	return $returnVal;	
}


function getLastEntered(){
	try {
		
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->query("SELECT COUNT(ticketID) AS ticket FROM ticketTable");
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['ticket'];
	}
	
	
	return $returnVal;
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}		
}


function getLastEnteredAjax(){
	try {
		
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->query("SELECT COUNT(ticketID) AS ticket FROM ticketTable");
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal = $input['ticket'];
	}
	
	
	echo "$returnVal";
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}		
}




//POPULATE FUNCTIONS
function populateTask() {
    global $conn;
    
    $stmt = $conn->query('SELECT taskName FROM taskTable ORDER BY taskName');
    $rows = $stmt->fetchAll();
	foreach($rows as $input){
	    echo "<option>" .$input['taskName']. "</option>";
	}
    
}

function populateYear(){
	global $conn;
	
	$stmt = $conn->query("SELECT year FROM modelYear WHERE isActive = '1'")->fetchAll();
	
	
	foreach($stmt as $input){
		echo "<option value='" .$input['year']. "'>" .$input['year']. "</option>";
	}
	
}

function populateDivision(){
	global $conn;
	
	$stmt = $conn->query("SELECT divisionName, divisionID FROM divisionTable ORDER BY divisionName")->fetchAll();
	
	
	foreach($stmt as $input){
		echo "<option value='" .$input['divisionID']. "'>" .$input['divisionName']. "</option>";
	}
	
}

function populateVehicle($divisionID) {
	try {
		
	global $conn;
	//echo "<div>$divisionID</div>";
	$query = $conn->prepare("SELECT modelName, modelID FROM manDivModelAssocTable WHERE divisionID = :divisionID ORDER BY modelName = 'Model Not Listed' ASC, modelName ASC");
	$query->bindValue(':divisionID', $divisionID);
	$query->execute();
	
	$rows = $query->fetchAll();
	
	foreach($rows as $name){
		echo "<option value='" .$name['modelID']. "'>" .$name['modelName']. "</option>";
	}
	
	}
	catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}
}

function populateModelCode($year, $divisionID) {
	try {
		
	global $conn;
	//echo "<div>$divisionID</div>";
	$query = $conn->prepare("SELECT modelName, modelCodeID FROM modelCodeTable WHERE (`divisionID` = :divisionID) AND (`year` = :year)");
	$query->bindValue(':divisionID', $divisionID);
        $query->bindValue(':year', $year);
	$query->execute();
	
	$rows = $query->fetchAll();
	
	foreach($rows as $name){
		echo "<option value='" .$name['modelCodeID']. "'>" .$name['modelName']. "</option>";
	}
	
	}
	catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}
}

function populateHiddenText($modelCodeID, $year, $divisionID) {
	try {
		
	global $conn;
	//echo "<div>$divisionID</div>";
	$query = $conn->prepare("SELECT manufacturerModelCode, mcsCode, meg FROM modelCodeTable WHERE (`divisionID` = :divisionID) AND (`year` = :year) AND (`modelCodeID` = :modelCodeID)");
	$query->bindValue(':divisionID', $divisionID);
        $query->bindValue(':year', $year);
        $query->bindValue(':modelCodeID', $modelCodeID);
	$query->execute();
	
	$rows = $query->fetchAll();
	
	foreach($rows as $name){
		
                
                echo '<b><label for="hiddenTextModelCode">Manufacturer Model Code: </label></b>';
                echo '<div id="hiddenTextModelCode" class="hiddenTextClass">'. $name["manufacturerModelCode"] .'</div>';
                
                echo '<b><label for="hiddenTextMCSCode">MCS Code: </label></b>';
                echo '<div id="hiddenTextMCSCode" class="hiddenTextClass">'. $name["mcsCode"] .'</div>';
                echo '<b><label for="hiddenTextMeg">MEG: </label></b>';
                echo '<div id="hiddenTextMeg" class="hiddenTextClass">'. $name["meg"] .'</div>';
	}
	
	}
	catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}
}

function populateError($errorLog) {
	try {
	$newURL = "https://www.pcsgroup.info/displayError.php?".http_build_query($errorLog);	
	header("Location: $newURL");
	
	}
	catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}
}	

function checkEmail($data){
	
	if(preg_match('|^[a-zA-Z0-9._%+-]+@militarycars\.com$|', $data) || preg_match('|^[a-zA-Z0-9._%+-]+@mymilitarycars\.com$|', $data) || preg_match('|^[a-zA-Z0-9._%+-]+@intlauto\.com$|', $data)){
           
            return filter_var(filter_var($data, FILTER_VALIDATE_EMAIL), FILTER_SANITIZE_EMAIL);
        }
        else{
            return -1;
                            
        }
}


function sanitize_data($data){
	
	$data = trim($data);
	//$data = stripslashes($data);
	$data = strip_tags($data);
	//$data = htmlspecialchars($data, ENT_QUOTES);
	
	return $data;
}

function addTime($ticketID){
    
    global $conn;
    
    $stmt = $conn->prepare("UPDATE ticketTable SET dateIn = ADDTIME(CURRENT_TIMESTAMP, '3:0:0') WHERE ticketID = :ticketID");
                $stmt->bindValue(':ticketID', $ticketID);
                $stmt->execute();
}


//INSERT ticket into DB
function insertTicket($queryArray){
	try {
		
	global $conn;
	
	
	$lastID = "";
	$stmt = $conn->prepare('INSERT INTO `ticketTable` (`taskID`, `specialistID`, `userEmail`, 
			`userInputNumber`, `userInputNumberType`, `userText`, `year`, `divisionID`, `modelID`, `vehicleString`) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	
	
	
	foreach($queryArray as $key => $value){
		
		$stmt->bindValue(($key +1), $value);
		
	}
	
	$stmt->execute();
	$lastID = getLastEntered();
        addTime($lastID);
        //checkForm();
	//echo "<div>InsertTest" . $lastID . "</div>";
	
	
	
	return $lastID;
	}
	
 catch (Throwable $e) {
	echo "Error Caught: " . $e->getMessage();
}
	
}

function storeAttachment($tempFile, $tempName, $ticketID){
    global $conn;

	$stmt = $conn->prepare('INSERT INTO `attachmentTable` (`attachment`, `tempName`, `ticketID`) 
			VALUES (?, ?, ?)');
	
	$stmt->bindValue((1), $tempFile);
        $stmt->bindValue((2), $tempName);
        $stmt->bindValue((3), $ticketID);
	$stmt->execute();
}

function countAttachment($ticketID){
    global $conn;
    
    
    $stmt = $conn->prepare('SELECT COUNT(attachment) AS attachCount FROM attachmentTable WHERE ticketID = :ticketID');
    $stmt->bindValue(':ticketID', $ticketID);
    $stmt->execute();
    $rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		return $input['attachCount'];
		
	}
        
}

function checkForAttachments($ticketID){
    global $conn;
    
    $count = 0;
    $dataArray;
    $nameArray;
	$stmt = $conn->prepare('SELECT attachment, tempName FROM attachmentTable WHERE ticketID = :ticketID');
	
	$stmt->bindValue(':ticketID', $ticketID);
	$stmt->execute();
        $rows = $stmt->fetchAll();
            foreach($rows as $data){
                $count = $count + 1;
                
                $dataArray[0] = $count;
                $dataArray[$count] = $data['attachment'];
                $nameArray[0] = $count;
                $nameArray[$count] = $data['tempName'];
            }
        if ($count > 0){
                    $returnArray[] = $dataArray;
                    $returnArray[] = $nameArray;
                    return $returnArray;
                }
                else {
                    return false;
                }
            
}

//SEND email to User
function sendTicketMessage($ticketID) {
    require_once 'PHPMailer/src/Exception.php';
	require_once 'PHPMailer/src/PHPMailer.php';
	require_once 'PHPMailer/src/SMTP.php';
	

	
	try {
		
	global $conn;
	
	//echo "<div>TicketMSGTest" . $ticketID . "</div>";
	$userTask = "";
	$specialist = "";
	$userEmail = "";
	$userInputNumber = "";
	$userInputNumberType = "";
	$userText = '';
        $divisionID = "";
        $modelID = "";
        $vehicleString = "";
	$year = "";
	$userTaskName = "";
	$specialistEmail = "";
        $divisionName = "";
        $modelName = "";
        $arr = array("//" => "", "/" => "");
	
	
	$stmt = $conn->prepare("SELECT taskID, specialistID, userEmail, userInputNumber, userInputNumberType, userText, year, divisionID, modelID, vehicleString FROM ticketTable WHERE ticketID = :ticketID");
	$stmt->bindValue(':ticketID', $ticketID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $value){
		$userTask = $value['taskID'];
		$specialist = $value['specialistID'];
		$userEmail = $value['userEmail'];
		$userInputNumber = $value['userInputNumber'];
		$userInputNumberType = $value['userInputNumberType'];
		$userText = strtr(nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES)), $arr);
                //$userText = nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES));
                $year = $value['year'];
                $divisionID = $value['divisionID'];
                $modelID = $value['modelID'];
                //$vehicleString = nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES));
		$vehicleString = strtr(nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES)), $arr);
	}
		
		
	
	
	$userTaskName = getTaskName($userTask);
	$specialistEmail = getSpecialistEmail($specialist);
        $divisionName = getDivisionName($divisionID);
        $modelName = getModelName($modelID);
	
	$email = new PHPMailer();
        $email->isSMTP();
        $email->SMTPDebug = 0;
        $email->Host = 'mail.pcsgroup.info';
        $email->Port = 587;
        $email->SMTPAuth = 'LOGIN';
        $email->SMTPSecure = false;
        $email->Username = 'support@pcsgroup.info';
        $email->Password = '1lx?kYe&9UWx';
        $email->SMTPAutoTLS = false; 
        $email->CharSet = 'UTF-8'; 
         
	
	
	$email->IsHTML(TRUE);
	
	
	//$email->setFrom("support@pcsgroup.info");
	$email->setFrom("support@pcsgroup.info", "PCS Ticket System");
        $email->addReplyTo($specialistEmail);
	$email->Subject = "$userInputNumberType : $userInputNumber - Model: $modelName - Ticket: PCS00$ticketID - Task: $userTaskName";
	$email->Body = "<body>
<div style='font-family: Arial;'>
<h1 style='text-align: center; font-weight: bolder; border-bottom: 5px double #c2c8c5;'>PCS Ticket System</h1>	
<div>This email has been automatically generated by your submission of a ticket</div>
<br>
<br>
<div><b>Ticket Number:</b> PCS00$ticketID</div>
<div><b>Ticket Submission By:</b> $userEmail</div>
<div><b>Task Category:</b> $userTaskName</div>
<div><b>Year:</b> $year</div>
<div><b>Division:</b> $divisionName</div>
<div><b>Model:</b> $modelName</div>
<div><b>Trim / Package:</b> $vehicleString</div>
<div><b>$userInputNumberType :</b> $userInputNumber</div>
<div><b>Assigned PCS Specialist:</b> $specialistEmail</div>
<div><b>Submitted Info:</b> $userText</div>
<br>
<br>
<div>Please be patient as we look into your inquiry</div>
<div>DO NOT submit multiple tickets for the same issue</div>
</div>
</body>";
	$email->AltBody = "This email has been automatically generated by your submission of a ticket

Ticket Number: PCS00$ticketID
Ticket Submission By: $userEmail
Task Category: $userTaskName
$userInputNumberType: $userInputNumber
Assigned PCS Specialist: $specialistEmail

Submitted Info: $userText



Please be patient as we look into your inquiry
DO NOT submit multiple tickets for the same issue";
	$email->AddAddress($userEmail);
	$email->AddCC($specialistEmail);
        if ($userTask == 7){
            $email->addCC("deggling@militarycars.com");
        }
	
if (isset($_FILES['file-upload']) && ($_FILES['file-upload']['name'][0] !== "" ) ) 	{
	//echo var_dump($_FILES['file-upload']);
	foreach($_FILES['file-upload']['tmp_name'] as $key => $value) {
		$file_tmp = $_FILES['file-upload']['tmp_name'][$key];
		$file_name = $_FILES['file-upload']['name'][$key];
		storeAttachment(file_get_contents($file_tmp), $file_name, $ticketID);
		$email->AddAttachment($file_tmp, $file_name);
               
	}
	

	
}

	$email->Send();
	
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
                echo "\nFile Upload Error";
	}		
}

function sendPushTicketMessage($ticketID) {
    require_once 'PHPMailer/src/Exception.php';
	require_once 'PHPMailer/src/PHPMailer.php';
	require_once 'PHPMailer/src/SMTP.php';
	

	
	try {
		
	global $conn;
	
	//echo "<div>TicketMSGTest" . $ticketID . "</div>";
	$userTask = "";
	$specialist = "";
	$userEmail = "";
	$userInputNumber = "";
	$userInputNumberType = "";
	$userText = '';
        $divisionID = "";
        $modelID = "";
        $vehicleString = "";
	$year = "";
	$userTaskName = "";
	$specialistEmail = "";
        $divisionName = "";
        $modelName = "";
        $arr = array("//" => "", "/" => "");
	
	
	$stmt = $conn->prepare("SELECT `taskID`, `specialistID`, `userEmail`, `userInputNumber`, `userInputNumberType`, `userText`, `year`, `divisionID`, `modelID`, `vehicleString` FROM `ticketTable` WHERE `ticketID` = :ticketID");
	$stmt->bindValue(':ticketID', $ticketID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $value){
		$userTask = $value['taskID'];
		$specialist = $value['specialistID'];
		$userEmail = $value['userEmail'];
		$userInputNumber = $value['userInputNumber'];
		$userInputNumberType = $value['userInputNumberType'];
		$userText = strtr(nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES)), $arr);
                //$userText = nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES));
                $year = $value['year'];
                $divisionID = $value['divisionID'];
                $modelID = $value['modelID'];
                $vehicleString = strtr(nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES)), $arr);
                //$vehicleString = nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES));
		
	}
		
		
	
	
	$userTaskName = getTaskName($userTask);
	$specialistEmail = getSpecialistEmail($specialist);
        $divisionName = getDivisionName($divisionID);
        $modelName = getModelName($modelID);
	
	$email = new PHPMailer();
        $email->isSMTP();
        $email->SMTPDebug = 2;
        $email->Host = 'mail.pcsgroup.info';
        $email->Port = 587;
        $email->SMTPAuth = 'LOGIN';
        $email->SMTPSecure = false;
        $email->Username = 'support@pcsgroup.info';
        $email->Password = '1lx?kYe&9UWx';
        $email->SMTPAutoTLS = false; 
        $email->CharSet = 'UTF-8'; 
	
	
	$email->IsHTML(TRUE);
        //$email->SMTPAuth = false;
	//$email->Username = "support@pcsgroup.info";
	//$email->Password = "zxasqw12ZXASQW!@";
	//$email->setFrom("support@pcsgroup.info");
	$email->setFrom("support@pcsgroup.info", "PCS Ticket System");
        $email->addReplyTo($specialistEmail);
	$email->Subject = "$userInputNumberType : $userInputNumber - Model: $modelName - Ticket: PCS00$ticketID - Task: $userTaskName";
	$email->Body = "<body>
<div style='font-family: Arial;'>
<h1 style='text-align: center; font-weight: bolder; border-bottom: 5px double #c2c8c5;'>PCS Ticket System</h1>	
<div>This email has been automatically generated by your submission of a ticket</div>
<br>
<br>
<div><b>Ticket Number:</b> PCS00$ticketID</div>
<div><b>Ticket Submission By:</b> $userEmail</div>
<div><b>Task Category:</b> $userTaskName</div>
<div><b>Year:</b> $year</div>
<div><b>Division:</b> $divisionName</div>
<div><b>Model:</b> $modelName</div>
<div><b>Trim / Package:</b> $vehicleString</div>
<div><b>$userInputNumberType :</b> $userInputNumber</div>
<div><b>Assigned PCS Specialist:</b> $specialistEmail</div>
<div><b>Submitted Info:</b> $userText</div>
<br>
<br>
<div>Please be patient as we look into your inquiry</div>
<div>DO NOT submit multiple tickets for the same issue</div>
</div>
</body>";
        
	$email->AltBody = "This email has been automatically generated by your submission of a ticket

Ticket Number: PCS00$ticketID
Ticket Submission By: $userEmail
Task Category: $userTaskName
$userInputNumberType: $userInputNumber
Assigned PCS Specialist: $specialistEmail

Submitted Info: $userText



Please be patient as we look into your inquiry
DO NOT submit multiple tickets for the same issue";
	$email->AddAddress($userEmail);
	$email->AddCC($specialistEmail);
        if ($userTask == 7){
            $email->addCC("deggling@militarycars.com");
        }
	
$checkCount = checkForAttachments($ticketID);
        if (!empty($checkCount[0][0])){
            for ($i = 1; $i <= $checkCount[0][0]; $i++){
                //var_dump($checkCount);
                $email->AddStringAttachment(($checkCount[0][$i]), ($checkCount[1][$i]));
            }
        }

	$email->Send();
	
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
                echo "\nFile Upload Error";
	}		
}

      
function sendReassignedTicketMessage($ticketID) {
    require_once 'PHPMailer/src/Exception.php';
	require_once 'PHPMailer/src/PHPMailer.php';
	require_once 'PHPMailer/src/SMTP.php';
	

	
	try {
		
	global $conn;
	
	//echo "<div>TicketMSGTest" . $ticketID . "</div>";
	$userTask = "";
	$specialist = "";
	$userEmail = "";
	$userInputNumber = "";
	$userInputNumberType = "";
	$userText = "";
        $divisionID = "";
        $modelID = "";
        $vehicleString = "";
	$year = "";
	$userTaskName = "";
	$specialistEmail = "";
        $divisionName = "";
        $modelName = "";
        $arr = array("//" => "", "/" => "");
	
	
	$stmt = $conn->prepare("SELECT taskID, specialistID, userEmail, userInputNumber, userInputNumberType, userText, year, divisionID, modelID, vehicleString FROM ticketTable WHERE ticketID = :ticketID");
	$stmt->bindValue(':ticketID', $ticketID);
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	foreach($rows as $value){
		$userTask = $value['taskID'];
		$specialist = $value['specialistID'];
		$userEmail = $value['userEmail'];
		$userInputNumber = $value['userInputNumber'];
		$userInputNumberType = $value['userInputNumberType'];
                $userText = strtr(nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES)),$arr);
                //$userText = nl2br(htmlspecialchars_decode($value['userText'], ENT_QUOTES));
                $year = $value['year'];
                $divisionID = $value['divisionID'];
                $modelID = $value['modelID'];
                $vehicleString = strtr(nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES)),$arr);
                //$vehicleString = nl2br(htmlspecialchars_decode($value['vehicleString'], ENT_QUOTES));
		
	}
		
		
	
	
	$userTaskName = getTaskName($userTask);
	$specialistEmail = getSpecialistEmail($specialist);
        $divisionName = getDivisionName($divisionID);
        $modelName = getModelName($modelID);
	
	$email = new PHPMailer();
        $email->isSMTP();
        $email->SMTPDebug = 0;
        $email->Host = 'mail.pcsgroup.info';
        $email->Port = 587;
        $email->SMTPAuth = 'LOGIN';
        $email->SMTPSecure = false;
        $email->Username = 'support@pcsgroup.info';
        $email->Password = '1lx?kYe&9UWx';
        $email->SMTPAutoTLS = false; 
        $email->CharSet = 'UTF-8'; 
         
	
	
	$email->IsHTML(TRUE);
	
	$email->setFrom("support@pcsgroup.info", "PCS Ticket System");
        $email->addReplyTo($specialistEmail);
	$email->Subject = "$userInputNumberType : $userInputNumber - Model: $modelName - Ticket: PCS00$ticketID - Task: $userTaskName";
	$email->Body = "<body>
<div style='font-family: Arial;'>
<h1 style='text-align: center; font-weight: bolder; border-bottom: 5px double #c2c8c5;'>PCS Ticket System</h1>	
<div>This email has been automatically generated by the REASSIGNMENT of your ticket</div>
<br>
<br>
<div><b>Ticket Number:</b> PCS00$ticketID</div>
<div><b>Ticket Submission By:</b> $userEmail</div>
<div><b>Task Category:</b> $userTaskName</div>
<div><b>Year:</b> $year</div>
<div><b>Division:</b> $divisionName</div>
<div><b>Model:</b> $modelName</div>
<div><b>Trim / Package:</b> $vehicleString</div>
<div><b>$userInputNumberType :</b> $userInputNumber</div>
<div><b>Assigned PCS Specialist:</b> $specialistEmail</div>
<div><b>Submitted Info:</b> $userText</div>
<br>
<br>
<div>Please be patient as we look into your inquiry</div>
<div>DO NOT submit multiple tickets for the same issue</div>
</div>
</body>";
	$email->AltBody = "This email has been automatically generated by your submission of a ticket

Ticket Number: PCS000$ticketID
Ticket Submission By: $userEmail
Task Category: $userTaskName
$userInputNumberType: $userInputNumber
Assigned PCS Specialist: $specialistEmail

Submitted Info: $userText



Please be patient as we look into your inquiry
DO NOT submit multiple tickets for the same issue";
	$email->AddAddress($userEmail);
	$email->AddCC($specialistEmail);
        if ($userTask == 7){
            $email->addCC("deggling@militarycars.com");
        }
	
        $checkCount = checkForAttachments($ticketID);
        if (!empty($checkCount[0][0])){
            for ($i = 1; $i <= $checkCount[0][0]; $i++){
                //var_dump($checkCount);
                $email->AddStringAttachment(($checkCount[0][$i]), ($checkCount[1][$i]));
            }
        }
        //$email->AddAttachment($file_tmp, $file_name);

	$email->Send();
	
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}		
}





function updateRow($userSelection) {
	
	global $conn;
        
        foreach ($userSelection as $userSelection){
            $stmt = $conn->prepare("SELECT isResolved FROM ticketTable WHERE ticketID = :ticketID");
            $stmt->bindValue(':ticketID', $userSelection);
            $stmt->execute();
        
            $rows = $stmt->fetchAll();
            foreach($rows as $input){
                $resolved = $input['isResolved'];
            }
        
            if ($resolved == '1'){
                $stmt = $conn->prepare("UPDATE ticketTable SET isResolved = '0', dateResolved = NULL WHERE ticketID = :ticketID");
                $stmt->bindValue(':ticketID', $userSelection);
                $stmt->execute();
                 
            }
            else{
                $stmt = $conn->prepare("UPDATE ticketTable SET isResolved = '1', dateResolved = ADDTIME(CURRENT_TIMESTAMP, '3:0:0') WHERE ticketID = :ticketID");
                $stmt->bindValue(':ticketID', $userSelection);
                $stmt->execute();
                //run deleteAttachment function here
            }
        
            unset($resolved);
	
	
        }
	
}

function reassignRow($ticketSelection, $specialist){
    
    global $conn;
    
    
   $stmt = $conn->prepare("UPDATE ticketTable SET specialistID = (SELECT specialistID from specialistTable WHERE specialistName = :specialistID) WHERE ticketID = :ticketID");
                $stmt->bindValue(':ticketID', $ticketSelection);
                $stmt->bindValue(':specialistID', $specialist);
                $stmt->execute();
                
                
                sendReassignedTicketMessage($ticketSelection);
                
               echo $specialist;
    }

    

/* function populateDynamicTable($sort, $column) {
	
	global $conn;
        
        //limitDash(getLastEntered());
	
        $stmt = $conn->prepare("SELECT * FROM ticketTable ORDER BY `ticketTable`.`". $column ."` ".$sort.";");
       
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
        
	foreach($rows as $input){
            echo "<tr>\n";
                echo "<td><input type='checkbox' class='nonSelect' name='select[".$input['ticketID']."]' value= '" . $input['ticketID'] . "'/></td>\n";
                echo "<td>" . $input['ticketID'] . "</td>\n";
                echo "<td>" . getTaskName($input['taskID']) . "</td>\n";
                echo "<td>" . getSpecialistEmail($input['specialistID']) . "</td>\n";
                echo "<td>" . $input['userEmail'] . "</td>\n";
                echo "<td>" . $input['userInputNumber'] . "</td>\n";
                echo "<td>" . getDivisionName($input['divisionID']) . "</td>\n";
                echo "<td>" . getModelName($input['modelID']) . "</td>\n";
                echo "<td>" . $input['dateIn'] . "</td>\n";
                echo "<td class = 'bit". $input['isResolved'] ."'></td>\n";
                echo "<td>" . $input['dateResolved'] . "</td>\n";
            echo "</tr>\n";	
	}
        
	//getLastEntered() to find last Ticket Entry
	//limiter goes in this function (50-100?)
	//Subtract limiter from the return of getLastEntered() = Edge Ticket | DO NOT GO FURTHER POINT 
	
	//for (i = lastTicketID; i >=(?maybe just >?) Edge Ticket; i--) 
	//call populateRow($i) 
	
} */



function populateDetails($ticket) {
	try {
	$attachCount = countAttachment($ticket);	
	global $conn;
	//echo "<div>$divisionID</div>";
	$query = $conn->prepare("SELECT userText FROM ticketTable WHERE ticketID = :ticket");
	$query->bindValue(':ticket', $ticket);
	$query->execute();
	
	$rows = $query->fetchAll();
	
	foreach($rows as $name){
		echo htmlspecialchars_decode($name['userText'], ENT_QUOTES);
                echo "\n\nNumber of Attachments: $attachCount";
	}
	
	}
	catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}
}


function populateSpecialist(){
    
try {
		
	global $conn;
	//echo "<div>$divisionID</div>";
	$query = $conn->prepare("SELECT specialistName FROM specialistTable WHERE isActive = 1");
	$query->execute();
	
	$rows = $query->fetchAll();
	
        echo "<div id='selectSpec'>";
        echo "<select name='specialist' id='specialist' onchange='singleSpecialistFilter()'>";
        echo "<option value='noChoice'>One-Time Filter - No Save</option>";
	foreach($rows as $name){
		echo "<option value='". $name['specialistName'] . "'>". $name['specialistName'] . "</option>";
	}
        echo "</select>";
        echo "<div>";
        echo "<button type='button' name='saveButton' onclick='saveFilter()'>Save Filtering</button>";
        echo "<button type='button' name='loadButton' onclick='loadFilter()'>Load</button>";
        echo "<button type='button' id='hideButton' name='hideButton' onclick='hideFilter()'>Show</button>";
        echo "</div>";
	echo "</div>";
}
catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
}
    
}

function populateFilter(){
    
try {
		
	global $conn;
	
	$query = $conn->prepare("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='i9161278_lara2' AND `TABLE_NAME`='ticketTable';");
	$query->execute();
	
	$rows = $query->fetchAll();
	
        echo "<div id='selectFilter'>";
        echo "<table>";
        echo "<tbody>";
	foreach($rows as $name){
            
                echo "<tr class='hidecol'>";
                echo "<td><label for='formDesign'>". $name['COLUMN_NAME'] ."</label></td>";
                echo "<td><input type='checkbox' class='filterCheck' name='". $name['COLUMN_NAME'] ."' value='". $name['COLUMN_NAME'] ."' /></td>";
		echo "</tr>";
	}
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        
}
catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
}
    
}


function saveFilter($filterArray, $specialist){
    
try {
	
	global $conn;
	
        //for($i = 1; $i < $count; $i++){
           // $valueString += ", ?";
       // }
	$query = $conn->prepare("UPDATE specialistTable SET `specialistTable`.presetFilter = (?)
			 WHERE `specialistTable`.specialistName = '$specialist'");
        
        
        
        
        $query->bindValue((1), $filterArray);        	
	$query->execute(); 
        
        
}
catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
}
    
}

function loadFilter($specialist){
    
try {
    global $conn;
    
	
        //for($i = 1; $i < $count; $i++){
           // $valueString += ", ?";
       // }
	$query = $conn->prepare("SELECT presetFilter FROM specialistTable
			 WHERE `specialistName` = :specialist");
        $query->bindValue(':specialist', $specialist);
        $query->execute();
	
	$rows = $query->fetchAll();
	
        
	foreach($rows as $input){
            echo $input['presetFilter'];
            
        }
  	
	
        
}
catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
}
    
}


function populateDynamicTableHeader(){
    
try {
		
	global $conn;
	
	$query = $conn->prepare("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='i9161278_lara2' AND `TABLE_NAME`='ticketTable';");
	$query->execute();
	
	$rows = $query->fetchAll();
	
        echo "<tr>";
        echo "<th style='border: 2px solid white' name='sort' value='selection' >Select</th>";
	foreach($rows as $name){
            echo "<th style='border: 2px solid white' onclick=getSort('". $name['COLUMN_NAME'] ."') name='sort' class='". $name['COLUMN_NAME'] ."' >". $name['COLUMN_NAME'] ."</th>";
	}
        echo "</tr>";

        
}
catch (Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
}
    
}

function populateDynamicTableBody($sort, $column) {
	
	global $conn;
        
        //limitDash(getLastEntered());
	
        $stmt = $conn->prepare("SELECT * FROM ticketTable ORDER BY `ticketTable`.`". $column ."` ".$sort.";");
       
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
        
	foreach($rows as $input){
            
            echo "<tr>\n";
                echo "<td><input type='checkbox' class='nonSelect' name='select[".$input['ticketID']."]' value= '" . $input['ticketID'] . "'/></td>\n";
                echo "<td>" . $input['ticketID'] . "</td>\n";
                echo "<td>" . getTaskName($input['taskID']) . "</td>\n";
                echo "<td>" . getSpecialistName($input['specialistID']) . "</td>\n";
                echo "<td>" . $input['userEmail'] . "</td>\n";
                echo "<td>" . $input['userInputNumber'] . "</td>\n";
                echo "<td>" . $input['userInputNumberType'] . "</td>\n";
                echo "<td class='userText'>" . htmlspecialchars_decode(htmlspecialchars_decode($input['userText']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['year'] . "</td>\n";
                echo "<td>" . getDivisionName($input['divisionID']) . "</td>\n";
                echo "<td>" . getModelName($input['modelID']) . "</td>\n";
                echo "<td>" . htmlspecialchars_decode(htmlspecialchars_decode($input['vehicleString']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['dateIn'] . "</td>\n";
                echo "<td class = 'bit". $input['isResolved'] ."'></td>\n";
                echo "<td>" . $input['dateResolved'] . "</td>\n";
            echo "</tr>\n";	
	}
	
}

function populateDynamicTableBodyLimit($sort, $column, $limit) {
    global $conn;
        
        //limitDash(getLastEntered());
	
        $stmt = $conn->prepare("SELECT * FROM ticketTable ORDER BY `ticketTable`.`". $column ."` ".$sort." LIMIT " . $limit . ";");
       
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
        
	foreach($rows as $input){
            
            echo "<tr>\n";
                echo "<td><input type='checkbox' class='nonSelect' name='select[".$input['ticketID']."]' value= '" . $input['ticketID'] . "'/></td>\n";
                echo "<td>" . $input['ticketID'] . "</td>\n";
                echo "<td>" . getTaskName($input['taskID']) . "</td>\n";
                echo "<td>" . getSpecialistName($input['specialistID']) . "</td>\n";
                echo "<td>" . $input['userEmail'] . "</td>\n";
                echo "<td>" . $input['userInputNumber'] . "</td>\n";
                echo "<td>" . $input['userInputNumberType'] . "</td>\n";
                echo "<td class='userText'>" . htmlspecialchars_decode(htmlspecialchars_decode($input['userText']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['year'] . "</td>\n";
                echo "<td>" . getDivisionName($input['divisionID']) . "</td>\n";
                echo "<td>" . getModelName($input['modelID']) . "</td>\n";
                echo "<td>" . htmlspecialchars_decode(htmlspecialchars_decode($input['vehicleString']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['dateIn'] . "</td>\n";
                echo "<td class = 'bit". $input['isResolved'] ."'></td>\n";
                echo "<td>" . $input['dateResolved'] . "</td>\n";
            echo "</tr>\n";	
	}
}

function populateDynamicTableBodyBetween($sort, $column, $highNum, $lowNum) {
    global $conn;
        
        
	
        $stmt = $conn->prepare("SELECT * FROM ticketTable WHERE ". $column ." BETWEEN " . $lowNum . " AND " . $highNum . " ORDER BY `ticketTable`.`". $column ."` ".$sort.";");
       
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
        
	foreach($rows as $input){
            
            echo "<tr>\n";
                echo "<td><input type='checkbox' class='nonSelect' name='select[".$input['ticketID']."]' value= '" . $input['ticketID'] . "'/></td>\n";
                echo "<td>" . $input['ticketID'] . "</td>\n";
                echo "<td>" . getTaskName($input['taskID']) . "</td>\n";
                echo "<td>" . getSpecialistName($input['specialistID']) . "</td>\n";
                echo "<td>" . $input['userEmail'] . "</td>\n";
                echo "<td>" . $input['userInputNumber'] . "</td>\n";
                echo "<td>" . $input['userInputNumberType'] . "</td>\n";
                echo "<td class='userText'>" . htmlspecialchars_decode(htmlspecialchars_decode($input['userText']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['year'] . "</td>\n";
                echo "<td>" . getDivisionName($input['divisionID']) . "</td>\n";
                echo "<td>" . getModelName($input['modelID']) . "</td>\n";
                echo "<td>" . htmlspecialchars_decode(htmlspecialchars_decode($input['vehicleString']), ENT_QUOTES) . "</td>\n";
                echo "<td>" . $input['dateIn'] . "</td>\n";
                echo "<td class = 'bit". $input['isResolved'] ."'></td>\n";
                echo "<td>" . $input['dateResolved'] . "</td>\n";
            echo "</tr>\n";	
	}
}






