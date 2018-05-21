// last modified 08/27/2013 -- added checkdate function
// last modified 03/16/2004 -- allow Canadian Zip codes in addition to US zip codes

var strAlertMsg = ""
var focusField = ""
function isDate(strFieldName,strMsg){
   var objFormField = document.forms[0].elements[strFieldName];
   strDate = objFormField.value;
   if(strDate.length>0){
           var dateregex=/^[ ]*[0]?(\d{1,2})\/(\d{1,2})\/(\d{4,})[ ]*$/;
            var match=strDate.match(dateregex);
            if (match){
                      var tmpdate=new Date(match[3],parseInt(match[1],10)-1,match[2]);
                 if (tmpdate.getDate()==parseInt(match[2],10) && tmpdate.getFullYear()==parseInt(match[3],10) && (tmpdate.getMonth()+1)==parseInt(match[1],10)){
                    return true;
                    }
            }
                   strAlertMsg += "- "+ strMsg +" is Required.\nExample: 01/01/2003\n";
              if(!focusField) focusField =""+ strFieldName +"";
        return false;
   }
   else{
        return true;
   }
}
//========================================================================
function checkdate(strFieldName,strMsg, reQuired){
   var objFormField = document.forms[0].elements[strFieldName];
   strDate = objFormField.value;
   if(strDate.length>0){
           var dateregex=/^[ ]*[0]?(\d{1,2})\/(\d{1,2})\/(\d{4,})[ ]*$/;
            var match=strDate.match(dateregex);
            if (match){
                      var tmpdate=new Date(match[3],parseInt(match[1],10)-1,match[2]);
                 if (tmpdate.getDate()==parseInt(match[2],10) && tmpdate.getFullYear()==parseInt(match[3],10) && (tmpdate.getMonth()+1)==parseInt(match[1],10)){
                    return true;
                    }
            }
                   strAlertMsg += "- "+ strMsg +" is Required.\nExample: 01/01/2003\n";
              if(!focusField) focusField =""+ strFieldName +"";
        return false;
   }
   else{
	if (reQuired == "no") {
        	return true;
	} else {
		strAlertMsg += "- "+ strMsg +" is Required.\nExample: 01/01/2003\n";
       	       if(!focusField) focusField =""+ strFieldName +"";
        	return false;
	}
   }
}
//========================================================================
//Validate Us Phone. Ex. (999) 999-9999 or (999)999-9999
function isPhone(strFieldName,strMsg)     {
    var objFormField = document.forms[0].elements[strFieldName];
    var strValue = objFormField.value;
    var objRegExp  = /^\([1-9]\d{2}\)\s?\d{3}\-\d{4}$/;
     if(!objRegExp.test(strValue)){
         strAlertMsg += "- "+ strMsg +" is Required.\n    Example:\n(999)999-9999 or (999) 999-9999\n";
    if(!focusField) focusField=""+ strFieldName +"";
         return false;
         }
    return true;
}
//========================================================================
//Validate US zip code in 5 digit format or zip+4 format. 99999 or 99999-9999
// Validates any Canadian Zip Code
function isZipCode(strFieldName,strMsg)     {
    var objFormField = document.forms[0].elements[strFieldName];
    var strValue = objFormField.value;
    var objRegExp  = /(^\d{5}$)|(^\d{5}-\d{4}$)|(^\D{1}\d{1}\D{1}\-?\d{1}\D{1}\d{1}$)/;
    if(!objRegExp.test(strValue)){
         strAlertMsg += "- "+ strMsg +" is Required.\n";
    if(!focusField) focusField =""+ strFieldName +"";
         return false;
         }
    return true;
}
//==========================================================================
//Validate the Select
function hasSelection(strFieldName,strMsg)     {
    var objFormField = document.forms[0].elements[strFieldName];
    if(objFormField.selectedIndex ==0)     {
         strAlertMsg += "- "+ strMsg +" is Required.\n";
    if(!focusField) focusField =""+ strFieldName +"";		 
          return false;
           }
    return true;
}
//========================================================================
//Validate Check Box
function isChecked(strFieldName,strMsg) {
    var objFormField= document.forms[0].elements[strFieldName];
    var strValue= objFormField.checked;
    if (strValue.length > 10) {
         //alert("The \""+ strMsg +"\" box is checked!")
         //} else {
         strAlertMsg += "- "+ strMsg +" is Required.\n";
    if(!focusField) focusField =""+ strFieldName +"";
         return false;
         }
    return true;
}
//========================================================================
//Validate Text Box
function isEmpty(strFieldName,strMsg){
    var objFormField = document.forms[0].elements[strFieldName];
    var strValue = objFormField.value;
    strValue = strValue.split(" ").join("")
    if(strValue.length<1){
         strAlertMsg += "- "+ strMsg +" is Required.\n";
    if(!focusField) focusField =""+ strFieldName +"";
         return false;
         }
    return true;
}
//========================================================================
//Validate Email
function isEmail(strFieldName,strMsg){
    var objFormField = document.forms[0].elements[strFieldName]
    var strEmail = objFormField.value;
    var bolValid = true;
         if(strEmail.length < 7){
         bolValid = false;
         }
         if(strEmail.lastIndexOf(" ") >0){
         bolValid = false;
         }
         var intLastDot = strEmail.lastIndexOf(".")
         if(intLastDot == -1 ||  strEmail.length - intLastDot >4){
         bolValid = false;
         }
         var intAt = strEmail.lastIndexOf("@")
         if(intAt == -1 ||  strEmail.length - intAt < 5){
         bolValid = false;
         }
         if(!bolValid){
         strAlertMsg += "- "+ strMsg +" is Required.\n";
    if(!focusField) focusField =""+ strFieldName +"";
         }
    return bolValid;
}
//========================================================================
//Validate Radio Button
function checkRadioControl(strFieldName,strMsg){
         var objFormField = document.forms[0].elements[strFieldName]
         intControlLength = objFormField.length
         bolSelected = false;
         for (i=0;i<intControlLength;i++){
         if(objFormField[i].checked){
         bolSelected = true;
         break;
         }
    }    
     if(! bolSelected){
         strAlertMsg += "- "+ strMsg +" is Required.\n";
   if(!focusField) focusField =""+ strFieldName +"[0]";		 
         return false;
         }
    return true;
}
//========================================================================
//Compare the Fields
function compareFields(strFieldName1,strFieldName2,strMsg){
         var objFormField1= document.forms[0].elements[strFieldName1];
         var objFormField2= document.forms[0].elements[strFieldName2];
         var strValue1= objFormField1.value;
         var strValue2= objFormField2.value;
    if(strValue1 != strValue2){
         strAlertMsg +="The "+ strMsg +" fields do not match, please try again.\n";
     if(!focusField) focusField =""+ strFieldName1 +"";
         return false;
          }
    return true;
}
//========================================================================
//Format Phone Number 9999999999 = (999)999-9999
//Call the function like so onKeyPress="javascript:formatPhone(this);
function formatPhone(objFormField){
    intFieldLength = objFormField.value.length;
    if(intFieldLength == 3){
         objFormField.value = "(" + objFormField.value + ") ";
         return false;
         }
   if(intFieldLength >= 9 && intFieldLength <= 10){
       objFormField.value = objFormField.value + "-";
       return false;
       }
}
//========================================================================

// SSN - strip out delimiters and check for 9 digits

function isSSN (strFieldName,strMsg) {
    var objFormField = document.forms[0].elements[strFieldName]
    var strSSN = objFormField.value;
	
	
	if (strSSN == "") {
        strAlertMsg += "- "+ strMsg +" is Required.\n";
    	if(!focusField) focusField =""+ strFieldName +"";
        	return false;
    }


	var stripped = strSSN.replace(/[\(\)\.\-\ ]/g, ''); //strip out acceptable non-numeric characters
	
	
    if (isNaN(parseInt(stripped))) {
        strAlertMsg += "- "+ strMsg +" is Required.\n";
	    if(!focusField) focusField =""+ strFieldName +"";
        return false;
    }

	
    if (!(stripped.length == 9)) {
        strAlertMsg += "- "+ strMsg +" is Required.\n";
	    if(!focusField) focusField =""+ strFieldName +"";
        return false;
    }
	
    return true;
}
