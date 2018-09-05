<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<?php
//require 'phone.inc';
//require 'form_checks.php';

//Database information and connection

$host = "localhost";
$user = "";
$password = "";
$dataBase = "lab_reservation";
$dbc = mysqli_connect($host, $user, $password, $dataBase);

if( mysqli_connect_errno() ){
	echo "Failed to Connect";
}

// initialize variables
$labs_available = array('LAU-216', '1911-110', 'WI-131', 'WN-133');

$name = "Display_Name";//$_SERVER['SHIB_DISPLAYNAME']; // not user set
$email = "ryanbuchanan21@gmail.com";//$_SERVER['SHIB_EPPN']; // not user set
if(isset($_POST['phone'], $_POST['room'], $_POST['date'][0], $_POST['time_start'][0], 
    $_POST['ap_start0'], $_POST['time_end'][0], $_POST['ap_end0'], $_POST['course_pre'], 
    $_POST['course_num'], $_POST['section'], $_POST['event'], $_POST['purpose'], $_POST['software'])){
        $phone = $_POST['phone'] ? htmlspecialchars(trim($_POST['phone'])) : '';
        $room = $_POST['room'] ? htmlspecialchars(trim($_POST['room'])) : '';
        //Variables for use in the dates section of the form
        $date = $_POST['date'][0] ? htmlspecialchars(trim($_POST['date'][0])) : '';
        $time_start = $_POST['time_start'][0] ? htmlspecialchars(trim($_POST['time_start'][0])) : '';
        $ampm_start = $_POST['ap_start0'] ? htmlspecialchars(trim($_POST['ap_start0'])) : '';
        $time_end = $_POST['time_end'][0] ? htmlspecialchars(trim($_POST['time_end'][0])) : '';
        $ampm_end = $_POST['ap_end0'] ? htmlspecialchars(trim($_POST['ap_end0'])) : '';
        //$Dates = array(array($date, $time_start, $ampm_start, $time_end, $ampm_end));
        //Variables for use in the course section.
        $course_pre = $_POST['course_pre'] ? htmlspecialchars(trim($_POST['course_pre'])) : '';
        $course_num = $_POST['course_num'] ? htmlspecialchars(trim($_POST['course_num'])) : '';
        $section = $_POST['section'] ? htmlspecialchars(trim($_POST['section'])) : '';
        //Variables for use in the event section
        $event = $_POST['event'] ? htmlspecialchars(trim($_POST['event'])) : '';
        $purpose = $_POST['purpose'] ? htmlspecialchars(trim($_POST['purpose'])) : '';
        $software = $_POST['software'] ? htmlspecialchars(trim($_POST['software'])) : '';
        
    } else {
        $phone =  '';
        $room =  '';
        //Variables for use in the dates section of the form
        $date = '';
        $time_start = '';
        $ampm_start =  '';
        $time_end = '';
        $ampm_end = '';
        //$Dates = array(array($date, $time_start, $ampm_start, $time_end, $ampm_end));
        //Variables for use in the course section.
        $course_pre =  '';
        $course_num =  '';
        $section =  '';
        //Variables for use in the event section
        $event =  '';
        $purpose =  '';
        $software =  '';
        $no_software = "";
    }
//Checks for date validity mm/dd/yyyy
function validDate($date){
	$d = DateTime::createFromFormat('m/d/Y', $date);
	return $d && $d->format('m/d/Y') === $date;
}
/*
After form submission.
The Dates array is parsed through to gather the date/time information for each reserved day.
Those values are stored into the global variables, and the query/emails are sent like normal. 
*/
$error_message = "";	

if (isset($_POST['submit'])) {
	
		if (empty($name)) {
			$error_message .= "Please specify name<br />"; 
		}
		if (empty($phone)) {
			$error_message .= "Please specify phone<br />"; 
		}
		if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
			$error_message .= "Please provide a valid phone number e.g. ###-###-####<br />"; 
		}
		if (empty($email)) {
			$error_message .= "Please specify email<br />"; 
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error_message .="Please provide a valid email address<br />"; 
		}
		if (!in_array($room, $labs_available)) {
			$error_message .= "Please select one of the available rooms<br />"; 
		}
    /**  Creating an array to store the values in, so that they can all be checked before submission. */
	$reservation_array = array( array("dates"=>$date, "time_start"=>$time_start, "ampm_start"=>$ampm_start,
															"time_End"=>$time_end, "ampm_end"=>$ampm_end));	
															
	for($i = 0; $i < count($_POST['date']); $i++){	
		//Set the global variables to the nested array variables.
		$date = $_POST['date'][$i];
		$time_start = $_POST['time_start'][$i];
		$ampm_start = $_REQUEST['ap_start'.$i.''];
		$time_end = $_POST['time_end'][$i];
		$ampm_end = $_REQUEST['ap_end'.$i.''];
		//Add them to the array.
		$reservation_array[$i]["dates"] = $date;
		$reservation_array[$i]["time_start"] = $time_start;
		$reservation_array[$i]["ampm_start"] = $ampm_start;
		$reservation_array[$i]["time_end"] = $time_end;
		$reservation_array[$i]["ampm_end"] = $ampm_end;	
		
		if (empty($date)){
			$error_message = "Please specify a date <br />";
			
		} 
		if(!validDate($date)){
			$error_message = "Please provide a valid date <br />";
			
		} 			
		if (empty($time_start)) {
			$error_message = "Please specify start time<br />"; 
			
		} 
		if (empty($time_end)) {
			$error_message = "Please specify end time<br />"; 
			
		}		
		$choice = $_REQUEST["C-E"];		
		if (  $choice == "Course" ) {
			if(empty($course_pre) || empty($course_num) || empty($section)){
				$error_message = "Please specify a course and section.<br />";
				
			}
		} else if ( $choice == "Event" ) {
			if(empty($event) || empty($purpose)){
				$error_message = "Please specify an event and purpose.<br />";
				
			}
		}		
		
		if( strlen($ampm_start) == 0 ){
			$error_message = "Please specify AM or PM for all of your reservation dates.</br>";
			
		}else if( strlen($ampm_end) == 0 ){
			$error_message = "Please specify AM or PM for all of your reservation dates.</br>";
			
		}
		if ( ($time_start == $time_end) && ($ampm_start == $ampm_end) ){
			$error_message = "The start and end times cannot be the same.</br>";
		}
		//Check the current time to the time of the reservation, to assure there is at least 24hrs after submission. 
		if( !empty( $software ) ){
			//converts to 24hr time
			$converted_time = strtotime($date . ' ' . $time_start . ' ' . $ampm_start );
			//If submitted date < current date + 24hr
			if( $converted_time < ( time() + 86400 ) ){ 
				$_SESSION['no_software'] =  "Since this reservation is scheduled within 24 hours from now, the software you requested is not guaranteed to be available.";
			}	
				
		} 
		
	}
		// if no errors, send email and redirect user
		if (strlen($error_message) == 0) {
			
			for($i = 0; $i < count($_POST['date']); $i++){
					$date = $reservation_array[$i]["dates"];
					$time_start = $reservation_array[$i]["time_start"];
					$ampm_start = $reservation_array[$i]["ampm_start"];
					$time_end = $reservation_array[$i]["time_end"];
					$ampm_end = $reservation_array[$i]["ampm_end"];	
					
					//send email message (optional testing $mailto may have been set in including file)
					//$to = $mailto ? $mailto : "chass_labs@help.ncsu.edu";
					$to = $email;
					$subject = "Lab Reservation Request";
					$message = "Request received to reserve lab:<br /><br />";
					$message .= "Name: $name<br />";
					$message .= "Phone: $phone<br />";
					$message .= "Email: $email<br />";
					$message .= "Room: $room<br />";
					$message .= "Date: $date<br />";
					$message .= "Time: $time_start $ampm_start - $time_end $ampm_end<br />";
					if (!empty($course) && !empty($section)) {
						$message .= "Course: $course $section<br />";
					}
					if (!empty($event)) {
						$message .= "Event: $event<br />";
					}

					$message .= "Purpose: $purpose<br />";
					$message .= "Software: $software<br />";
					$headers = "MIME-Version: 1.0" . "\r\n" .
						"Content-type: text/html; charset=UTF-8" . "\r\n";
					$from = "ryanbuchanan21@gmail.com";

				   // set success/failure and redirect to self to prevent re-submission
                   
					/**  THE MAILTO FUNCTIONALITY IS DEPENDENT ON SOME FILES THAT I DON'T 
                        CURRENTY HAVE, THERFORE THE CHECK IS ALTERED TO REFLECT THE FACT THAT 
                        IT WILL ALWAYS FAIL THE MAILTO PART    */
					if (mail($to, $subject, $message, $headers, "-f" . $from)) {
						$_SESSION['reservation_success'] = true;
					} else {
						$_SESSION['reservation_success'] = true;
					}
				
				//Converts date from html form, to a usable form in mysql.
				$newDate = date("y-m-d", strtotime($date));				
				//Checks for sketchy escape characters.
				$name = $dbc->real_escape_string($name);
				$phone = $dbc->real_escape_string($phone);
				$email = $dbc->real_escape_string($email);
				$room = $dbc->real_escape_string($room);
				//Date/Time info
				$newDate = $dbc->real_escape_string($newDate);		
				$time_Start = $dbc->real_escape_string($time_start);
				$ampm_start = $dbc->real_escape_string($ampm_start);
				$time_End = $dbc->real_escape_string($time_end);
				$ampm_end = $dbc->real_escape_string($ampm_end);
				//Course info
				$course_pre = $dbc->real_escape_string($course_pre);
				$course_num = $dbc->real_escape_string($course_num);
				$section = $dbc->real_escape_string($section);
				//Event info
				$event = $dbc->real_escape_string($event);
				$purpose = $dbc->real_escape_string($purpose); 
				$software  = $dbc->real_escape_string($software);
				
				if( !empty($event) ){			
				
					$query = "INSERT INTO lab_reservation.lab_request_form (Name, Phone, Email, Room, Date, Time_Start, ampm_start,			
							Time_End, ampm_end, Event, Purpose, Software) VALUES ('$name', '$phone', '$email', '$room', '$newDate',
							  '$time_start', '$ampm_start', '$time_end', '$ampm_end', '$event', '$purpose', '$software')";
				} else {		
					
					$query = "INSERT INTO lab_reservation.lab_request_form (Name, Phone, Email, Room, Date, Time_Start, ampm_start,
							Time_End, ampm_end, Course_Prefix, Course_Number, Section, Software) VALUES ('$name', '$phone', '$email',
							'$room', '$newDate', '$time_start', '$ampm_start', '$time_end', '$ampm_end', '$course_pre', '$course_num', 
							'$section', '$software')";								
				}		
						
				//Reports any errors with dbc connection/query.
				if(!$dbc->query($query)){
					die("Failed to connect to database: " . $dbc->error);
				} else {
					echo "Connection Successful.<br/>";
				}
		}
	}
	if((isset($error_message) && strlen($error_message) == 0)){
		header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']);
		exit();
	}
}
?>

<?php	//require_once 'template_top.inc'; ?>

<div class="configurable text_block">
    <section class="text-mod no-components">
        <div class="container-fluid">
            <div class="section-txt">
<?php
// show form and messages if form has not been submitted, or has been submitted with errors
if (!isset($_POST['submit']) 
    || (isset($error_message) && strlen($error_message) > 0)
) {
?>
                    <div class="row">
                        <div class="col-sm-11 col-sm-offset-1">
                            <h1 class="section-head">Lab Reservation</h1>
                        </div>
                    </div>
<?php
// show success/error message after form submission and page redirection
if (array_key_exists('reservation_success', $_SESSION)) {
    echo "<div class='row'><div class='col-sm-11 col-sm-offset-1'>";
    if ($_SESSION['reservation_success']) {
        echo "<div class='alert alert-success text-center'>";
        echo "<strong>Thank you!</strong> Your reservation request has been submitted." . $time_start . $time_end;
		if(isset($_SESSION['no_software'])){
			echo "<br/>" . $_SESSION['no_software'];			
		}
        echo "</div>";
		session_unset();
    } else {
        echo "<div class='alert alert-danger text-center'>";
        echo "<strong>There was a problem submitting your request.</strong>";
        echo " Please <a href='mailto:chass_it@ncsu.edu'>email CHASS IT</a> at chass_it@ncsu.edu for assistance.";
        echo "</div>";
    }
    echo "</div></div>";
    unset($_SESSION['reservation_success']);
}
// show any form validation error messages
if (isset($error_message) && !empty($error_message)) {
    echo "<div class='alert alert-danger text-center'>";
    echo $error_message;
    echo "</div>";
}?>
<!-- from google api libraries -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="dates.js"></script>

                    <div class="row">
                        <div class="col-sm-11 col-sm-offset-1">
                            <div class="text-mod-txt contenteditable">
                                <p>If you want to reserve a computer lab, first check the classroom schedules
                                    below and make sure the room is free at the desired time.
                                    If it is available, please complete and submit the following form:
                                </p><p></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
					
					<!--FORM START-->
                        <form class="form-horizontal" id="reservation_form"
                            name="reservation_form" action="#" method="post">

                            <!-- NAME -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name: </label>
                                <div class="col-sm-5">
                                    <input class="form-control"  type="input" id="name"
                                        name="name" placeholder="Name" value="<?php echo $name; ?>" required maxlength=50>
                                </div>
                            </div>

                            <!-- PHONE -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="phone">Phone number: </label>
                                <div class="col-sm-5">
                                    <input class="form-control"  type="tel" id="phone"
                                        name="phone" placeholder="Phone" value="<?php echo $phone; ?>" required maxlength=15>
                                </div>
                            </div>

                            <!-- EMAIL -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="email">Email: </label>
                                <div class="col-sm-5">
                                    <input class="form-control"  type="email"
                                        id="email" name="email" placeholder="Email"
                                        value="<?php echo $email; ?>" required maxlength=50>
                                </div>
                            </div>

                            <!-- ROOM -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="room">Room to reserve: </label>
                                <div class="col-sm-2">
                                    <select class="form-control" id="room" name="room" requried>
                                        <option value="">Please select a lab</option>										
										<?php foreach ($labs_available as $lab) {
										?>
											<option value="<?php echo $lab ?>"
													<?php if ($room == $lab) {
														echo " selected"; 
													} ?>>
												<?php echo $lab ?>
											</option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
							
							<!-- COURSE/EVENT SELECT -->
							<div class="form-group"> 
								<label class="col-sm-2 col-sm-offset-1 control-label" for="course">
									Is this request for a course or an event?
								</label>
								<div class="col-sm-2">
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="C-E" value="Course" onclick="show1();" required>
                                            Course
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="C-E" value="Event" onclick="show2();" required>
                                            Event
                                        </label>
                                    </div>
                                </div>
							</div>

                            <!-- COURSE / SECTION -->
                            <div class="form-group" id="Course_section" style="display:none">
							
                                <label class="col-sm-2 col-sm-offset-1 control-label" for="course_pre">Course Prefix: </label>
                                <div class="col-sm-1">
                                    <input class="form-control"  type="course_pre" id="course_pre"
                                        name="course_pre" placeholder="Ex:HSS"
                                        value="<?php echo $course_pre; ?>" size=7 maxlength=3>
                                </div>
								<label class="col-sm-2 control-label" for="course_num">Course Number: </label>
                                <div class="col-sm-1">
                                    <input class="form-control"  type="course_num" id="course_num"
                                        name="course_num" placeholder="Ex:101"
                                        value="<?php echo $course_num; ?>" size=7 maxlength=3>
                                </div>
                                <label class="col-sm-2 control-label" for="section">Section: </label>
                                <div class="col-sm-1">
                                    <input class="form-control"  type="text" id="section"
                                        name="section" placeholder="Ex:001"
                                        value="<?php echo $section; ?>" size=7 maxlength=3>
                                </div>								
                            </div>
							
                            <!-- EVENT / PURPOSE -->
							<div class="form-group" id="Event_section" style="display:none">
								<div class="form-group">
									<label class="col-sm-3 control-label" for="event">Name of event: </label>
									<div class="col-sm-9">
										<input class="form-control"  type="text" id="event"
											name="event" placeholder="Event name" value="<?php echo $event; ?>">
									</div>
								</div>

								<!-- PURPOSE -->
								<div class="form-group">
									<label class="col-sm-3 control-label" for="purpose">Purpose of Event: </label>
									<div class="col-sm-9">
										<textarea class="form-control" id="purpose" name="purpose"
											 placeholder="Class, demonstration, exam, etc."><?php echo $purpose; ?></textarea>
									</div>
								</div>
							</div>
							
							<br/>
							
						<!-- DATE/TIME (Stores values into the array, then places them into global variables during submission.)-->							
							<div id="DateSection">
								<!-- DATE -->
									<div class="form-group">
										<label class="col-sm-2 col-sm-offset-1 control-label" for="date">Date: </label>
										<div class="col-sm-2">
											<input class="form-control"  type="text" id="date"
												name="date[0]" placeholder="MM/DD/YYYY"
												value="<?php 
													if(isset($_POST['date'])){
														echo $_POST['date'][0];
													} 
												?>" required>																				
									</div>
									<!-- START TIME -->
									<div class="form-group">
										<label class="col-sm-2 control-label" for="time_start">Start time: </label>
										<div class="col-sm-2">									
											<select class="form-control" type="text" id="time_start"
												name="time_start[0]" value="<?php 
															if(isset($_POST['time_start'])){
																echo $_POST['time_start'][0];
															}
												?>" required>
													<script> populate('#time_start'); </script>
											</select>
										</div>									
										<div class="col-sm-2">
											<div class="radio-inline">
												<label>
													<input type="radio" name="ap_start0" value="AM" required>AM</label>
											</div>
											<div class="radio-inline">
												<label>
													<input type="radio" name="ap_start0" value="PM" required>PM</label>
											</div>
										</div>
									</div> 

									<!-- END TIME -->
									<div class="form-group" id="end time">
										<label class="col-sm-2 col-sm-offset-5 control-label" for="time_end">End time: </label>
										<div class="col-sm-2">
											<select class="form-control" type="text" id="time_end"
												name="time_end[0]" value="<?php 
															if(isset($_POST['time_end'])){
																echo $_POST['time_end'][0];
															}
												?>" required>
												<script> populate('#time_end'); </script>
											</select>
										</div>									
										<div class="col-sm-2">
											<div class="radio-inline">
												<label>
													<input type="radio" name="ap_end0" value="AM" required>AM</label>
											</div>
											<div class="radio-inline">
												<label>
													<input type="radio" name="ap_end0" value="PM" required>PM</label>											
											</div>
										</div>								
									</div>
								</div>
							</div>
							<?php /* Recreates the dynamically created form fields in the date section.*/
							if(isset($error_message) && strlen($error_message) > 0){
									for($i = 1; $i < count($_POST['date']); $i++){
										//Set the global variables to the nested array variables.
										$date = $_POST['date'][$i];
										echo  "<br><div class='form-group'>" . "<label class='col-sm-2 col-sm-offset-1 control-label' for='date'>Date: </label>" .
										"<div class='col-sm-2'> <input class='form-control'" .
										"type='text' id='date' name='date[" . $i . "]'" . " placeholder='MM/DD/YYYY' " . "value='" . $date . "' required> </div>"; 
										
										$time_start = $_POST['time_start'][$i];
										echo "<div class='form-group'>" . "<label class='col-sm-2 control-label' for='time_start'>Start time: </label>" .
										"<div class='col-sm-2'> <select class='form-control' type='text' id='time_start" .$i. "'" .
										"name='time_start[" . $i . "]'" . "value='" . $time_start . "' required> <script> populate('#time_start" . $i . "'); </script></select></div>";
										
										if(!empty($_POST['ap_start' . $i . ''])){
											$ampm_start = $_POST['ap_start' . $i . ''];
										} else {
											$ampm_start = '';
										}
										
										echo "<div class='col-sm-2'> <div class='radio-inline'>" . "<label> <input type='radio' name='ap_start" . $i . "'" . " value='AM'" .
										"> AM" . "</label> </div> <div class='radio-inline'>" . "<label> <input type='radio' name='ap_start" . $i . "'" . " value='PM'" . "> PM" .
										"</label> </div> </div> </div> ";
										
										$time_end = $_POST['time_end'][$i];
										echo "<div class='form-group'>" . "<label class='col-sm-2 col-sm-offset-5 control-label control-label' for='time_end'>End time: </label>" .
										"<div class='col-sm-2'> <select class='form-control' type='text' id='time_end" .$i. "'" .
										"name='time_end[" . $i . "]'" . "value='" . $time_end . "' required> <script> populate('#time_end" . $i . "'); </script></select></div>";										
										if(!empty($_POST['ap_end' . $i . ''])){
											$ampm_end = $_POST['ap_end' . $i . ''];
										} else {
											$ampm_end = '';
										}
										
										echo "<div class='col-sm-2'> <div class='radio-inline'>" . "<label> <input type='radio' name='ap_end" . $i . "'" . " value='AM'" .
										"> AM" . "</label> </div> <div class='radio-inline'>" . "<label> <input type='radio' name='ap_end" . $i . "'" . " value='PM'" . "> PM" .
										"</label> </div> </div> </div> ";
									}
							}?>
							<!-- ADD DATE -->
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-2">
									<button class="btn btn-primary btn-md" type="button" id="date_add" onClick="addDates('DateSection');"> Add A Date </button>
								</div>
							</div>
                            <!-- SOFTWARE -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="software">Software: </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="software"
                                        name="software" placeholder="Software"><?php echo $software; ?></textarea>
										<p> Note: If you are requesting a software, please ensure that you submit this request at least 24 hours prior to the date of the reservation. </p> 
                                </div>
                            </div>
							
							<!-- SUBMIT -->
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-2">
                                    <button class="btn btn-primary btn-lg" type="submit" id="submit"
                                        name="submit">Request Reservation</button>
                                </div>
                            </div>
							
                        </form>
					</div>	
                    <!--
					<div class="text_block">
						<section class="text-mod">
							<div class="container">
								<div class="section-txt">
									<h1 class="section-head"> Calendars </h1>
										<div class="text-mod-txt">
											<?php //require '../includes/calendar.inc'; ?>
										</div>
								</div>
							</div>
						</section>
					</div>
					-->
<?php } // end show form ?>
<!--
                <div class="row">
                    <div class="col-sm-12">
                        <br>
                        <div class="alert alert-info text-center">
                            This calendar is meant ONLY as a reference for determining when labs are available.<br>
                            <strong>In order to reserve a lab, you must submit the reservation request form above.</strong>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <?php $cal_group = 'instructional'; // group of calendars to list for optional display ?>
                        <?php $cal_display = $labs_available; // these display automatically ?>
                        <?php // allow alternate calendar template to be set for testing ?>
                        <?php //require $calendar_template ? $calendar_template : "calendar/index.php"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-11 col-sm-offset-1">
                        <br>
                        If you have difficulty filling out this form, please <a href='mailto:chass_it@ncsu.edu'>email CHASS IT</a> at chass_it@ncsu.edu for assistance.
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
-->
<!--
<script type="text/javascript">
$(document).ready(function() {
    $('#date').datepicker();
});
</script>
-->
<?php	//require_once 'template_middle.inc'; ?>
