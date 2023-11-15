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
                        Crear <span>Curso</span>
                    </h2>
                </div>
                <div class="mt-5 mx-5">
                    <?php
                    include_once("../db_info.php");

                    //query para crear secciones
                    if (isset($_GET['submit'])) {
                        $course_id = $_GET['course_id'];
                        $course_title = $_GET['course_title'];
                        $credits = $_GET['credits'];
                        print $course_id;

                        $query = "SELECT * FROM course WHERE course_id = ?";
                        $stmt = $dbc->prepare($query);
                        $stmt->bind_param("s", $course_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            print '<div class="my-5 " >
                            <h3 style="color:red;">Ya existe este curso</h3>
                            <a href="crear_curso.php" class="d-flex ">Crear otro curso&nbsp;&nbsp;<i class="gg-add-r"></i></a>
                            </div>';
                        } else {
                            // Insert the selected course into the database with the current timestamp
                            $sqlI = "INSERT INTO course (course_id, title, credits) 
                                VALUES (?,?,?)";
                            $stmt1 = $dbc->prepare($sqlI);
                            $stmt1->bind_param("ssi", $course_id, $course_title, $credits);

                            if (!$stmt1->execute()) {
                                throw new Exception("Error: " . $stmt1->error);
                            } else {
                                print '<div class="my-5 d-flex" >
                                <h3>Se realizo maticula. <a href="cursos.php" class="d-flex ">Volver a Cursos&nbsp;&nbsp;</a></h3>                                
                                </div>';
                            }
                        }
                    } else {
                        echo '<div class="h-100">
                        <form id="create_section" method="get" action="crear_curso.php">
                            <fieldset>
                                <div class="form-group">
                                    <label for="course_id" class="form-label mt-4">Codigo del curso</label>
                                    <input type="text" class="form-control" name="course_id" id="course_id" aria-describedby="course_id" placeholder="CCOM3001" required>
                                </div>
                                <div class="form-group">
                                    <label for="course_title" class="form-label mt-4">Titulo del curso</label>
                                    <input type="text" class="form-control" name="course_title" id="course_title" placeholder="Progrmamacion I" required>
                                </div>
                                <div class="form-group">
                                    <label for="credits" class="form-label mt-4">Creditos</label>
                                    <input type="number" class="form-control" name="credits" id="credits" placeholder="5" required>
                                </div>
                                <div class="modal-footer">
                                    <input class="btn btn-primary" type="submit" name="submit" id="submitForm" value="Crear Curso" />
                                </div>
                            </fieldset>
                        </form></div>';
                    }
                    ?>

                </div>
            </div>

    </section>


    <!-- end courses section -->

    <!-- footer section -->

    <?php include("layouts/footer.php") ?>


</body>

</html>