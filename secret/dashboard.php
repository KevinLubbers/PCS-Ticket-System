<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


require_once '../phpConnection.php';
require_once '../phpStoredProcedures.php';


function getSpecialists(){
    try {
		
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->query("SELECT specialistName FROM specialistTable WHERE isActive = 1;");
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal .= "'". $input['specialistName'] . "',";
	}
	
	
	return $returnVal;
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}	
}

function getNumberOfTickets(){
    try {
		
	global $conn;
	$returnVal = "";
	
	$stmt = $conn->query("SELECT specialistID, COUNT(*) AS MyCount FROM ticketTable WHERE specialistID != 9 GROUP BY specialistID;");
	$rows = $stmt->fetchAll();
	
	foreach($rows as $input){
		$returnVal .= "'". $input['MyCount'] . "',";
	}
	
	
	return $returnVal;
	} catch(Throwable $e) {
		echo "Error Caught: " . $e->getMessage();
	}	
}


if(isset($_POST['submit'])){
    
    
    updateRow($_POST['select']);
    
    
    
}






?>







<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex, nofollow" />
    <title>PCS Support Desk</title>
    <link rel="stylesheet" href="dashPage.css">
    <link rel="shortcut icon" type="image/jpg" href="../favicon.png"/>
    <style>
        .bit1 {
            background-color: #e5ffcf;
        }
        
        .bit0 {
            background-color: #ffd2cf;
            
        }
        
        .bitToggle{
            background-color: #ffff99;
        }
        
        #tableDash th:hover{
            cursor: pointer;
        }
        
        #tableDash tr:not(:first-child):hover{
            cursor: pointer;
        }
        
        .userText {
            display:none;
        }
        
    </style>
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
            if ( window.history.replaceState ) {
                window.history.replaceState(null, null, window.location.href );
            }
   </script>
        <script>
           
           $(document).ready(function() {
               
               
               setInterval(function (){
                  //let refreshHighNum = $('.nonSelect:checkbox:first').val();
                  
                  $.ajax({
						type: "GET",
						url: "scripts/topTicketScript.php",
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                       let refreshHighNum = result;
                                                       let refreshLowNum = (refreshHighNum - 50); 
                                                       let refreshTime = setTimeout(function () {getSortBetween('ticketID', refreshHighNum, refreshLowNum);}, 20000);
                                                       //console.log(refreshHighNum);
                                                       //document.getElementById('ticketsPerPage').value = 50;
                                                       //document.getElementById('pageSelect').value = 1;
                                                       //document.getElementById('pageSelectBottom').value = 1;
                                                       $(document).mousemove(function() {
                    
                                                        // console.log("in MouseMove");
                                                        clearTimeout(refreshTime);
                    
                    
                                                        }); 
                                                       
						}
						});
                  //console.log(refreshHighNum);
                  
                  
                  
                
                
               } , 30000);
               
               
               
                $('.bit0').closest("tr").addClass("bit0"); 
                $('.bit1').closest("tr").addClass("bit1");
                $('#tableDash').on( 'change', 'input:checkbox', function() {
                    //$('input:radio[name="select"]:last').css('background-color', '#FFFFFF'); 
                        
                    $(this).closest('tr').toggleClass("bitToggle");
                    
                
                });
              
              
                    $("input[value='userText']:checkbox").prop('checked',true);
                    hideUserText();
                    
                 
                $('.hidecol').on( 'click', 'input:checkbox', function() {
                var $tbl = $("#tableDash");
                        var $tblHead = $("#tableDash th");
                        $(this).each(function() {
                           var filterColName = $(this).val();
                           var colToHide = $tblHead.filter("." + filterColName);
                           var index = $(colToHide).index();
                          
                           $tbl.find('tr :nth-child(' + (index + 1) + ')').fadeToggle();
                    });
                        });
                
                
                 
                $('#ticketsPerPage').change(function(){
                    $('#pageSelect').each(function(){
                            $(this).find("option").remove();
                        });
                    $('#pageSelectBottom').each(function(){
                            $(this).find("option").remove();
                        });    
                        
                    let topTicketPHP = <?php echo getLastEntered(); ?>;
                    let ticketsPer = $('#ticketsPerPage').val();
                    let pagesNeeded = Math.ceil(topTicketPHP / ticketsPer);
                    
                    
                    for (let i = 1; i <= pagesNeeded; i++){
                        pageSelect.add(new Option(i, i));
                        pageSelectBottom.add(new Option(i, i));
                    }
                }); 
                
                
                $('#pageSelect').change(function(){
                    let pageAmount = ($(this).val() - 1); 
                    
                    let topTicketPHP = <?php echo getLastEntered(); ?>;
                    let ticketsPer = $('#ticketsPerPage').val();
                    if (pageAmount === 0){
                        getSortLimit('ticketID');
                    }
                    else{
                        
                        let highNum = (topTicketPHP - (ticketsPer * pageAmount));
                        
                            let lowNum = (highNum - ticketsPer) + 1;
                        
                        
                        getSortBetween('ticketID', highNum, lowNum);
                        
                    }
                    document.getElementById('pageSelectBottom').value = this.value;
                });
                $('#pageSelectBottom').change(function(){
                    
                    let pageAmount = ($(this).val() - 1); 
                    
                    let topTicketPHP = <?php echo getLastEntered(); ?>;
                    let ticketsPer = $('#ticketsPerPage').val();
                    if (pageAmount === 0){
                        getSortLimit('ticketID');
                    }
                    else{
                        
                        let highNum = (topTicketPHP - (ticketsPer * pageAmount));
                        
                            let lowNum = (highNum - ticketsPer) + 1;
                        
                        
                        getSortBetween('ticketID', highNum, lowNum);
                        
                    } 
                    document.getElementById('pageSelect').value = this.value;
                }); 
                
            
                
            
                
                        
             
                
            });
            
           
            
            
            
           
        </script>
        <script>
     function getSort(column) {
					var sortType = $("#sortType").val();
					var limit = $("#ticketsPerPage").val();
					
						$.ajax({
						type: "GET",
						url: "scripts/sortScript.php",
						data: {sort_Type:sortType,column_Name:column,ticketsPerPage:limit},
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                        $("#tableDash tr").remove();
                                                    
							$("#tableDash").append(result);
                                                        
                                                        if(sortType === "ASC"){
                                                            $("#sortType").val("DESC");
                                                        }else{
                                                            $("#sortType").val("ASC");
                                                        }
                                                        
                                                        
                                                        
                                                        
                                                        $(function() {
                $('#tableDash tr:not(:first-child) td:not(:first-child)').click(function() {
                    var ticketNumber = $(this).closest("tr").find('td:eq(1)').text();
                                        
                                        
						$.ajax({
						type: "GET",
						url: "scripts/detailsScript.php",
						data: {ticketNumber:ticketNumber},
                                                contentType: "text/html; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                       alert("\nUser Submitted Text:\n\n\n" + result);
						}
						});
                
    
                 
            });
            });
                                                $('.bit0').closest("tr").addClass("bit0"); 
                                                $('.bit1').closest("tr").addClass("bit1");
                                                $('body').on( 'check', 'input:checkbox', function() {
                    
                        
                                                $(this).closest('tr').toggleClass("bitToggle");
                    
                
                                                }); 
                                               singleSpecialistFilter();
                                                      
                                                
					}
				});
														                                                                                                                       
					}       
        </script>
        
      
        <script>
            
            
            $(function() {
                $('#tableDash tr:not(:first-child) td:not(:first-child)').click(function() {
                    var ticketNumber = $(this).closest("tr").find('td:eq(1)').text();
                                        
                                        
						$.ajax({
						type: "GET",
						url: "scripts/detailsScript.php",
						data: {ticketNumber:ticketNumber},
                                                contentType: "text/html; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                       alert("\nUser Submitted Text:\n\n\n" + result);
						}
						});
                
    
                
            });
            });
            
            
            
                
        </script>
        
        
        
        
        <script>
            function hideUserText() {
                        var $tbl = $("#tableDash");
                        var $tblHead = $("#tableDash th");
                        $('input[type=checkbox]:checked').each(function() {
                           var filterColName = $(this).val();
                           var colToHide = $tblHead.filter("." + filterColName);
                           var index = $(colToHide).index();
                          
                           $tbl.find('tr :nth-child(' + (index + 1) + ')').hide();
                        });
                       
                        
                        
                        
                        
    }
            
            function saveFilter() {
					var specialist_id = $("#specialist").val();
                                        
					var filterArray = "";
                                        $('input.filterCheck').each(function() {
                                            if($(this).is(':checked')) {
                                                filterArray += $(this).val() + "-";
                                            }
                                           
                                        });
					
						$.ajax({
						type: "GET",
						url: "scripts/filterScript.php",
						data: {specialist:specialist_id,filterArray:filterArray},
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(response){
							alert(response);
						}
						});
					
					
					
                                    }
                                    
                                    
           function loadFilter() {
					var specialist_id = $("#specialist").val();
                                        
					
					
						$.ajax({
						type: "GET",
						url: "scripts/loadScript.php",
						data: {specialist:specialist_id},
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(response){
                                                    
							var loadString = response.split('-');
                                                        $("input[class='filterCheck']:checkbox").prop('checked',false);
                                                        $.each(loadString, function(key, value){
                                                           
                                                            $("input[name='"+value+"']:checkbox").prop('checked',true);
                                                        });
                                                        hideUserText();
                                                        $("#sortType").val("DESC");
                                                        getSort('ticketID');
                                                        setTimeout(singleSpecialistFilter(), 500);
						}
                                                
						});
                                                
					
					
                                    }
            function hideFilter() {
					var textOG = $("#hideButton").text();
                                      
                                        $("#hideButton").text(textOG === 'Hide' ? 'Show' : 'Hide');
                                        $("#selectFilter").animate({
                                            height: 'toggle'
                                        }, "slow");

                                    }                        
                                    
           function reassignFilter() {
               
               
               
                                    var specialist_id = $("#specialist").val();
                                     var ticket_id = $('.nonSelect:checkbox:checked').val();
                                            
                
                
                //$("input.nonSelect :checked").val();
                                    
                                    if (specialist_id === 'noChoice'){
                                        alert("ERROR: Please Select a Specialist from the Dropdown menu BEFORE reassigning a ticket.");
                                        
                                        return;
                                    }
                                    else{
                                        $.ajax({
						type: "GET",
						url: "scripts/reassignScript.php",
						data: {ticket:ticket_id,specialist:specialist_id},
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(response){
                                                    
							alert("Your Ticket has been Reassigned to: " + response + "\nA New Email has been generated");
                                                       
                                                       location.reload();
						}
                                                
						});
                                    }
                                    
                                    
                                    
           }
           
           function pushEmail() {
                                     var ticket_id = $('.nonSelect:checkbox:checked').val();
                                     
                                     
                                     if (isNaN(ticket_id) === false){
                                         $.ajax({
						type: "GET",
						url: "scripts/pushEmailScript.php",
						data: {ticket: ticket_id},
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(response){
                                                    
							alert("Another Email has been generated" + response);
                                                       
                                                       
						}
                                                
						});
                                     }
                                     else{
                                         return;
                                     }                         
           }
           
           function singleSpecialistFilter(){
               specialist_id = $("#specialist").val();
               if(specialist_id == "noChoice") {
                   $('#tableDash tr').each(function(){
                    $(this).each(function(){
                        $(this).addClass('show');
                        $(this).removeClass('hidden');
                    });
                });
                    return;
               }
               else if($('input.nonSelect:checkbox').is(':checked')){
                   return;
               }
               else{
                    $('#tableDash tr:has(td)').each(function(){
                    $(this).each(function(){
                        $(this).addClass('show');
                        $(this).removeClass('hidden');
                        var tr = $(this);
                        if (tr.find('td:eq(3)').text()!==specialist_id){
                            tr.addClass('hidden');
                            tr.removeClass('show');
                        }
                    });
                });
                }
               
               
           }
           function getSortLimit(column) {
                                        $("#sortType").val("DESC");
					var sortType = $("#sortType").val();
					var limit = $("#ticketsPerPage").val();
                                        
					
						$.ajax({
						type: "GET",
						url: "scripts/sortScript.php",
						data: {sort_Type:sortType,column_Name:column,ticketsPerPage:limit},
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                        $("#tableDash tr").remove();
                                                    
							$("#tableDash").append(result);
                                                    
                                                    $(function() {
                $('#tableDash tr:not(:first-child) td:not(:first-child)').click(function() {
                    var ticketNumber = $(this).closest("tr").find('td:eq(1)').text();
                                        
                                        
						$.ajax({
						type: "GET",
						url: "scripts/detailsScript.php",
						data: {ticketNumber:ticketNumber},
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                       alert("\nUser Submitted Text:\n\n\n" + result);
						}
						});
                
    
                 
            });
            });
                                                $('.bit0').closest("tr").addClass("bit0"); 
                                                $('.bit1').closest("tr").addClass("bit1");
                                                $('body').on( 'check', 'input:checkbox', function() {
                    
                        
                                                $(this).closest('tr').toggleClass("bitToggle");
                    
                
                                                }); 
                                               singleSpecialistFilter();
                                                        
                                                    }});
                                                
                                            }
                function getSortBetween(column, high, low) {
                                        $("#sortType").val("DESC");
					var sortType = $("#sortType").val();
                                        
                                        
					//var limit = $("#ticketsPerPage").val();
                                        
					
						$.ajax({
						type: "GET",
						url: "scripts/betweenScript.php",
						data: {sort_Type:sortType,column_Name:column,highNum:high,lowNum:low},
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                        $("#tableDash tr").remove();
                                                    
							$("#tableDash").append(result);
                                                    
                                                    $(function() {
                $('#tableDash tr:not(:first-child) td:not(:first-child)').click(function() {
                    var ticketNumber = $(this).closest("tr").find('td:eq(1)').text();
                                        
                                        
						$.ajax({
						type: "GET",
						url: "scripts/detailsScript.php",
						data: {ticketNumber:ticketNumber},
                                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "html",
						success: function(result){
                                                    
                                                       alert("\nUser Submitted Text:\n\n\n" + result);
						}
						});
                
    
                 
            });
            });
                                                $('.bit0').closest("tr").addClass("bit0"); 
                                                $('.bit1').closest("tr").addClass("bit1");
                                                $('body').on( 'check', 'input:checkbox', function() {
                    
                        
                                                $(this).closest('tr').toggleClass("bitToggle");
                    
                
                                                }); 
                                               singleSpecialistFilter();
                                                        
                                                    }});
                                                
            } 
            
            
                                            
            
        </script>
        
</head>

<body>

    <div class="banner" id="banner">
        <div id = "imgWrap">
            <img src="../PCSLogo.jpg" />
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

<div id="viewWrap">
    
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
    <div id="graphWrap">
        
        <div>
            <canvas id="myChart" style="max-width: 500px;max-height: 300px; float:right"></canvas>
        </div>



<script>
     
  var specialists = [<?php echo getSpecialists();     ?>];
  var numberOfTickets = [<?php echo getNumberOfTickets();   ?>];
  var barColors = "white";
  
  //console.log(specialists); 
  //console.log(numberOfTickets);
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: specialists,
      datasets: [{
        backgroundColor: barColors,
        label: '# of Tickets',
        data: numberOfTickets,
        borderWidth: 1
      }]
    },
    options: {
      plugins:{
        legend: {
            display: false
        },
        title: {  
          display: true,
          text: "PCS Tickets Resolved (Real-Time)",
          color: "white"
        }
      },
      scales: {
        x: {
            beginAtZero: true,
            ticks: {
             color: "white"
            },
            grid :{
                color: "black"
            }
        },  
        y: {
          beginAtZero: true,
         ticks: {
             color: "white"
         },
          grid: {
              color: "black"
          }
        }
      }
    }
  });
</script>
        
    </div>
    <div id="dashWrap">
    <form method="POST" enctype="multipart/form-data">
        <div id="filter">
       <?php
        populateSpecialist(); 
        
        populateFilter();
       ?> 
        </div>
    </form>

    <div>
        <label style="color:white;font-size:1.33em;margin-top:15px;">Show Tickets per Page: </label>
        <select onchange="setTimeout(getSortLimit('ticketID'), 500);" id="ticketsPerPage">
            <option>Select</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    
    <div>
        <label style="color:white;font-size:1.33em;margin-top:15px;">Jump to Page: </label>
        <select id="pageSelect">
            
            
        </select>
    </div>



    <form action="" name="reassignForm">
        
    </form>
    
    <form action="" name="pushForm">
        
    </form>
    

<form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		
		<div class="field">
		    <div class="control">
                        <button name="submit" class="button is-info" >Resolve Ticket</button>
                        <button name="reassign" class="button is-info" onclick="reassignFilter()" form="reassignForm" >Reassign Ticket</button>
                        <button name="emailPush" class="button is-info" onclick="pushEmail()" form="pushForm">Push Email</button>
                    </div>
                </div>
		
		<input type="hidden" id="sortType" name="sortType" value="ASC" />
                <input type="hidden" id="specialistReassign" name="specialistReassign" value="0"; />
                
		<!-- Populate Table Function | Must Have Limiter of 50-100? | Must Dynamically Build -->
		<table id = "tableDash" style="width: 100%;">
                    <tbody>
		<!-- Populate Row Function PHP | Get the last 50-100 ticket entries AND display -->
		
			<?php
                        populateDynamicTableHeader();
                        populateDynamicTableBodyLimit('DESC', 'ticketID', "50");
                        
                        ?>
                    </tbody>    
                </table>
                
		
</form>

<div>
    <label style="color:white;font-size:1.33em;margin-top:15px;">Jump to Page: </label>
    <select id="pageSelectBottom">
     
    </select>
</div>
        
        
    </div>

    
</div>


</body>

</html>





