<?php

require_once 'phpStoredProcedures.php';
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex, nofollow" />
    <title>PCS Support Desk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="landing.css">
    <link rel="shortcut icon" type="image/jpg" href="/favicon.png"/>
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
            <div id="errorNavText">PCS Home</div>
            </a>
        </div>
        
    </div>

    <div class="faqContainer">
    <div class="titleContainer">
        <h1 class="enterTitle">Error Page</h1>
    </div>

    
    <?php
    if (isset($_GET)){
        
    
        $errorLog = $_GET;
        echo "<ul>";
        echo "<div>Unexpected Error, Please Correct and Resubmit Ticket</div>";
        foreach($errorLog as $key => $error){
		echo "<li>Error Code: 0" .$key. "<p>";
                echo "Error Printout: " .$error. "</li>";
                
	}
        echo "</ul>";
    }
    
    ?>
    </div>

</body>

</html>


