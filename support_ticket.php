<?php
/* 
 * Laura Davis
 * 20 December 2018
 */
		
/* 	$createTable = "CREATE TABLE IF NOT EXISTS support_tickets(
				PRIMARY KEY TicketNo INT(10), 
				Email VARCHAR(64),
				Name VARCHAR(64),
				Phone VARCHAR(24),
				Location VARCHAR (32),
				AvailDate DATE,
				AvailTime VARCHAR(64),
				Description TEXT(1000)			
				)";
				
	$setPriv = "GRANT ALL ON support_tickets.* FOR 'lbd'@'localhost'"; */
	
	//Database connection string variables
	$host = "localhost";
	$db = "support_tickets";
	$user = "lbd";
	$pwd = "password";
	
	//Create connection
	$conn = new mysqli($host, $user, $pwd, $db);
	if($conn->connect_error) die($conn->connect_error);
	
	//Query the database (finds the table to fetch data from)
	$selectquery = "SELECT * FROM support_tickets";
	$result = $conn->query($selectquery);
	if(!$result) die($conn->error);
		
		
	//Declare variables
	$msg = '';
	$msgClass = '';
	
	//Sanitizes user input to avoid errors or security issues
	function sanitizeInput($input){
		//Clears bad input characters for input santitization
		$output = htmlentities(strip_tags(stripslashes($input)));
		return $output;
	}
	
	//Sanitizes the input and ensures that invalid
	//and dangerous characters are excluded.
	//Utilizes regex and filter methods for input validation.
	//HTML input parsed as: _POST["html_field_value"];
	if(filter_has_var(INPUT_POST, 'submit')){
		//Sanitizes form inputs
		$name = sanitizeInput($_POST['name']);
		if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
			$msg = "Invalid name format."; 
			$msgClass = 'alert-danger';
		}
		$email = sanitizeInput($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg = "Invalid email format"; 
			$msgClass = 'alert-danger';
		}
		
		$phone = sanitizeInput($_POST['phone']);

		$location = sanitizeInput($_POST['location']);
		
		//The dates and times are imploded to post individual entries from the array.
		//I have also concatenated a ; with space to separate the values in
		//the database.
		$availDate = implode('; ', ($_POST['availDate']));
		$availDate = (string) $availDate;
		
		$availTime = implode('; ', ($_POST['availTime']));
		$availTime = (string) $availTime;
		
		//I'm using regex replace to strip curly single and double quotes from
		//input pasted into the description textbox from Word.
		$desc = sanitizeInput($_POST['desc']);
		$repl = preg_replace("/&lsquo;/", "'", $desc);
		$repl = preg_replace("/&rsquo;/", "'", $repl);
		$repl = preg_replace("/&ldquo;/", '"', $repl);
		$repl = preg_replace("/&rdquo;/", '"', $repl);
		$repl = preg_replace("/&quot;/", '"', $repl);
		$desc = $repl;
		
		
		//After checks and santitization, send email with user-entered data
		$toEmail = 'davis916@live.missouristate.edu';
		$subject = 'Support request from' .$name;
		$body = '<h2>Support request</h2>
				<h4>Name</h4><p>'.$name.'</p>
				<h4>Email</h4><p>'.$email.'</p>
				<h4>Location of issue</h4><p>'.$location.'</p>
				<h4>Date</h4><p>'.$availDate.'</p>
				<h4>Time</h4><p>'.$availTime.'</p>
				<h4>Issue description</h4><p>'.$desc.'</p>';
				
		$headers = "MIME-Version: 1.0" ."\r\n";
		$headers .= "Content-Type:text/html;charset=UTF-8" ."\r\n";
		$headers .= "From: " .$name. "<".$email. ">" . "\r\n";
		
		if(@mail($toEmail, $subject, $body, $headers)){
			$msg = "Your ticket has been submitted.";
			$msgClass = 'alert-success';
			return $msg;
		}
		else{
			$msg = "Your ticket was not submitted. Please <a href='about.html#contact'>contact us<a> for support.";
			$msgClass = 'alert-danger';
		} 
			
		//Creates a ticket number by incrementing the number of database entries
		$numEntries = $result->num_rows;
		$ticketNo = $numEntries+1;
		
		//Takes more secure user input for INSERT to defend against SQL injection
		$stmt = $conn->prepare('INSERT INTO support_tickets VALUES(?,?,?,?,?,?,?,?)');
		$stmt->bind_param('isssssss', $ticketNo, $name, $email, $phone, $location, $availDate, $availTime, $desc);
		$stmt->execute();
		
	$result->close();
	$conn->close();
	}
?>

<!DOCTYPE html>
<!-- Laura Davis ENG573 -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>File a support ticket</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!-- My stylesheet -->
  <link href="stylesheet.css" rel="stylesheet" type="text/css">
<style>
  @import url('https://fonts.googleapis.com/css?family=Libre+Franklin|Roboto:400,400i,700');
</style> 
</head>

<body>
<!-- jQuery, popper.js, and Bootstrap JS --> 
<div class="iefix">    
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</div>
  
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#5E0009;">
  <a class="navbar-brand" href="https://missouristate.edu/"><img src="images/msu_logo.png" alt="Misssouri State logo"/></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="documentation.html">Documentation</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="http://localhost/ITSupport_website/support_ticket.php">Support tickets</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/ITSupport_website/ticket_dashboard.php">Support ticket dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.html">About us</a>
      </li>
    </ul>
      <!-- SEARCH BAR
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
        -->
  </div>
</nav>
  
  <header class="coed-header">
    <p>College of Education - Technical Support</p>
  </header>
  
  <div class="main-container">
  <div class="main">
	  
      <div class="text-center">
        <h1 class="reg-h1" id="file-ticket">File a support ticket</h1>
		</div>
        <p class="lead">The following form allows you to file a support ticket
			that will help us provide the most efficient technical support.</p>
			
			<p class="warning">Warning: Do not enter sensitive information 
			into this form, inlcuding login credentials like usernames and passwords.</p>
			  	 
		<?php if($msg != ''): ?>
		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
		<?php endif; ?>
	  
	  <br>
	  <h2 id='about-you'>About you</h2>
          <form action='<?php echo $_SERVER['PHP_SELF'];?>' method='POST' accept-charset="UTF-8" class='needs-validation' novalidate>
		  

			<div class='mb-3'>
                <label for='name'>First and last name</label>
                <input type='text' class='form-control' id='name' name='name' placeholder='Example: Boomer Bear' value='' aria-required='true' required >
                <div class='invalid-feedback'>
                  Please enter your first and last name.
                </div>
			</div>

            <div class='mb-3'>
              <label for='email'>Email</label>
              <input type='email' class='form-control' id='email' name='email' placeholder='Example: boomerbear@missouristate.edu' aria-label='Example: boomerbear@missouristate.edu' aria-required='true' required>
              <div class='invalid-feedback'>
                Please enter a valid email address.
              </div>
            </div>
			
			<div class='mb-3'>
              <label for='phone'>Phone (not required)</label>
              <input type='phone' class='form-control' id='phone' name='phone' placeholder='Example: (417) 836-5000' aria-label='Example: (417) 836-5000' aria-required='false'>
              <div class='invalid-feedback'>
                Please enter a valid phone number.
              </div>
            </div>

            <div class='mb-3'>
              <label for='location'>Location of issue</label>
              <input type='text' class='form-control' id='location' name='location' placeholder='Example: Hill 209' aria-label='Example: Hill 209' aria-required='true' required>
              <div class='invalid-feedback'>
                Please enter the location of the issue, such as Hill 209 or PCOB Computer Lab.
              </div>
            </div>
			
		<div class='datetime' id='datetime'>
		<h2 id='availability'>Your availability</h2>
			<div class='row'>
			<div class='col-md-6 mb-3'>
              <label for='availDate'>Date</label>
              <input name='availDate[]' type='date' class='form-control' id='availDate' aria-required='true' required>
              <div class='invalid-feedback'>
                Please enter a date when you will be available.
              </div>
            </div>
			
			<div class='col-md-6 mb-3'>
              <label for='availTime'>Time</label>
              <input name='availTime[]' type='text' class='form-control' id='availTime' placeholder='Example: 8:00 - 12:00, 12:30 - 4:00' aria-label='Example: 8:00 - 12:00, 12:30 - 4:00' aria-required='true' required>
              <div class='invalid-feedback'>
                Please enter a time when you will be available.
              </div>
            </div>
			</div>
			</div>
			
				<div class='mb-3'>
				<button type='button' class='btn btn-success' id='addMore' style='width:220px;'>Add date and time</button>
				</div>
				
				<div class='mb-3'>
				<button type='button' class='btn btn-danger' id='remove' style='width:220px;'>Remove date and time</button>
				</div>
			<br>
			
		<h2 id='desc-issue'>Describe the issue</h2>
			<textarea class='form-control' id='desc' name='desc' style='height:300px;' 
					placeholder='Please include important information such as error messages in your description.' 
					aria-label='Please include important information such as error messages in your description.'
					aria-required='true' required maxlength="2000"></textarea>
			<div class='invalid-feedback'>
               Please enter a description of your issue.
			</div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block cust" type="submit" name="submit">Submit your support ticket</button>
          </form>
		  
		  <br>
		  
		  <?php if($msg != ''): ?>
		  <div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
		  <?php endif; ?>

	</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <script>
	//Allows the user to add more dates and times to the form.
		$(document).ready(function(){
			var i = $('.newfield').length;
			$('#addMore').click(function(e){
				e.preventDefault();
				$('#datetime').append('<div class="newfield"><div class="row">' +
			'<div class="col-md-6 mb-3"><label for="availDate">Date</label>' +
             '<input name="availDate[]" type="date" class="form-control" id="availDate" aria-required="true" required>' +
              '<div class="invalid-feedback">Please enter a date when you will be available.</div></div>' +
			'<div class="col-md-6 mb-3"><label for="time">Time</label>' +
              '<input name="availTime[]" type="text" class="form-control" id="availTime" placeholder="Example: 8:00 - 12:00, 12:30 - 4:00" aria-label="Example: 8:00 - 12:00, 12:30 - 4:00" aria-required="true" required>' +
              '<div class="invalid-feedback">Please enter a time when you will be available.</div></div>' +
			'</div></div></div>');
			i++;
			});
			
			//Allows the user to remove dates and times from the form.
			$(document).on('click', '#remove', function(){
			$('div .newfield:last').remove();
			});
		});
		

		
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>