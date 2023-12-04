<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="assets/images/favicon.png" type="">
    <title>Login - Pre-Matricula</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="assets/css/responsive.css" rel="stylesheet" />


</head>

<body class="sub_page">

    <div class="hero_area">

        <div class="hero_bg_box">
            <div class="bg_img_box">
                <img src="assets/images/hero-bg.png" alt="">
            </div>
        </div>

    </div>

    <!-- login section -->

    <section class="about_section layout_padding ">
        <div class="container  ">
            <div class="heading_container heading_center">
                <h2>
                    Pre-Matricula <span>UPRA</span>
                </h2>
            </div>
            <div class="row shadow p-3 mb-5 bg-body-tertiary rounded" id="login">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="assets/images/slider-img.png" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <h3>
                            Autenticarse
                        </h3>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if ((!empty($_POST['email'])) && (!empty($_POST['pass']))) { //conectarme a ver si existe ese estudiante o admin   
                                include_once("db_info.php");
                                $email = $_POST['email'];
                                $pass = $_POST['pass'];

                                //$hashedSubmittedPassword = password_hash($pass, PASSWORD_DEFAULT);


                                //query para buscar al admin en la base de datos
                                echo $email;
                                $queryA = "SELECT * FROM admin
                                        WHERE email = ?";
                                $stmt = $dbc->prepare($queryA);
                                $stmt->bind_param("s", $email);

                                // ejecuta el statement
                                $stmt->execute();
                                $resultA = $stmt->get_result();

                                //query para buscar al estudiante en la base de datos
                                $queryS = "SELECT * FROM student
                                        WHERE email = ?";
                                $stmt = $dbc->prepare($queryS);
                                $stmt->bind_param("s", $email);

                                // ejecuta el statement
                                $stmt->execute();
                                $resultS = $stmt->get_result();

                                //si encuentra al admin
                                if ($resultA->num_rows == 1) {
                                    $row = $resultA->fetch_assoc();
                                    //echo ("password de db");

                                    //echo "Stored Hashed Password: " . $row['password'] . "<br>";

                                    //  Redirigir el usuario a la página correspondiente
                                    if (password_verify($pass, $row['password'])) {

                                        // if ($pass === $row['password']) {
                                        session_start();
                                        $_SESSION['name'] = $row['user_name'] . ' ' . $row['user_lastname'];
                                        $_SESSION['email'] = $row['email'];

                                        echo "<p>password correcto</p>";
                                        $_SESSION['authenticated'] = 'true';
                                        header('Location: admin/cursos.php');
                                    } else {
                                        echo "<p>password incorrecto</p>";
                                    }


                                    //si encuentra al estudiante      
                                } elseif ($resultS->num_rows == 1) {
                                    $row = $resultS->fetch_assoc();


                                    if (password_verify($pass, $row['password'])) {
                                        //if ($pass === $row['password']) {
                                        session_start();
                                        $_SESSION['student_num'] = $row['student_id'];
                                        $_SESSION['nombre'] = $row['user_name'] . ' ' . $row['user_lastname'];
                                        $_SESSION['email'] = $row['email'];
                                        echo "<p>password correcto</p>";
                                        $_SESSION['authenticated'] = 'true';
                                        header('Location: estudiantes/cursos.php');
                                    } else {
                                        //Incorrect password for student
                                        echo "<p>Password Incorrecto</p>";
                                        echo $pass;
                                        echo $row['password'];
                                    }
                                } else {
                                    print '<h3>Su email no concuerda con nuestros archivos!<br />Vuelva a intentarlo...<a href="index.php"> Login </a></h3>';
                                }
                                $dbc->close();
                            } else {   // No entró uno de los campos
                                print '<h3>Asegúrese de entrar su email y número de estudiante. <br /> Vuelva a intentarlo...<a href="index.php"> Login </a></h3>';
                            }
                        } else // No llegó por un submit, por lo tanto hay que presentar el formulario
                        {
                            print '<form action="index.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label for="email" class="form-label mt-4">Correo Electronico</label>
                                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email@upr.edu">
                                </div>
                                <div class="form-group">
                                    <label for="passsword" class="form-label mt-4">Password</label>
                                    <input type="password" class="form-control" id="password" name="pass" placeholder="Password" autocomplete="off">
                                </div>
                                <p>No tienes una cuenta? <a href="registrarse.php">Registrese</a></p>
                                <button type="submit" name="submit" value="Entrar" class="btn btn-primary">Entrar</button>
                            </fieldset>
                        </form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- login about section -->
    <!-- jQery -->
    <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    <!-- owl slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- custom js -->
    <script type="text/javascript" src="assets/js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>