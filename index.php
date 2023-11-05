<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>LOGIN - Pre-Matricula</title>

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
                <img src="images/hero-bg.png" alt="">
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
            <div class="row">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="assets/images/about-img.png" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <h3>
                            Autenticarse
                        </h3>
                        <?php
                        //if (isset($_POST['submit']))
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if ((!empty($_POST['email'])) && (!empty($_POST['pass']))) { //conectarme a ver si existe ese estudiante de honor    
                                include_once("db_info.php");
                                $email = $_POST['email'];
                                $pass = $_POST['pass'];

                                $query = "SELECT * FROM estudiantes
                      WHERE email = '$email'";

                                $result = $dbc->query($query);
                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    echo "psw de la base de datos: " . $row['psw'];

                                    //  Redirigir el usuario a la página correspondiente
                                    if (password_verify($pass, $row['psw']) && $row['rol'] == 0) {
                                        session_start();
                                        $_SESSION['id'] = $row['estID'];
                                        $_SESSION['nombre'] = $row['nombre'] . ' ' . $row['apellidoP'];
                                        $_SESSION['email'] = $row['email'];
                                        $_SESSION['rol'] = $row['rol'];
                                        echo "<p>password correcto</p>";
                                        header('Location: admin/index.php');
                                    } elseif (password_verify($pass, $row['psw']) && $row['rol'] == 1) {
                                        session_start();
                                        $_SESSION['id'] = $row['estID'];
                                        $_SESSION['nombre'] = $row['nombre'] . ' ' . $row['apellidoP'];
                                        $_SESSION['email'] = $row['email'];
                                        $_SESSION['rol'] = $row['rol'];
                                        echo "<p>password correcto</p>";
                                        header('Location: user/index.php');
                                    } else
                                        echo "<p>password incorrecto</p>";
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
        <table border="0">
          <tr>
            <td width="140" align="right">Email:</td>
            <td><input type="email" name="email" size="50" maxlength="60" required /></td>
          </tr>
          <tr>
            <td width="255" align="right">Password:</td>
            <td><input type="password" name="pass" ></td>
          </tr>
         
          <tr>
            <td></td>
            <td><input type="submit" class="btn btn-primary" name="submit" value="Entrar" /></td>
          </tr></table></form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- login about section -->
    <!-- jQery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- owl slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- custom js -->
    <script type="text/javascript" src="js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>