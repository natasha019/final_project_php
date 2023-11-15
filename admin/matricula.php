<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: ../index.php');
}

$titulo = "Estudiantes de Honor UPRA";
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
                    <h2 class="mb-5">
                        Cursos <span>Disponibles</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");

                    if (isset($_GET['matricula'])) //vino del index
                    {
                        print '<form action="matricula.php" method="post" >
                        <h3> Estas seguro que desea correr la pre-matricla?</h3>';

                        print '<input type="hidden" name="matricula" value="true"/>';
                        print '<div style="text-align:center;">
                                <input type="submit" name="submit" value="Correr pre-matricula"/>
                                </div> </form>';
                    } elseif (isset($_POST['matricula']) && $_POST['matricula'] == "true") //vino del form
                    {
                        $queryGetSections = "SELECT DISTINCT e.course_id, e.section_id, s.capacity
                                             FROM enrollment e
                                             JOIN section s ON e.course_id = s.course_id";
                        $resultCourses = $dbc->query($queryGetSections);

                        while ($row = $resultCourses->fetch_assoc()) {
                            //matricular
                            $queryUpdateM = "UPDATE enrollment
                            SET status = 1
                            WHERE course_id = ? AND section_id = ? AND status = 0
                            ORDER BY timestamp ASC
                            LIMIT ?";

                            $stmt = $dbc->prepare($queryUpdateM);
                            $stmt->bind_param("ssi", $row['course_id'], $row['section_id'], $row['capacity']);
                            $stmt->execute();
<<<<<<< Updated upstream
=======
                        }

                        $queryGetSections = "SELECT DISTINCT e.course_id, e.section_id, s.capacity
                        FROM enrollment e
                        JOIN section s ON e.course_id = s.course_id";
                        $resultCourses = $dbc->query($queryGetSections);
                        while ($row = $resultCourses->fetch_assoc()) {
                            //denegar
                            $queryUpdateD = "UPDATE enrollment
                            SET status = 2
                            WHERE course_id = ? AND section_id = ? AND status = 0";

                            $stmt2 = $dbc->prepare($queryUpdateD);
                            $stmt2->bind_param("ss", $row['course_id'], $row['section_id']);
                            $stmt2->execute();
>>>>>>> Stashed changes
                        }
                    } else {
                        $dbc->close();
                        //header('Location: admin/cursos.php');
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