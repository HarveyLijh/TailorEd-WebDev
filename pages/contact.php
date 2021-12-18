<!DOCTYPE HTML>

<html>
	<head>
		<!-- Google Analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-174251519-2', 'auto');
			ga('require', 'displayfeatures');
			ga('send', 'pageview');
		</script>
		<!-- End Google Analytics -->
		
		<title>TailorEd - Contact</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js' async defer > </script>
		<link rel="icon" type = "image/ico" href = "../images/favicon.ico">
	</head>
	<body class="is-preload">

		<!-- Header -->
			<header id="header">

				<!-- Logo -->
					<div class="logo">
						<a href="/"><strong>Tailored Education </strong> </a>
					</div>
					
				<!-- Nav -->
				<nav id="nav">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="/about">About</a></li>
						<li><a href="/team">Team</a></li>
						<li class="current"><a href="/contact">Contact</a></li>
					</ul>
				</nav>
			</header>
			

		<!-- Wrapper -->
			<div id="wrapper">
				
				<!-- Section -->
				<section class="main style2">
					<header class="small">
						<h2>Get in touch</h2>
						<p>If you would like to receive more information or need to contact us, please fill out the form below and we will try to get back to you as soon as possible.</p>
					</header>
					<div class="inner special medium">
						<form id="contact" action="/contact" method="post">
							<div class="fields">
								<div class="field half">
									<input name="name" id="name" placeholder="Name *" type="text" required/>
								</div>
								<div class="field half">
									<input name="email" id="email" placeholder="Email *" type="email" required/>
								</div>
								<div class="field">
									<input name="subject" id="subject" placeholder="Subject *" type="text" required/>
								</div>
								<div class="field">
									<textarea name="message" id="message" rows="8" placeholder="Message *" minlength="10" required></textarea>
								</div>
							</div>
							<ul class="actions special">
								<li> <div class="g-recaptcha" data-sitekey="6Ld6E8AZAAAAAM6EhHkAzXGqsj4s5kVMJTCxjlfM" data-badge="inline" data-callback="setResponse"></div> </li>
							</ul>
							<ul class="actions special">
								<li><input value="Send Message" class="button next" type="submit" /></li>
							</ul>
						</form>
					</div>
				</section>
			</div>

			
			<div id="reCaptcha" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">reCaptcha Verification Required </h4></br>
							<p>Please check the reCaptcha before submitting the form.</p> </br>
							<button type="button" class="btn btn-default primary button" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

			<div id="contact-success" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Message Sent</h4></br>
							<p>Thank you for reaching out to us and we will try our best to get back to you shortly.</p>
							<button type="button" class="btn btn-default primary button" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
			<div id="contact-error" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Error</h4></br>
							<p>Sorry, something went wrong. Please email <b> info@tailored.education </b> to contact us directly.</p>
							<button type="button" class="btn btn-default primary button" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
		<!-- Scripts -->
			<script>
				$("#contact").submit(function(event) {

				   var recaptcha = $("#g-recaptcha-response").val();
				   if (recaptcha === "") {
				      event.preventDefault();
				      $("#reCaptcha").modal("toggle");
				   }
				});
				
				var onloadCallback = function() {
					grecaptcha.execute();
				};

				function setResponse(response) { 
					document.getElementById('captcha-response').value = response; 
				}
			</script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>

<?php
       use PHPMailer\PHPMailer\PHPMailer;
       use PHPMailer\PHPMailer\SMTP;

	require __DIR__.'/../assets/vendor/autoload.php';

       $mail = new PHPMailer;
	
       $mail->isSMTP();
	
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
	
       // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	
       $mail->Host = $_ENV['MAIL_HOST'];                            //Set the hostname of the mail server
       $mail->Port = 465;                                         //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->SMTPSecure = "ssl";				        //Set the encryption mechanism to use - STARTTLS or SMTPS
       $mail->SMTPAuth = true;                                    //Whether to use SMTP authentication
       $mail->Username = $_ENV['MAIL_USERNAME'];                     //Username to use for SMTP authentication - use full email address for gmail
       $mail->Password = $_ENV['MAIL_PASSWORD'];                           //Password to use for SMTP authentication
	
	if (isset($_POST['name'])) {
		$name = trim($_POST['name']);
		$email = $_POST['email'];
		$message = $_POST['message'];
		$subject = $_POST['subject'];
	} else {
		$name = $email = $message = $subject = null;
	}
	
       $mail->setFrom('info@tailored.education', $name);                   	   //Set who the message is to be sent from, DOESN'T work with gmail 
       $mail->addReplyTo($email, $name);                                 	   //Set an alternative reply-to address
       $mail->addAddress('julia@tailored.education', 'Julia Voss');    	   //Set who the message is to be sent to
	$mail->addAddress('navid@tailored.education', 'Navid Shaghaghi');      
	$mail->addBCC('info@tailored.education', 'Tailored Education');

	$mail->Subject = '[Tailored Education]: '. $subject;                     //Set the subject line	
       $mail->Body    = $message . '<br><br> ---- <br>' . $name . '<br>' . $email;             
       $mail->AltBody = $message;                				   //Replace the plain text body with one created manually
	
	if (isset($_POST['name'])) {
		$secret = $_ENV['RECAPTCHA_SECRET_KEY'];
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success) {
			if ($_POST["name"] != "") {
			//send the message, check for errors
				if (!$mail->send()) { ?>
					<script language="javascript" type="text/javascript">
						$("#contact-error").modal("toggle");
					</script> <?php
				} else { ?>
					<script language="javascript" type="text/javascript">
						$("#contact-success").modal("toggle");
					</script>
				<?php
				}
			}	
		} else { ?>
			<script language="javascript" type="text/javascript">
				$("#contact-error").modal("toggle");
			</script> <?php
		}
	}
?>