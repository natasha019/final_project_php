<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: ../index.php');
}


$limite = 5;

if (!isset($_GET['desde'])) {
    $desde = 0;
} else {
    $desde = $_GET['desde'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="">
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/trash-empty.css' rel='stylesheet'>
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/add-r.css' rel='stylesheet'>

    <title>Pre-Matricula</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="../assets/css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

    <!-- start navbar -->
    <?php include("layouts/header.php"); ?>


    <!-- courses section -->
    <section class="service_section mt-3">
        <div class="service_container">
            <div class="container ">
                <div class="heading_container heading_center">
                    <h2 class="mb-5">
                        Estudiantes <span>Matriculados</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");

                    // Default query
                    $query = "SELECT distinct student_id, course_id, section_id FROM enrollment where status = 1 ORDER BY student_id";

                    if (isset($_GET['queryType'])) {
                        $queryType = $_GET['queryType'];

                        // Customize your queries based on the selected queryType
                        if ($queryType === 'prematriculado') {
                            $query = "SELECT distinct student_id, course_id, section_id FROM enrollment where status = 0 ORDER BY student_id";
                        } elseif ($queryType === 'Matriculados') {
                            $query = "SELECT distinct student_id, course_id, section_id FROM enrollment where status = 1 ORDER BY student_id";
                        } elseif ($queryType === 'denegados') {
                            $query = "SELECT distinct student_id, course_id, section_id FROM enrollment where status = 2 ORDER BY student_id";
                        }
                    }

                    try {
                        if ($result = $dbc->query($query)) {
                            print   "<div class='row d-flex justify-content-end pr-3'>
        
                            <div class='col-'>
                            </div>
        
                            <div class='col-auto'>
                                <div class='dropdown'>
                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton'
                                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        Select Query
                                    </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                        <a class='dropdown-item' href='reporte_estudiantes.php?queryType=default'>Default</a>
                                        <a class='dropdown-item' href='reporte_estudiantes.php?queryType=prematriculado'>Pre - Matriculados</a>
                                        <a class='dropdown-item' href='reporte_estudiantes.php?queryType=Matriculados'>Matriculados</a>
                                        <a class='dropdown-item' href='reporte_estudiantes.php?queryType=denegados'>Denegados</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ";

                            print " <table class='table table-striped'>";
                            print "<tr> 
      
    
                        <th>Numero Estudiante</th>
                        <th>Curso</th>
                        <th>Seccion</th>
                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                print "<tr><form method='POST'>

                            <td>" . $row['student_id'] . "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'></td>
                            <td>" . $row['course_id'] . "</td>
                            <td>" . $row['section_id'] . "</td>
                        </form></tr>";
                            }
                            print "</table>";
                            echo "<h2 style='text-align:center'>";

                            for ($i = 1; $i <= $total_pags; $i++)
                                echo "<a  class='btn pages' href='courses.php?limite=$limite'> $i </a>&nbsp;&nbsp;";

                            echo "</h2>";
                        }
                    } catch (Exception $e) {
                        print "<h3 style=\"color:red\">Error en el query: " . $dbc->error . "</h3>";
                    } finally {
                        $dbc->close();
                    }
                    ?>
                </div>
            </div>
    </section>

    <!-- end courses section -->

    <!-- footer section -->
    <?php include("layouts/footer.php") ?>
    <!-- end footer section -->

</body>

</html>
