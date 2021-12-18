<?php 

       include 'connection.php';

       $disciplinary = $_POST['disciplinary'];
       $department = $_POST['department'];
       $course = $_POST['course'];

       $sex = $_POST['sex'];
       $fg = $_POST['fg'];
       
       $anyrace = $_POST['anyrace'];
       $white = $_POST['white'];
       $asian = $_POST['asian'];
       $black = $_POST['black'];
       $hispanic = $_POST['hispanic'];
       
       $lecture = $_POST['lecture'];
       $discussion = $_POST['discussion'];
       $group_work = $_POST['groupwork'];       
              	
	if ($disciplinary != "") {
              $name = $disciplinary;
              $type = "disciplinary";
	} 
	if ($department != "") { 
              $name = $department;
              $type = "department";
	}

	if ($course != "") { 
              $name = $course;
              $type = "course";
	}	
       	
	function query ($type, $name) { // checks the database and returns the datapoints for the graph
		
		$male = $female = null;			
		if ($sex == "Any") {
			$male = "M";
			$female = "F";
		}
		if ($sex == "Male") {
			$male = "M";
		}
		if ($sex == "Female") {
			$female = "F";
		}

		if ($anyrace != "") {
			$white = "White";
			$asian = "Asian";
			$black = "Black or African American";
			$hispanic = "Hispanic of any race";
			$hawaiian = "Native Hawaiian or Other Pacif";
			$alaskan = "American Indian or Alaska Nati";
			$multiple = "Two or more races";
			$unknown = "Race and ethnicity unknown";
		} else {
                     $hawaiian = $alaskan = $multiple = $unknown = null;	
                     if ($white == "") {
                            $white = null;
                     }
                     if ($asian == "") {
                            $asian = null;
                     }
                     if ($black == "") {
                            $black = null;
                     }
                     if ($hispanic == "") {
                            $hispanic = null;
                     }
              }

		$first_gen = $non_first_gen = '3'; // variable not used in databse, don't use null because it returns 0. 			
		if ($fg == "Any") {
			$first_gen = "1";
			$non_first_gen = "0";
		}
		
		if ($fg == "First-Gen College Student") {
			$first_gen = "1"; // variables used in database
		} 
		if ($fg == "Non First-Gen College Student") {
			$non_first_gen = "0";
		} 
                            		
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
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W')
				GROUP BY crse_grade_off_eot WITH ROLLUP";		
		} 
              else if ($type == "department") {
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
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W')
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
				WHERE crse_grade_off_eot NOT IN ('-', 'AUD','P', 'I', 'NS', 'W')
				GROUP BY crse_grade_off_eot WITH ROLLUP";		
		}
		
              include 'connection.php';
              
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
              
		$array = array();              
		while($row = mysqli_fetch_assoc($result)) {
			$array[] = $row;
		}
              
              // print_r ($array);
              		
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
		
		$temp = array ();
		for ($i = 0; $i < 12; $i++) { //puts all the datapoints as percentages into the format that is used by Canvas
			$temp[$i] = array("label"=> $grades[$i][1], "y"=> ($array[$grades[$i][0]]['data'])/$total);
		}
		return $temp;
	}
	
	$graph = query ($type, $name); // calls function to retrieve the datapoints
       
       print_r ($graph);

 ?>

<!-- <div id='data' style='height: 400px; width: 100%;'></div><br> -->

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