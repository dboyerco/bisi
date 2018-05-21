/*
http://www.javascript-coder.com/html-form/javascript-form-validation.phtml

<script language="JavaScript" type="text/javascript">
var frmvalidator = new Validator("form1");
frmvalidator.addValidation("{$value}","req","You must fill in the required field.");
</script>
*/

function selectFolder(URL, folder)
{
	parent.location.href = URL+folder	
}

function weatherStateResubmit(ScheduleID, ContentType, state, ContentName)
{
	parent.location.href = "PM_content_alerts2.php?ContentType="+ContentType+"&state="+state+"&ContentName="+ContentName+"&ScheduleID="+ScheduleID
}

function lotteryStateResubmit(ContentType, LotteryState)
{
	parent.location.href = "PM_content_alerts2.php?ContentType="+ContentType+"&LotteryState="+LotteryState
}

function deleteConfirm(ScheduledAlertID)
{
	input_box=confirm("Are you sure you want to remove the alert?");
	if (input_box==true)
	{ 
		parent.location.href = "PM_content_alerts.php?action=delete&ScheduledAlertID="+ScheduledAlertID
	}
}

function populateText(selectbox) <!--autopopulates the zipcode field if city is filled in -->
{ 
	document.forms[0].zipcode.value = selectbox.value
} 

function ScheduledAlertsValidation()
{	
	filled = false;
	if(form.SubscriberUserScheduledAlertsMonTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsTueTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsWedTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsThuTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsFriTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsSatTime.value != "")
	{
		filled = true;
	}
	if(form.SubscriberUserScheduledAlertsSunTime.value != "")
	{
		filled = true;
	}
	if(filled == false)
	{
		alert("You must select a time for at least one day of the week.")
		return false;
	}
	return true;
}

function loginValidation(form)
{
	if(notEmpty(form.username) && form.username.value != "Phone Number")
	{
		if(notEmpty(form.password) && form.password.value != "Your message")
		{
			return true;
		}
	}
	alert("You must input both username and password");
	return false;
}

function notEmpty(elem)
{
	var str = elem.value;
	if(str.length == 0 || str == null || str == "" || str.charAt(0) == ' ' )
	{
		alert("You did not fill in the required fields");
		return false;
	} 
	else 
	{
		return true;
	}
}

function validatePwd() 
{
	var invalid = " "; // Invalid character is a space
	var minLength = 6; // Minimum length
	var pw1 = document.form.password.value;
	var pw2 = document.form.password2.value;
	
	if (pw1 == '' || pw2 == '')  // check for a value in both fields.
	{
		alert('Please enter your password twice.');
		return false;
	}
	// check for minimum length
	
	if (document.form.password.value.length < minLength) 
	{
		alert('Your password must be at least ' + minLength + ' characters long. Try again.');
		return false;
	}
	if ((document.form.RecoveryPhrase.value.length == 0) || (document.form.RecoveryPhrase.value == null))
	//if (document.form.RecoveryPhrase.value.length < 1) 
	{
		alert('You must select a recovery question');
		return false;
	}
	if ( (document.form.RecoveryAnswer.value.length == 0) || (document.form.RecoveryAnswer.value == null) ||  (document.form.RecoveryAnswer.value.indexOf(invalid) > -1) )
	//if (document.form.RecoveryAnswer.value.length < 1) 
	{
		alert('You must fill in the recovery answer\n No spaces are allowed.');
		return false;
	}
	// check for spaces
	if (document.form.password.value.indexOf(invalid) > -1) 
	{
		alert("Sorry, spaces are not allowed.");
		return false;
	}
	else 
	{
		if (pw1 != pw2) 
		{
			alert ("Password mismatch - Both the Password and the Verify Password must match to proceed. Please click OK to re-enter.");
			return false;
		}
		else 
		{		
				return true;
	    }
	}
}


/*
  -------------------------------------------------------------------------
	                    JavaScript Form Validator 
                                Version 2.0.2
	Copyright 2003 JavaScript-coder.com. All rights reserved.
	You use this script in your Web pages, provided these opening credit
    lines are kept intact.
	The Form validation script is distributed free from JavaScript-Coder.com

	You may please add a link to JavaScript-Coder.com, 
	making it easy for others to find this script.
	Checkout the Give a link and Get a link page:
	http://www.javascript-coder.com/links/how-to-link.php

    You may not reprint or redistribute this code without permission from 
    JavaScript-Coder.com.
	
	JavaScript Coder
	It precisely codes what you imagine!
	Grab your copy here:
		http://www.javascript-coder.com/
    -------------------------------------------------------------------------  
*/
function Validator(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: could not get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=form_submit_handler;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
}
function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}
function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}
function form_submit_handler()
{
	for(var itr=0;itr < this.elements.length;itr++)
	{
		if(this.elements[itr].validationset &&
	   !this.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}
function add_validation(itemname,descriptor,errstr)
{
  if(!this.formobj)
	{
	  alert("BUG: the form object is not set properly");
		return;
	}//if
	var itemobj = this.formobj[itemname];
  if(!itemobj)
	{
	  alert("BUG: Couldnot get the input object named: "+itemname);
		return;
	}
	if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
  itemobj.validationset.add(descriptor,errstr);
}
function ValidationDesc(inputitem,desc,error)
{
  this.desc=desc;
	this.error=error;
	this.itemobj = inputitem;
	this.validate=vdesc_validate;
}
function vdesc_validate()
{
 if(!V2validateData(this.desc,this.itemobj,this.error))
 {
    this.itemobj.focus();
		return false;
 }
 return true;
}
function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}
function add_validationdesc(desc,error)
{
  this.vSet[this.vSet.length]= 
	  new ValidationDesc(this.itemobj,desc,error);
}
function vset_validate()
{
   for(var itr=0;itr<this.vSet.length;itr++)
	 {
	   if(!this.vSet[itr].validate())
		 {
		   return false;
		 }
	 }
	 return true;
}
function validateEmailv2(email)
{
// a very simple email validation checking. 
// you can add more complex email checking if it helps 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}
function V2validateData(strValidateStr,objValue,strError) 
{ 
    var epos = strValidateStr.search("="); 
    var  command  = ""; 
    var  cmdvalue = ""; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    } 
    switch(command) 
    { 
        case "req": 
        case "required": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }//if 
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search("[^A-Za-z0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alpha-numeric characters allowed "; 
                }//if 
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search("[^0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits allowed "; 
                }//if               
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break;               
           }//numeric 
        case "alphabetic": 
        case "alpha": 
           { 
              var charpos = objValue.value.search("[^A-Za-z]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//alpha 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
        case "email": 
          { 
               if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
        case "gt": 
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               }//if               
               alert(strError); 
               return false;                 
             }//if             
            break; 
         }//case greaterthan 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
              alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue)) 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = objValue.name+": Please Select one option "; 
              }//if                                                               
              alert(strError); 
              return false;                                   
             } 
             break; 
         }//case dontselect 
    }//switch 
    return true; 
}
function isValidDOB(e) {
	var dateString = document.getElementById(e).value;
	var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/ ;
	if(!(date_regex.test(dateString))) {
		return false;
	} else {
		var _MS_PER_DAY = 1000 * 60 * 60 * 24;
		var d = new Date(dateString);
		var c = new Date();
		var utc1 = Date.UTC(c.getFullYear(), c.getMonth(), c.getDate());
  		var utc2 = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
		var diff = Math.floor((utc1 - utc2) / _MS_PER_DAY);
		if (diff < 365) {
			return false;
		}	
	}	
	return true;
};	
function isValidDate(e) {
	var dateString = document.getElementById(e).value;
	var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/ ;
	if(!(date_regex.test(dateString))) {
		return false;
	} else {
		var _MS_PER_DAY = 1000 * 60 * 60 * 24;
		var d = new Date(dateString);
		var c = new Date();
		var utc1 = Date.UTC(c.getFullYear(), c.getMonth(), c.getDate());
  		var utc2 = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
		var diff = Math.floor((utc1 - utc2) / _MS_PER_DAY);
		if (diff < 0) {
			return false;
		}	
	}	
	return true;
};	
function isValidEDate(e) {
	var dateString = document.getElementById(e).value;
	var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/ ;
	if(!(date_regex.test(dateString))) {
		return false;
	} 
	return true;
};	

function isValidDiff(c,d) {
	var c = new Date(c);
	var d = new Date(d);
	var _MS_PER_DAY = 1000 * 60 * 60 * 24;
	var utc1 = Date.UTC(c.getFullYear(), c.getMonth(), c.getDate());
  	var utc2 = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
	var diff = Math.floor((utc2 - utc1) / _MS_PER_DAY);
	if (diff < 0) {
		return false;
	} else {
		return true;
	}
};
/*
	Copyright 2003 JavaScript-coder.com. All rights reserved.
*/

