<?php
	include 'connection.php';
	
	if (isset($_POST['disciplinary']) && $_POST['disciplinary'] != "") { // if no course or department is selected, then sets type to disciplinary if a disciplinary is found
              $name = trim ($_POST["disciplinary"]);
              $type = "disciplinary";
	} 
	if (isset($_POST['department']) && $_POST['department'] != "") { // if no course is selected, then sets type to department if a department is found
              $name = trim ($_POST["department"]);
              $type = "department";
	}

	if (isset($_POST['course']) && $_POST['course'] != "") {  // initially sets type to course
              $name = trim ($_POST["course"]);
              $type = "course";
	}	

	if ($type == "course") { // retrieves course numbers 
		$table = "SELECT DISTINCT CLASS_NBR_COMBO AS course_number FROM style WHERE CLASS_TITLE_COMBO =  '$name' LIMIT 4";
		$result = mysqli_query($conn,$table);
	       $course_number = array ();
	       while($row = mysqli_fetch_assoc($result)) {
	              $course_number[] = $row['course_number'];
	       }
		
		$sql = "SELECT DISTINCT CLASS_TITLE_COMBO, CLASS_NBR_COMBO, total_frames
			FROM style
			WHERE CLASS_TITLE_COMBO = '$name' ";
			
			if ($result = mysqli_query($conn, $sql)) {
				$classes = mysqli_num_rows($result);
				mysqli_free_result($result);
			}
	} else if ($type == "department") {
		$sql = "SELECT DISTINCT class_acad_org_descr_combo, CLASS_NBR_COMBO, total_frames
			FROM style
			WHERE class_acad_org_descr_combo = '$name' ";
			
			if ($result = mysqli_query($conn, $sql)) {
				$classes = mysqli_num_rows($result);
				mysqli_free_result($result);
			}
	} else if ($type == "disciplinary") {
		$sql = "SELECT DISTINCT class_org_cluster_descr_combo, CLASS_NBR_COMBO, total_frames
			FROM style
			WHERE class_org_cluster_descr_combo = '$name' ";
			
			if ($result = mysqli_query($conn, $sql)) {
				$classes = mysqli_num_rows($result);
				mysqli_free_result($result);
			}
	}
	
	function query ($type, $name, $info) { // checks the database and returns the datapoints for the graph
		
		$male = $female = null;
		foreach ($_REQUEST["sex"] as $sex) {
			
			if ($sex == "Any") {
				$male = "M";
				$female = "F";
				break;
			}
			
			if ($sex == "Male") {
				$male = "M";
			}
			if ($sex == "Female") {
				$female = "F";
			}
			
		}
		
		$white = $asian = $black = $hispanic = $hawaiian = $alaskan = $multiple = $unknown = null;
		foreach ($_REQUEST["race"] as $race) {
			
			if ($race == "Any") {
				$white = "White";
				$asian = "Asian";
				$black = "Black or African American";
				$hispanic = "Hispanic of any race";
				$hawaiian = "Native Hawaiian or Other Pacif";
				$alaskan = "American Indian or Alaska Nati";
				$multiple = "Two or more races";
				$unknown = "Race and ethnicity unknown";
				break;
			} 
			
			if ($race == "White") {
				$white = "White";
			} 
			if ($race == "Asian") {
				$asian = "Asian";
			}
			if ($race == "Black or African American") {
				$black = "Black or African American";
			} 
			if ($race == "Hispanic of any race") {
				$hispanic = "Hispanic of any race";
			}
		}

		$first_gen = $non_first_gen = '3'; // variable not used in databse, don't use null because it returns 0. 
		foreach ($_REQUEST["fg"] as $fg) {
			
			if ($fg == "Any") {
				$first_gen = "1";
				$non_first_gen = "0";
				break;
			}
			
			if ($fg == "First-Gen College Student") {
				$first_gen = "1"; // variables used in database
			} 
			if ($fg == "Non First-Gen College Student") {
				$non_first_gen = "0";
			} 
		}
		
		$lecture = $_POST["lecture"];
		$discussion = $_POST["discussion"];
		$group_work = $_POST["group-work"];
		
		include 'connection.php';

		// queries the name into the database and outputs the grades as an array (rows as grades, columns as demographic)
		if ($type == "course") {
			$sql = "SELECT crse_grade_off_eot AS grade,
				COUNT(CASE WHEN class_title_combo = '$name' 
					AND (sex = '$male' OR sex = '$female') 
					AND (FirstGen = '$first_gen' OR FirstGen = '$non_first_gen')
					AND (ipeds_ethnic_descr = '$white' 
					OR ipeds_ethnic_descr = '$black'
					OR ipeds_ethnic_descr = '$asian'
					OR ipeds_ethnic_descr = '$hispanic'
					OR ipeds_ethnic_descr = '$hawaiian'
					OR ipeds_ethnic_descr = '$alaskan'
					OR ipeds_ethnic_descr = '$multiple'
					OR ipeds_ethnic_descr = '$unknown')
					AND lecture_percentage_rounded = '$lecture'
					AND round_percentage_rounded = '$discussion'
					AND groups_percentage_rounded = '$group_work'
				THEN 1 END) AS data
				FROM style
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W' )
				GROUP BY crse_grade_off_eot WITH ROLLUP";		
		} else if ($type == "department") {
			$sql = "SELECT crse_grade_off_eot AS grade,
				COUNT(CASE WHEN class_acad_org_descr_combo = '$name' 
					AND (sex = '$male' OR sex = '$female') 
					AND (FirstGen = '$first_gen' OR FirstGen = '$non_first_gen')
					AND (ipeds_ethnic_descr = '$white' 
					OR ipeds_ethnic_descr = '$black'
					OR ipeds_ethnic_descr = '$asian'
					OR ipeds_ethnic_descr = '$hispanic'
					OR ipeds_ethnic_descr = '$hawaiian'
					OR ipeds_ethnic_descr = '$alaskan'
					OR ipeds_ethnic_descr = '$multiple'
					OR ipeds_ethnic_descr = '$unknown')
					AND lecture_percentage_rounded = '$lecture'
					AND round_percentage_rounded = '$discussion'
					AND groups_percentage_rounded = '$group_work'
				THEN 1 END) AS data
				FROM style
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W' )
				GROUP BY crse_grade_off_eot WITH ROLLUP";		
		} else if ($type == "disciplinary") {
			$sql = "SELECT crse_grade_off_eot AS grade,
				COUNT(CASE WHEN class_org_cluster_descr_combo = '$name' 
					AND (sex = '$male' OR sex = '$female') 
					AND (FirstGen = '$first_gen' OR FirstGen = '$non_first_gen')
					AND (ipeds_ethnic_descr = '$white' 
					OR ipeds_ethnic_descr = '$black'
					OR ipeds_ethnic_descr = '$asian'
					OR ipeds_ethnic_descr = '$hispanic'
					OR ipeds_ethnic_descr = '$hawaiian'
					OR ipeds_ethnic_descr = '$alaskan'
					OR ipeds_ethnic_descr = '$multiple'
					OR ipeds_ethnic_descr = '$unknown')
					AND lecture_percentage_rounded = '$lecture'
					AND round_percentage_rounded = '$discussion'
					AND groups_percentage_rounded = '$group_work'
				THEN 1 END) AS data
				FROM style
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W' )
				GROUP BY crse_grade_off_eot WITH ROLLUP";		
		}
		
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$array = array();
		while($row = mysqli_fetch_assoc($result)) {
			$array[] = $row;
		}
				
		if ($info == "size") {
			return $array[12]['data'];
		}
		
		$grades = array( // grades in the order that is sent into the table
			array (0, 'A'), 
			array (1, 'A-'), 
			array (2, 'B+'), 
			array (3, 'B'), 
			array (4, 'B-'), 
			array (5, 'C+'), 
			array (6, 'C'), 
			array (7, 'C-'), 
			array (8, 'D+'), 
			array (9, 'D'), 
			array (10, 'D-'), 
			array (11, 'F')
		);
		
		if ($array[12]['data'] != 0) {
			$total = $array[12]['data'];
		} else { // ensures that the denominator cannot be zero
			$total = 1;
		}
		
		if ($info == "data") {
			$temp = array ();
			for ($i = 0; $i < 12; $i++) { //puts all the datapoints as percentages into the format that is used by Canvas
				$temp[$i] = array("label"=> $grades[$i][1], "y"=> ($array[$grades[$i][0]]['data'])/$total);
			}
			return $temp;
		}
		
	}
	
	$graph = query ($type, $name, "data"); // calls function to retrieve the datapoints
		
	$sample_size = query ($type, $name, "size"); //  calls function in order to retrieve the sample size 
	
?>

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
		
		<title>TailorEd - Results</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="icon" type = "image/ico" href = "../images/favicon.ico">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>		
		<script>

			window.onload = function () {
				
				var data = new CanvasJS.Chart("data", {
					animationEnabled: true,
					theme: "light2",
					// title:{
					// 	text: "Chart"
					// },
					axisX:{
						title:"Letter Grade",
						includeZero: true
					},
					axisY:{
						title:"% of students",
						includeZero: true
					},
					data: [{
						type: "column",
						yValueFormatString: "#0.##%",
						showInLegend: false,
						visible: true,     
						dataPoints: <?php echo json_encode($graph, JSON_NUMERIC_CHECK); ?>
					}]
				});
				
				data.render();
			}
		</script>
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
						<li><a href="/contact">Contact</a></li>
					</ul>
				</nav>
				
		</header>
		
		<div id="modify-wrapper" class="wrapper">
			<!-- Section -->
				<section class="main style1">
					<header class="medium">
						<h1>Results</h1>
					</header>

					<header id="modify" class="medium">
						<form action = "/pages/results.php" method="post">
							<div class="main style2">
								<div id="modify">
									<div class="modify style" id="style"> 
										<h4>Class Style</h4>
										
										<h5> Lecture </h5>
										<div class="range-slider">
											<?php echo '<input id="lecture" name="lecture" class="range-slider__range" type="range" value= "'. $_POST["lecture"] .'"min="0" max="100" step="25"></input>'; ?>
											<span class="range-slider__value">0</span>
										</div> <br>
										
										<h5> Group Work </h5>
										<div class="range-slider">
											<?php echo '<input id="group-work" name="group-work" class="range-slider__range" type="range" value= "'. $_POST["group-work"] .'"min="0" max="100" step="25"></input>';?>											
											<span class="range-slider__value">0</span>
										</div> <br>
										
									</div>
									<div class="modify subject" id="subject"> 
										<h4>Class Subject</h4>
											<section>
												<h5> Disciplinary </h5>
												<div id = "disp">
												<select name="disciplinary" id="disciplinary" class="ui selection dropdown disciplinary" required>
													<option></option>
													<?php 
														$sql_disciplinary = "SELECT DISTINCT CLASS_ORG_CLUSTER_DESCR_COMBO AS disciplinary FROM style WHERE CLASS_ORG_CLUSTER_DESCR_COMBO != 'Other Arts and Sciences' ";
														$disciplinary_data = mysqli_query($conn,$sql_disciplinary);
														while($row = mysqli_fetch_assoc($disciplinary_data) ){
															$disciplinary = $row['disciplinary'];
															if ($disciplinary == trim($_POST['disciplinary'])) {
																echo "<option value='".$disciplinary."' selected>" .$disciplinary. "</option>";
															} else {
																echo "<option value='".$disciplinary."' >" .$disciplinary. "</option>";
															}
														}
													?>
												</select>
												</div>
											</section> </br>
		
									</div>
									<div class="modify sample" id="sample"> 
										<h4>Student Sample</h4>
										<section>
											<h5 class = "required">Sex</h5>
											<input type="radio" id="any-sex" class="any category4" name="sex[]" value="Any" <?php foreach ($_REQUEST["sex"] as $sex) { if ($sex == "Any") { echo 'checked'; break; } } ?> > <label for="any-sex"> Any</label><br>
											<input type="radio" id="male" class="sex category4" name="sex[]" value="Male" <?php foreach ($_REQUEST["sex"] as $sex) { if ($sex == "Male") { echo 'checked'; break; } } ?>> <label for="male"> Male </label> <br>	
											<input type="radio" id="female" class="sex category4" name="sex[]" value="Female" <?php foreach ($_REQUEST["sex"] as $sex) { if ($sex == "Female") { echo 'checked'; break; } } ?> > <label for="female"> Female </label> <br>	
										</section>
										<section>
											<h5 class = "required">Race</h5>
											<input type="checkbox" id="any-race" class="any category1" name="race[]" value="Any" <?php foreach ($_REQUEST["race"] as $race) { if ($race == "Any") { echo 'checked'; break; } } ?> > <label for="any-race"> Any</label><br>
											<input type="checkbox" id="race-option-2" class="race category1" name="race[]" value="White" <?php foreach ($_REQUEST["race"] as $race) { if ($race == "White") { echo 'checked'; break; } } ?> > <label for="race-option-2"> White</label><br>
											<input type="checkbox" id="race-option-3" class="race category1" name="race[]]" value="Asian" <?php foreach ($_REQUEST["race"] as $race) { if ($race == "Asian") { echo 'checked'; break; } } ?> > <label for="race-option-3"> Asian</label><br>
											<input type="checkbox" id="race-option-4" class="race category1" name="race[]]" value="Black or African American" <?php foreach ($_REQUEST["race"] as $race) { if ($race == "Black or African American") { echo 'checked'; break; } } ?> > <label for="race-option-4"> Black or African American</label><br>
											<input type="checkbox" id="race-option-5" class="race category1" name="race[]]" value="Hispanic of any race" <?php foreach ($_REQUEST["race"] as $race) { if ($race == "Hispanic of any race") { echo 'checked'; break; } } ?> > <label for="race-option-5"> Hispanic of any race</label><br>
										</section>
										<section> 
											<h5 class = "required">First-Generation</h5>
											<input type="radio" id="any-fg" class="any category3" name="fg[]" value="Any" <?php foreach ($_REQUEST["fg"] as $fg) { if ($fg == "Any") { echo 'checked'; break; } } ?> > <label for="any-fg"> Any</label><br>
											<input type="radio" id="first-gen" class="fg category3" name="fg[]" value="First-Gen College Student" <?php foreach ($_REQUEST["fg"] as $fg) { if ($fg == "First-Gen College Student") { echo 'checked'; break; } } ?> > <label for="first-gen"> First-Gen College Students </label> <br>	
											<input type="radio" id="non-first-gen" class="fg category3" name="fg[]" value="Non First-Gen College Student" <?php foreach ($_REQUEST["fg"] as $fg) { if ($fg == "Non First-Gen College Student") { echo 'checked'; break; } } ?> > <label for="non-first-gen"> Non First-Gen College Students </label> <br>	
										</section>
									</div>
									<div id = "change" class="main style2">
										<button id="modify-submit" class = "buttom primary" type="submit" name="submit">Submit</button>
									</div>
								</div>

							</div>
						</form>
					</header>
					<div class="inner" >
						<?php 	
							if ($sample_size == 0) {
								echo "<p class = 'description'> Based on your search criteria, we found <b> 0 students </b> in your selected ". $type. " who fit your student sample. Please change the student sample to yield better results.";								
							} else {
								echo "<p class='description'> Based on your search criteria, we found  <b>";
									if ($sample_size == 1) {
										echo "1 student";
									} else {
										echo $sample_size. "  students";
									}
								echo "</b> from <b>";
									if ($classes == 1) {
										echo "1 class";
									} else {
										echo $classes. "  classes";
									}
								echo "</b> in your selected ". $type ." who fit your student sample. In the graph below, you can find the grade distribution for these students.  </p> </br>";
								echo "<div id='data' style='height: 400px; width: 100%;'></div><br>";
							}
														
							echo "<br> <br> <p class = 'description'> <b> Class styles available for this ". $type .". </b> </p>";
							
							if ($type == "course") {
								$style = "SELECT DISTINCT total_frames, lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded FROM style WHERE CLASS_TITLE_COMBO = '$name' ORDER BY lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded";
							} else if ($type == "department") {
								$style = "SELECT DISTINCT total_frames, lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded FROM style WHERE class_acad_org_descr_combo = '$name' ORDER BY lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded";
							} else if ($type == "disciplinary") {
								$style = "SELECT DISTINCT total_frames, lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded FROM style WHERE CLASS_ORG_CLUSTER_DESCR_COMBO = '$name' ORDER BY lecture_percentage_rounded, round_percentage_rounded, groups_percentage_rounded";
							}
							
							$style_data = mysqli_query($conn, $style);
							$temp_lecture = $temp_discussion = null;
							while($row = mysqli_fetch_assoc($style_data) ){
								$lecture = $row['lecture_percentage_rounded'];
								$discussion = $row['round_percentage_rounded'];
								$group_work = $row['groups_percentage_rounded'];
								
								if (($lecture != $temp_lecture) && ($discussion != $temp_discussion)) {
									echo "<p class = 'description'>". $lecture . "% lecture,   ". $discussion . "% discussion,   ". $group_work . "% group work </p>";		
								}
															
								$temp_lecture = $lecture;
								$temp_discussion = $discussion;
							}

						 ?>
					</div>
				</section>
				
		</div>

		<!-- Scripts -->
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<script src="../assets/js/jquery.min.js"></script>
		<script src="../assets/js/jquery.dropotron.min.js"></script>
		<script src="../assets/js/browser.min.js"></script>
		<script src="../assets/js/breakpoints.min.js"></script>
		<script src="../assets/js/main.js"></script>
		<script src="../assets/js/range.js"></script>

	</body>
</html>