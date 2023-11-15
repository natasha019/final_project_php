<?php
session_start();
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
                    include_once("db_info.php");

                    if (isset($_GET['estID']) && is_numeric($_GET['estID'])) //vino del index
                    {
                        $query = "SELECT *
            FROM estudiantes
            WHERE estID={$_GET['estID']}";
                        try {
                            if ($result = $dbc->query($query)) {
                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    print '<form action="eliminar_estudiante.php" method="post" >
                        <h3> Estas seguro que desea eliminar al siguiente estudiante de honor:
                        ' . $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'] . ';
                        ' . $row['numEst'] . '?</h3>';

                                    print '<input type="hidden" name="estID" value="' . $_GET['estID'] . '"/>';
                                    print '<div style="text-align:center;">
                                    <input type="submit" name="submit" value="Eliminar Estudiante"/>
                                </div> </form>';
                                } else {
                                    print '<h3 style="color:red;" >Error, el estudiante no se encontro en la tabla</h3>';
                                }
                            }
                        } catch (Exception $e) {
                            print '<h3 style="color:red;" >Error en el query:' . $dbc->error . '</h3>';
                        }
                    } elseif (isset($_POST['estID']) && is_numeric($_POST['estID'])) //vino del form
                    {
                        $query = "DELETE FROM estudiantes WHERE estID={$_POST['estID']} LIMIT 1";
                        if ($dbc->query($query) === TRUE)
                            echo '<h3 class="centro"> El record del estudiante ha sido eliminado con exito.</h3>';
                        else
                            echo '<h3 class="centro">No se pudo eliminar al estudiante porque: <br/>' . $dbc->error . '</h3>';
                    } else {
                        echo '<h3 class="centro" style="color:red;">Esta pagina ha sido accedido con error.</h3>';
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