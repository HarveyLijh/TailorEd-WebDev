<?php

       $servername = "localhost";
       $username = "tailhaea_roland";
       $password = "roland1029";
       $dbname = "tailhaea_1";

       // Create connection
       $conn = new mysqli($servername, $username, $password, $dbname);
       // Check connection
       if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
       }

?>