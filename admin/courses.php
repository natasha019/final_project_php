<?php
session_start();

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
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                        //esto lo hice con chat pq cuando le daba al boton como no tenia un name como tal pues boom
                        // yo dije pero pq no me funciona esto xd y era el name ese que faltaba
                        //pero esto divide los valores del post
                        list($course_id, $section_id) = explode("|", $_POST['delete']);
                       
                        $query_eli = "DELETE FROM section 
                                      WHERE course_id = '$course_id' AND section_id = '$section_id'";
                        
                        if ($dbc->query($query_eli) === TRUE) {
                            echo "Curso eliminado correctamente";
                        } else {
                            echo "Error: " . $query_eli . "<br>" . $dbc->error;
                        }
                    
                        
                    }
                


                        // if (isset($_POST['course_id']) && isset($_POST['editar'])) {
                        //     $student_id = '000001111';
                        //     $course_id = $_POST['course_id'];
                        //     $section_id = $_POST['section_id'];
                        //     $capacity = $_POST['capacity'];

                        //     // Determine the status based on capacity
                        //     $status = ($capacity > 0) ? 0 : 1;

                        //     // Insert the selected course into the database with the current timestamp
                        //     $sql = "INSERT INTO enrollment (student_id, course_id, section_id, timestamp, status) 
                        //     VALUES ('$student_id', '$course_id', '$section_id', NOW(), '$status')";
                        //     if ($dbc->query($sql) === TRUE) {
                        //         echo "Course added to the database successfully.";
                        //     } else {
                        //         echo "Error: " . $sql . "<br>" . $dbc->error;
                        //     }
                        // }
                    

                    //query para ver las clases
                    $query = "SELECT COUNT(course_id) as contador
                     FROM course";

                    $result = $dbc->query($query);
                    $row = $result->fetch_assoc();
                    $contador = $row['contador'];
                    $total_pags = ceil($contador / $limite);
                    $pag_actual = ceil($desde / $limite) + 1;

                    $query = "SELECT DISTINCT c.course_id, c.title, s.section_id, c.credits, s.capacity
                    FROM course c
                    INNER JOIN section s ON s.course_id = c.course_id
                    ORDER BY c.course_id, s.section_id";

                    try {
                        if ($result = $dbc->query($query)) {
                            print   "<div class='row d-flex justify-content-end pr-3'>
                            <div class='col-'><form class='d-flex'>
                            <input class='form-control me-sm-2' type='search' placeholder='Search'>
                            <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
                          </form></div>
                          </div>";

                            print " <table class='table table-striped'>";
                            print "<tr> 
                            <th></th>
                            <th></th>
                        <th>Codigo</th>
                        <th>Seccion</th>                        
                        <th>Nombre del curso</th>
                        <th>Creditos</th>
                        <th>Cupo</th>                        
                    </tr>";
                    while ($row = $result->fetch_assoc()) {
                        print "<tr><form method='POST'>
                            <td><button type='submit' name='delete' id='delete' value='" . $row['course_id'] . "|" . $row['section_id'] . "'><i class='gg-trash-empty'></i></button></td>
                            <td><a href='editar_curso.php?estID=" . $row['course_id'] . "'>Editar</a></td>
                            <td>" . $row['course_id'] . "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'></td>
                            <td>" . $row['section_id'] . "<input type='hidden' name='section_id' value='" . $row["section_id"] . "'></td>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['credits'] . "</td>
                            <td>" . $row['capacity'] . "<input type='hidden' name='capacity' value='" . $row["capacity"] . "'></td>
                        </form></tr>";
                    }
                            print "</table>";
                            echo "<h2 style='text-align:center'>";

                            for ($i = 1; $i <= $total_pags; $i++)
                                echo "<a  class='btn pages' href='courses.php?desde=" . (($i - 1) * $limite) . "&limite=$limite'> $i </a>&nbsp;&nbsp;";

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