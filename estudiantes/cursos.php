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

    <title>Pre-Matricula</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
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
                        Cursos <span>Disponibles</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");


                    //query para insertar clases
                    $student_id = $_SESSION['student_num'];
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['course_id'])) {
                            $course_id = $_POST['course_id'];
                            $section_id = $_POST['section_id'];
                            $capacity = $_POST['capacity'];


                            // determinamos el estado dependiendo de la capacidad
                            $status = ($capacity > 0) ? 0 : 1;

                            // insertar curso con el tiempo actual 
                            $sql = "INSERT INTO enrollment (student_id, course_id, section_id, timestamp, status) 
                            VALUES ('$student_id', '$course_id', '$section_id', NOW(), '$status')";
                            if ($dbc->query($sql) === TRUE) {
                                echo "Course added to the database successfully.";
                            } else {
                                echo "Error: " . $sql . "<br>" . $dbc->error;
                            }
                        }
                    }

                    // query para ver las clases
                    $query = "SELECT COUNT(course_id) as contador FROM course";
                    $result = $dbc->query($query);
                    $row = $result->fetch_assoc();
                    //paginacion
                    // $contador = $row['contador'];
                    // $total_pags = ceil($contador / $limite);
                    // $pag_actual = ceil($desde / $limite) + 1;

                    //selecciona los cursos en los que el estudiante se prematriculo
                    $queryE = "SELECT course_id FROM enrollment WHERE student_id = ?";
                    $stmt = $dbc->prepare($queryE);
                    $stmt->bind_param("s", $student_id);
                    if (!$stmt->execute()) {
                        die('Error: ' . $stmt->error);
                    }

                    $result = $stmt->get_result();
                    //$row = $result->fetch_assoc();
                    $coursesEnrolled = [];
                    try {
                        while ($row = $result->fetch_assoc()) {
                            array_push($coursesEnrolled, $row['course_id']);
                        }
                    } catch (Exception $e) {
                        print "<h3 style=\"color:red\">Error en el query: " . $dbc->error . "</h3>";
                    }



                    // chequeamos que el query de búsqueda esté disponible
                    if (isset($_GET['query_busqueda'])) {
                        $query_busqueda = $_GET['query_busqueda'];

                        // Modify your SQL query to include a WHERE clause
                        $query = "SELECT DISTINCT c.course_id, c.title, s.section_id, c.credits, s.capacity
                            FROM course c
                            INNER JOIN section s ON s.course_id = c.course_id
                            WHERE c.course_id LIKE '%$query_busqueda%'
                            ORDER BY c.course_id, s.section_id";
                    } else {
                        // Your existing query without search
                        $query = "SELECT DISTINCT c.course_id, c.title, s.section_id, c.credits, s.capacity
                            FROM course c
                            INNER JOIN section s ON s.course_id = c.course_id
                            ORDER BY c.course_id, s.section_id";
                    }

                    try {
                        if ($result = $dbc->query($query)) {

                            print "<div class='row d-flex justify-content-end pr-3'>
                            <div class='col-'>
                                <form class='d-flex' method='GET' action='courses.php'>
                                    <input class='form-control me-sm-2' type='search' placeholder='Search' name='query_busqueda'>
                                    <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
                                </form>
                            </div>
                        </div>";

                            print " <table class='table table-striped'>";
                            print "<tr> 
                                <th></th>
                                <th>Seccion</th>                        
                                <th>Nombre del curso</th>
                                <th>Creditos</th>
                                <th>Cupo</th>                        
                            </tr>";
                            while ($row = $result->fetch_assoc()) {
                                print "<tr><form method='POST'>
                                <td><input type='submit' value='Matricular'";
                                if (in_array($row['course_id'], $coursesEnrolled)) {
                                    print "disabled";
                                }
                                print "></td>
                                <td>" . $row['course_id'] . "-" . $row['section_id'] . "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'><input type='hidden' name='section_id' value='" . $row["section_id"] . "'></td>                               
                                <td>" . mb_strtoupper($row['title']) . "</td>
                                <td>" . $row['credits'] . "</td>
                                <td>" . $row['capacity'] . "<input type='hidden' name='capacity' value='" . $row["capacity"] . "'></td>
                                </form></tr>";
                            }
                            print "</table>";
                            // echo "<h2 style='text-align:center'>";

                            // for ($i = 1; $i <= $total_pags; $i++)
                            //     echo "<a  class='btn pages' href='cursos.php?desde=" . (($i - 1) * $limite) . "&limite=$limite'> $i </a>&nbsp;&nbsp;";

                            // echo "</h2>";
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