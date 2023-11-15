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
                    <h2 class="mb-0">
                        Secciones <span>Disponibles</span>
                    </h2>
                </div>
                <div class="mt-5">
                    <?php
                    include_once("../db_info.php");
                    //query para insertar clases
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                        list($course_id, $section_id) = explode("|", $_POST['delete']);

                        // Eliminar los cursos que se relacionan con la clase
                        $query_delete_courses = "DELETE FROM course WHERE course_id = '$course_id'";
                        if ($dbc->query($query_delete_courses) === TRUE) {
                            echo "Courses associated with the section deleted successfully";
                        } else {
                            echo "Error: " . $query_delete_courses . "<br>" . $dbc->error;
                        }

                        // Eliminacion de la seccion 
                        $query_delete_section = "DELETE FROM section WHERE section_id = '$section_id'";
                        if ($dbc->query($query_delete_section) === TRUE) {
                            echo "Section deleted successfully";
                        } else {
                            echo "Error: " . $query_delete_section . "<br>" . $dbc->error;
                        }
                    }



                    //query para crear secciones
                    if (isset($_POST['crearSec'])) {
                        $course_id = strtoupper($_POST['course_id']);
                        $section_id = strtoupper($_POST['section_id']);
                        $capacity = $_POST['capacity'];

                        $query = "SELECT * FROM section WHERE course_id = ? AND section_id = ?";
                        $stmt = $dbc->prepare($query);
                        $stmt->bind_param("ssi", $course_id, $section_id, $capacity);
                        if (!$stmt->execute()) { {
                                throw new Exception("Error: " . $stmt->error);
                            }
                            //header('Location: secciones.php');
                        } else {
                            $result = $stmt->get_result();
                            if ($result->num_rows == 0) {
                                // Insert the selected course into the database with the current timestamp
                                print '<h3 style="color:red;">no existe esa seccion</h3>';
                                $sql = "INSERT INTO section (course_id, section_id, capacity) 
                                VALUES ($course_id, $section_id, $capacity)";
                                if ($dbc->query($sql) === TRUE) {
                                    print "La seccion fue creada.";
                                } else {
                                    print "Error: " . $sql . "<br>" . $dbc->error;
                                }
                            } else
                                print '<h3 style="color:red;">Ya existe esa seccion</h3>';
                        }
                    }


                    //query para paginacion
                    $query = "SELECT COUNT(course_id) as contador
                     FROM course";

                    $result = $dbc->query($query);
                    $row = $result->fetch_assoc();
                    $contador = $row['contador'];
                    $total_pags = ceil($contador / $limite);
                    $pag_actual = ceil($desde / $limite) + 1;

                    //query para ver las secciones
                    $query = "SELECT DISTINCT c.course_id, c.title, s.section_id, c.credits, s.capacity
                    FROM course c
                    INNER JOIN section s ON s.course_id = c.course_id
                    ORDER BY c.course_id, s.section_id";

                    try {
                        if ($result = $dbc->query($query)) {
                            print   "<div class='row d-flex justify-content-end pr-3'>
                            <div class='col'>
                            <a href='crear_seccion.php' class='btn btn-primary d-flex mw-25'>Crear seccion&nbsp;&nbsp;<i class='gg-add-r'></i></a>
                             </div>
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
                            <td><button type='submit' name='delete' class='delete' value='" . $row['course_id'] . "|" . $row['section_id'] . "'><i class='gg-trash-empty'></i></button></td>
                            <td><a href='editar_seccion.php?course_id=" . $row['course_id'] . "&section_id=" . $row['section_id'] . "'>Editar</a></td>
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
    <script>
        // $(document).ready(() => {
        //     $("#create_section").submit((event) => {
        //         // Prevent the default form submission
        //         event.preventDefault();
        //         setTimeout(() => {
        //             $("#create_section").modal("hide");
        //         }, 5000);
        //     });

        // });
    </script>

</body>

</html>