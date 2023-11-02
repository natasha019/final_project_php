<?php

require('Model/conection.php');

$con = new Conection();

$students = $con->getStudent();



?>