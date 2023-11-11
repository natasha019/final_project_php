<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="assets/images/favicon.png" type="">
    <title>Registrarse - Pre-Matricula</title>

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
                            <span style="color: #00bbf0;">Registrarse</span>
                        </h3>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if (isset($_POST['submit'])) {
                                include_once("db_info.php");
                                $user_name = $_POST['name'];
                                $studentNum = $_POST['studentNum'];
                                $studentYear = $_POST['studentYear'];
                                $email = $_POST['email'];
                                $lastname = $_POST['lastname'];
                                $password = $_POST['pass'];


                                //query para buscar al admin en la base de datos
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
                                    echo "<p>Ya existe ese email en nuestra base de datos.</p>";
                                } elseif ($resultS->num_rows == 1) {
                                    echo "<p>Ya existe ese email en nuestra base de datos.</p>";
                                } else {
                                    //query para insertar estudiantes
                                    $queryS = "INSERT INTO student (user_name,last_name,email,student_id,password,year_of_study)
                                    VALUES (?,?,?,?,?,?)";


                                    $stmt = $dbc->prepare($queryS);
                                    $stmt->bind_param("sssisi", $user_name, $lastname, $email, $studentNum, $password, $studentYear);
                                    if (!$stmt->execute()) {
                                        throw new Exception("Error: " . $stmt->error);
                                    }
                                    header('Location: index.php');
                                }
                                $dbc->close();
                            }
                        } else // No llegó por un submit, por lo tanto hay que presentar el formulario
                        {
                            print '<form action="registrarse.php" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <label for="name" class="form-label mt-4">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="form-label mt-4">Apellido</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Apellido" required>
                                </div>
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label for="studentNum" class="form-label mt-4">Numero de estudiante</label>
                                        <input type="number" class="form-control" id="studentNum" name="studentNum" placeholder="840-00-0000" required>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="studentYear" class="form-label mt-4">Año de estudio</label>
                                        <select name="studentYear" id="studentYear" class="form-control" required>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label mt-4">Correo Electronico</label>
                                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email@upr.edu" required>
                                </div>
                                <div class="form-group">
                                    <label for="passsword" class="form-label mt-4">Password</label>
                                    <input type="password" class="form-control" id="password" name="pass" placeholder="Password" autocomplete="off" required>
                                </div>                               
                                <p>Ya tienes una cuenta? <a href="index.php" >Haz login</a></p>
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