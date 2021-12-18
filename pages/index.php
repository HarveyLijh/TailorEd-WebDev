<!DOCTYPE HTML>

<html lang="en">
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
		
		<title>TailorEd</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name=”description” content= "As classroom infrastructure remains an understudied variable in the active learning equation, this project measures the return on infrastructural investment in student learning.">
		<link rel="canonical" href="https://tailored.education" />

		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="icon" type = "image/ico" href = "../images/favicon.ico">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>		

		<?php include 'connection.php'; ?>
				
	</head>
	<body class="is-preload">

		<!-- Header -->
			<header id="header">
					<div class="logo">
						<a href="/"><strong>Tailored Education </strong> </a>
					</div>
					<nav id="nav">
						<ul>
							<li class="current"><a href="/">Home</a></li>
							<li><a href="/about">About</a></li>
							<li><a href="/team">Team</a></li>
							<li><a href="/contact">Contact</a></li>
						</ul>
					</nav>
			</header>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Banner -->
					<section class="banner">
						<div class="image" data-position="right">
							<img src="../../../images/banner.jpg" alt="" />
						</div>
						<div class="content">
							<h1>Tailored Education</h1>
							<ul class="actions special">
								<li><a href="#query" class="button down">Get Started</a></li>
							</ul>
						</div>
					</section>

				<!-- Section -->
					<section id ="query"class="main style1">
						<header class="small">
							<h2>Make a Query</h2>
						</header>
						
						<form id="makequery" action = "/pages/results.php" method="post">

						<div>
							<h3 id="class-style">Class Style</h3>
							<p>Adjust the sliders to the percentages spent on each type of activity that you are looking for. <br/>
								Note: All categories must add up to 100%. </p>								
								
								<h4 class = "demo"> Lecture </h4>
								<div class="range-slider">
									<input id="lecture" name="lecture" class="range-slider__range" type="range" value="0" min="0" max="100" step="25"></input>
									<span class="range-slider__value">0</span>
								</div> <br>
								
								<h4 class = "demo"> Group Work </h4>
								<div class="range-slider">
									<input id="group-work" name="group-work" class="range-slider__range" type="range" value="0" min="0" max="100" step="25"></input>
									<span class="range-slider__value">0</span>
								</div> <br>
								
								<p id="class-style-error" class="error-message"> * Please make sure that the sum of all categories equals 100%. </p> 
													
							<hr />
							
							<div class="inner">
							
								<h3 id="class-subject">Class Subject</h3>
								<p>Use the menus to make a query on an entire disciplinary or to narrow down the focus to a specific department or class.</p>

								<div class = "features">
									<section>
										<h4 class = "required"> Disciplinary </h4>	
											<select id="disciplinary" name="disciplinary">
												<option></option>
												<?php 
													$sql_disciplinary = "SELECT DISTINCT CLASS_ORG_CLUSTER_DESCR_COMBO AS disciplinary FROM combined WHERE CLASS_ORG_CLUSTER_DESCR_COMBO != 'Other Arts and Sciences' ";
													$disciplinary_data = mysqli_query($conn,$sql_disciplinary);
													while($row = mysqli_fetch_assoc($disciplinary_data) ){
														$name = $row['disciplinary'];
														echo "<option value='".$name."' >" .$name. "</option>";
													}
												?>
											</select>
									</section>
								</div>
							</div>
							
							<p id="class-subject-error" class="error-message"> * Please make sure that you have selected something from one of the dropdown menus. </p> 
					
						<hr />
					
						<h3 id="student-sample">Student Sample</h3>
						<p>Check off boxes for demographics of students you want to receive data about. If 'any' is chosen, that category will not be narrowed down.</p>
					
						</div>

						<div class="features sample">
							<section>
								<h3 class = "required">Sex</h3>
								<input type="radio" id="any-sex" class="any category1" name="sex[]" value="Any"> <label for="any-sex"> Any</label><br>
								<input type="radio" id="male" class="sex category1" name="sex[]" value="Male"> <label for="male"> Male </label> <br>	
								<input type="radio" id="female" class="sex category1" name="sex[]" value="Female"> <label for="female"> Female </label> <br>	
								<p id="sex-error" class="error-message"> * At least one checkbox must be selected. </p>
							</section>
							<section>
								<h3 class = "races required">Race</h3>
								<input type="checkbox" id="any-race" class="any category2" name="race[]" value="Any"> <label for="any-race"> Any</label><br>
								<input type="checkbox" id="race-option-2" class="race category2" name="race[]" value="White"> <label for="race-option-2"> White</label><br>
								<input type="checkbox" id="race-option-3" class="race category2" name="race[]]" value="Asian"> <label for="race-option-3"> Asian</label><br>
								<input type="checkbox" id="race-option-4" class="race category2" name="race[]]" value="Black or African American"> <label for="race-option-4"> Black or African American</label><br>
								<input type="checkbox" id="race-option-5" class="race category2" name="race[]]" value="Hispanic of any race"> <label for="race-option-5"> Hispanic of any race</label><br>
								<p id="race-error" class="error-message"> * At least one checkbox must be selected. </p> 
							</section>
							<section> 
								<h3 class = "required">First-Generation</h3>
								<input type="radio" id="any-fg" class="any category3" name="fg[]" value="Any"> <label for="any-fg"> Any</label><br>
								<input type="radio" id="first-gen" class="fg category3" name="fg[]" value="First-Gen College Student"> <label for="first-gen"> First-Gen College Students </label> <br>	
								<input type="radio" id="non-first-gen" class="fg category3" name="fg[]" value="Non First-Gen College Student"> <label for="non-first-gen"> Non First-Gen College Students </label> <br>	
								<p id="fg-error" class="error-message"> * At least one checkbox must be selected. </p> 						
							</section>
						</div>
						<footer>
							<ul class="actions special">
								<li><input id="submit" type="submit" name="submit" class="button large"/></li>
							</ul>
						</footer>
						</form>
					</div>
				</section>
			</div>
					
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/main.js"></script>
			<script src="../assets/js/range.js"></script>
	</body>
</html>