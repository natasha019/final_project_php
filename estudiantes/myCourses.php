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

    <title>Mis Cursos</title>

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
                        Mis <span>Cursos</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");
                    //query para insertar clases

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['course_id'])) {
                            $student_id = '840194867';
                            $course_id = $_POST['course_id'];
                            $section_id = $_POST['section_id'];



                            // Borrar una clase de mis cursos  
                            $sql = "DELETE FROM enrollment WHERE student_id = '$student_id' AND course_id = '$course_id'";
                            if ($dbc->query($sql) === TRUE) {
                                echo "Curso eliminado de la base de datos.";
                            } else {
                                echo "Error: " . $sql . "<br>" . $dbc->error;
                            }
                        }
                    }
                    // chequeamos que el query de búsqueda esté disponible
                    $student_id = '840194867';

                    // Check if a search query for course_id is provided
                    if (isset($_GET['query_busqueda'])) {
                        $query_busqueda = $_GET['query_busqueda'];

                        $query = "SELECT * 
                            FROM enrollment e 
                            INNER JOIN course c ON e.course_id = c.course_id
                            WHERE e.student_id = '$student_id'
                            AND c.course_id LIKE '%$query_busqueda%'";
                    } else {
                        // Default query without search
                        $query = "SELECT * 
                                FROM enrollment e 
                                INNER JOIN course c ON e.course_id = c.course_id
                                WHERE e.student_id = '$student_id'";
                    }
                    try {
                        if ($result = $dbc->query($query)) {
                            print   "<div class='row d-flex justify-content-end pr-3'>
                            <div class='col-'>
                            <form class='d-flex' method='GET' action='myCourses.php'>
                            <input class='form-control me-sm-2' type='search' placeholder='Search' name='query_busqueda'>
                            <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
                        </form>
                          </div>
                          </div>";

                            print "<table class='table table-striped mb-5'>";
                            print "<tr> 
                            <th></th>
                        <th>Codigo</th>
                        <th>Seccion</th>                        
                        <th>Nombre del curso</th>
                        <th>Creditos</th>                     
                    </tr>";
                            while ($row = $result->fetch_assoc()) {

                                print "<tr><form method='POST'>
                            <td><input type='submit' value='Eliminar'></td>
                            <td>" . $row['course_id'] . "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'></td>
                            <td>" . $row['section_id'] . "<input type='hidden' name='section_id' value='" . $row["section_id"] . "'></td>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['credits'] . "</td>
                            
                            </form></tr>";
                            }
                            print "</table>";
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

    <!-- jQery -->
    <script type="text/javascript" src="../assets/js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="../assets/js/bootstrap.js"></script>
    <!-- owl slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- custom js -->
    <script type="text/javascript" src="../assets/js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>