function TabNext(obj,event,len,next_field) {
var phone_field_length=0; 
	if (event == "down") {
		phone_field_length=obj.value.length;
		}
	else if (event == "up") {
		if (obj.value.length != phone_field_length) {
			phone_field_length=obj.value.length;
			if (phone_field_length == len) {
				next_field.focus();
				}
			}
		}
	}
