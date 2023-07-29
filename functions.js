function checkEmail(){
    var userEmail = $("#email").val();
    
    var check = userEmail.indexOf("@militarycars.com");
    var ruairiCheck = userEmail.indexOf("@mymilitarycars.com");
    var collinsCheck = userEmail.indexOf("@intlauto.com");
    
    if(check === -1 && ruairiCheck === -1 && collinsCheck === -1){
        $("#email").removeClass("toggleBorderGreen");
        $("#email").addClass("toggleBorderRed");
    }
    else{
        $("#email").removeClass("toggleBorderRed");
        $("#email").addClass("toggleBorderGreen");
    }
}

function checkDropdown(sender){
   var checkString = document.getElementById(sender.id).value;
    
    if(checkString === "Select Dropdown"){
        $(sender).attr('style', "border: red solid 2px");
        
    }
    else{
        $(sender).attr('style', "border: green solid 2px");
        
    }
}

function checkDropdownDivision(sender){
   getDivisionId();
   var checkString = document.getElementById(sender.id).value;
    
    if(checkString === "Select Dropdown"){
        $(sender).attr('style', "border: red solid 2px");
        $("#vehicleModel").attr('style', "border: red solid 2px");
    }
    else{
        $(sender).attr('style', "border: green solid 2px");
        $("#vehicleModel").attr('style', "border: green solid 2px");
    }
}

function checkTrim(){
    var vehicleString = $("#vehicleString").val();
    
    if (vehicleString.lenth < 1 || vehicleString === null || vehicleString === ""){
        $("#vehicleString").addClass("toggleBorderRed");
        $("#vehicleString").removeClass("toggleBorderGreen");
    }
    else {
        $("#vehicleString").addClass("toggleBorderGreen");
        $("#vehicleString").removeClass("toggleBorderRed");
    }
    
}

function checkInfo(){
    ninePaste();
    var info = $("#userInputNumber").val();
    //alert($("input[type=radio]:checked", "#formForm").val());
    //alert(info.length + " " + radio);
    
    var radio = $("input[type=radio]:checked", "#formForm").attr('id');
    
    
    
    if ((info.length < 3 && radio === "vin") || (info.length < 6 && radio === "foNumber") || (info.length < 13 && radio === "customerNumber") || (info.length < 1) || (radio === undefined)){
        $("#userInputNumber").addClass("toggleBorderRed");
        $("#userInputNumber").removeClass("toggleBorderGreen"); 
    }
    else {
        $("#userInputNumber").addClass("toggleBorderGreen");
        $("#userInputNumber").removeClass("toggleBorderRed");
    }
    
   

}

function checkDetails(){
     var detailString = $("#userText").val();
    
    if (detailString.lenth < 1 || detailString === null || detailString === ""){
        $("#userText").addClass("toggleBorderRed");
        $("#userText").removeClass("toggleBorderGreen");
    }
    else {
        $("#userText").addClass("toggleBorderGreen");
        $("#userText").removeClass("toggleBorderRed");
    }
}

