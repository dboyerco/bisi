function frmtssn(obj,event) {
	if (obj.value.length == 3) {
		obj.value = obj.value + "-";
	}
	if (obj.value.length == 6) {
		obj.value = obj.value + "-";
	}
}
function frmtdate(obj,event) {
	if (obj.value.length == 2) {
		obj.value = obj.value + "/";
	}
	if (obj.value.length == 5) {
		obj.value = obj.value + "/";
	}
}
function frmtbphone(obj,event) {
//	if (obj.value.length == 1) {
//		obj.value = "(" + obj.value;
//	}
	if (obj.value.length == 3) {
		obj.value = obj.value + " ";
	}
	if (obj.value.length == 7) {
		obj.value = obj.value + "-";
	}
	if (obj.value.length == 12) {
		obj.value = obj.value + " ";
	}
	if (obj.value.length == 13) {
		hldv = obj.value;
		obj.value = hldv.substr(0, 13) + " ex. " + hldv.substr(15);
	}
}

function frmtphone(obj,event) {
//	if (obj.value.length == 1) {
//		obj.value = "(" + obj.value;
//	}
	if (obj.value.length == 3) {
		obj.value = obj.value + " ";
	}
	if (obj.value.length == 7) {
		obj.value = obj.value + "-";
	}
}

function numericOnly(e, t) {
	try {
		if(window.event) {
			var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
		}
        else { return true; }
			if (charCode > 47 && charCode < 58) {
                return true;
            } else {
				alert('Only numbers are allowed');
                return false;
			}	
        }
    catch (err) {
        alert(err.Description);
	}	
}		
