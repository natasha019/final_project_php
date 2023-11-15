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
                        Editar <span>Seccion</span>
                    </h2>
                </div>
                <div class="mt-5 mx-5">
                    <?php
                    include_once("../db_info.php");

                    if (isset($_GET['course_id']) && isset($_GET['section_id'])) {
                        $course_id = $_GET['course_id'];
                        $section_id = $_GET['section_id'];

                        $query = "SELECT * FROM section WHERE course_id = ? AND section_id = ?";
                        $stmt = $dbc->prepare($query);
                        $stmt->bind_param("ss", $course_id, $section_id);
                        $stmt->execute();
                        //$result = $stmt->get_result();
                        if ($result = $stmt->get_result()) {
                            $row = $result->fetch_assoc();
                            print '<div class="h-100">
                             <form id="edit_section" method="post" action="editar_seccion.php">
                                 <fieldset>
                                     <div class="form-group">
                                         <label for="course_id" class="form-label mt-4">Codigo del curso</label>
                                         <input type="text" class="form-control" name="course_id" id="course_id" value="' . $row['course_id'] . '" readonly>                        
                                     </div>
                                     <div class="form-group">
                                         <label for="section_id" class="form-label mt-4">Seccion</label>
                                         <input type="text" class="form-control" name="section_id" id="section_id" value="' . $row['section_id'] . '" required>
                                     </div>
                                     <div class="form-group">
                                         <label for="capacity" class="form-label mt-4">Capacidad</label>
                                         <input type="number" class="form-control" name="capacity" id="capacity" value="' . $row['capacity'] . '" min="10" max="25" required>
                                     </div>
                                     <div class="modal-footer">
                                     <input class="btn btn-primary" type="submit" name="editar" id="Editar" value="Editar" />
                                     </div>
                                 </fieldset>
                             </form></div>';
                        } else
                            print '<h3 style="color:red;">No se pudo traer la información de la seccion. Error:<br />' . $dbc->error . '</h3>';
                    } else if (isset($_POST['course_id']) && isset($_POST['section_id']) && isset($_POST['capacity'])) {
                        $course = $_POST['course_id'];
                        $section = htmlspecialchars($_POST['section_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
                        $capacity = htmlspecialchars($_POST['capacity'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

                        $query = "UPDATE section 
                                  SET  section_id=?, capacity=?
                                  WHERE course_id=?";
                        $stmt = $dbc->prepare($query);
                        $stmt->bind_param("sis", $section, $capacity, $course);
                        //$stmt->execute();

                        if ($stmt->execute() === TRUE)
                            print '<h3>La seccion ha sido actualizado exitosamente. <a href="secciones.php" class="d-flex ">Volver a Secciones&nbsp;&nbsp;</a></h3>';
                        else
                            print '<h3 style="color:red;">No se pudo actualizar la seccion porque:<br />' . $dbc->error . '</h3>';
                    }

                    $dbc->close();

                    ?>

                </div>
            </div>

    </section>


    <!-- end courses section -->

    <!-- footer section -->

    <?php include("layouts/footer.php") ?>


</body>

</html>