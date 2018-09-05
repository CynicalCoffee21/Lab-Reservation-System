  // This allows the calender to be displayed for date selection. 
$(document).ready(function() {
    $("#date").datepicker();
});
//Script that keeps the course and event sections from displaying at the same time.
function show1(){
  document.getElementById('Event_section').style.display = 'none';
  document.getElementById('Course_section').style.display = 'block';
}
function show2(){
  document.getElementById('Course_section').style.display = 'none';
  document.getElementById('Event_section').style.display = 'block';
}
function is_valid_phone(inputtxt){
  var phoneno = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
  if(inputtxt.value.match(phoneno)){
      return true;
  }else{
      alert("bad number");
      return false;
  }
}
// Populates the time select fields
function populate(select) {
    var select = $(select); // Select being the html id sent as a parameter
    var hours, minutes;
	
    for(var i = 60; i <= 770; i += 15){
        hours = Math.floor(i / 60);
        minutes = i % 60;
		
        if (minutes < 10){
            minutes = '0' + minutes; // adding leading zero
        }
		
        hours = hours % 12;
		
        if (hours === 0){
            hours = 12;
        }

		if(select.attr('value') != "" && select.attr('value') == (hours + ':' + minutes)){
			select.append( $("<option selected='selected'></option>") 
				.attr('value', hours + ':' + minutes) // The value should be the same as the text displayed. (H:MM)
				.text(hours + ':' + minutes) ); 
		} else {
			select.append( $("<option></option>") 
				.attr('value', hours + ':' + minutes) // The value should be the same as the text displayed. (H:MM)
				.text(hours + ':' + minutes) ); 
		}
    }
}
var count = document.getElementsByName("date[]").length + 1;
function addDates(divName){															
	var newdiv = document.createElement('div'); 
	var newAMPMS = 'ap_start' + count + '';
	var newAMPME = 'ap_end' + count + '';
	var newDate = 'date[' + count + ']';
	var newStart = 'time_start[' + count + ']';
	var newEnd = 'time_end[' + count + ']';								 
	/* 
		Add the fields that belong to the dates section.
		Every time the 'Add A Date' button is clicked, 
		a new dates section is to be generated. 
	*/
	newdiv.innerHTML = "<br><div class='form-group'>" +
		"<label class='col-sm-2 col-sm-offset-1 control-label' for='date'>Date: </label>" +
		"<div class='col-sm-2'> <input class='form-control'" +
		"type='text' id='date" + count +"'+ name='" + newDate + "' placeholder='MM/DD/YYYY' " +
		"value='' required> </div>" + /* Date field */
		"<div class='form-group'>" +
		"<label class='col-sm-2 control-label' for='time_start'>Start time: </label>" +
		"<div class='col-sm-2'> <select class='form-control' type='text' id='time_start" + count +"'"+
		"name='" + newStart +  
		"'value='' required> </select></div>" + /* Starting time field */
		"<div class='col-sm-2'> <div class='radio-inline'>" +
		"<label> <input type='radio' name='" + newAMPMS + "' value='AM'" +
		">AM" + /* AM */
		"</label> </div> <div class='radio-inline'>" +
		"<label> <input type='radio' name='" + newAMPMS + "' value='PM'" +
		">PM" + /* PM */
		"</label> </div> </div> </div> " + 
		"<div class='form-group'>" +
		"<label class='col-sm-2 control-label col-sm-offset-5 control-label' for='time_end'>End time: </label>" +
		"<div class='col-sm-2'> <select class='form-control' type='text' id='time_end" + count +"'"+
		"name='" + newEnd + 
		"'value='' required> </select></div>" +  /* Ending time field */
		"<div class='col-sm-2'> <div class='radio-inline'>" +
		"<label> <input type='radio' name='" + newAMPME + "' value='AM'" +
		"> AM" + /* AM */
		"</label> </div> <div class='radio-inline'>" +
		"<label> <input type='radio' name='" + newAMPME + "' value='PM'" +
		"> PM" + /* PM */
		"</label> </div> </div> </div> ";	
	document.getElementById(divName).appendChild(newdiv);
								
	populate("#time_start" + count);
	populate("#time_end" + count);
								
	$(document).ready(function(){ $("#date" + count).datepicker(); });
								
	count++;
}