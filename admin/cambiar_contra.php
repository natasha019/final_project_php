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
                        Cuentas <span>Estudiantiles</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");
                    //query para insertar clases

                    if (isset($_POST['change_passu'])) {

                        $student_id = $_POST['change_passu'];
                        echo $student_id;
                        $pass = "pass123";
                        $hash = password_hash($pass, PASSWORD_DEFAULT);

                        $query = "UPDATE student
                                SET password = '$hash'
                                where student_id = '$student_id'";

                        if ($dbc->query($query) === TRUE)
                            print '<h3>Contrasenas han sido actualizadas exitosamente</h3>';
                        else
                            print '<h3style="color:red;"> No se pudo actualizar la contrasena de los estudiantes ya que : <br/>' . $dbc->error . '</h3>';
                    }else if (isset($_POST['change_passa'])) {

                        $email = $_POST['change_passa'];
                        echo $email;
                        $pass = "pass123";
                        $hash = password_hash($pass, PASSWORD_DEFAULT);

                        $query = "UPDATE admin
                                SET password = '$hash'
                                where email = '$email'";

                        if ($dbc->query($query) === TRUE)
                            print '<h3>Contrasenas han sido actualizadas exitosamente</h3>';
                        else
                            print '<h3style="color:red;"> No se pudo actualizar la contrasena de los estudiantes ya que : <br/>' . $dbc->error . '</h3>';
                    }

                    $query = "SELECT COUNT(course_id) as contador
                     FROM course";

                    $result = $dbc->query($query);
                    $row = $result->fetch_assoc();
                    $contador = $row['contador'];
                    $total_pags = ceil($contador / $limite);
                    $pag_actual = ceil($desde / $limite) + 1;

                    $query = "SELECT student_id,user_name,last_name,email
                    FROM student";

                    $queryA = "SELECT user_name,user_lastname,email
                    FROM admin";

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
                        <th>Numero Estudiante</th>
                        <th>Nombre</th>                        
                        <th>Apellido</th>
                        <th>Email</th>
                                            
                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                print "<tr><form method='POST'>
            
                            <td><button type='submit' name='change_passu' class='delete' value='" . $row['student_id'] . "'>Cambiar Contrasena</button></td>
                            <td>" . $row['student_id'] . "<input type='hidden' name='student_id' value='" . $row["student_id"] . "'></td>
                            <td>" . $row['user_name'] . "<input type='hidden' name='user_name' value='" . $row["user_name"] . "'></td>
                            <td>" . $row['last_name'] . "</td>
                            <td>" . $row['email'] . "</td> 
                            
                            
                           </tr>";
                            }
                            print "</table>";
                        }

                        if ($result = $dbc->query($queryA)) {
                            print   "<div class='heading_container heading_center'>
                            <h2 class='mb-5 mt-5'>
                                Cuentas <span>Administradores</span>
                            </h2>
                        </div>
                            <div class='row d-flex justify-content-end pr-3'>
                          </form></div>
                          </div>";

                            print " <table class='table table-striped'>";
                            print "<tr> 
                            
                            <th></th>
                        <th>Nombre</th>                     
                        <th>Apellido</th>
                        <th>Email</th>
                                            
                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                print "<tr><form method='POST'>
            
                            <td><button type='submit' name='change_passa' class='delete' value='" . $row['email'] . "'>Cambiar Contrasena</button></td>
                            <td>" . $row['user_name'] . "<input type='hidden' name='user_name' value='" . $row["user_name"] . "'></td>
                            <td>" . $row['user_lastname'] . "</td>
                            <td>" . $row['email'] . "</td> 
                            
                            
                           </tr>";
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


</body>

</html>