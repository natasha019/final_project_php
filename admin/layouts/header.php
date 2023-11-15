<div class="hero_area">

    <div class="hero_bg_box">
        <div class="bg_img_box">
            <img src="images/hero-bg.png" alt="">
        </div>
    </div>

    <!-- header section strats -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
                <a class="navbar-brand" href="../courses.php">
                    <span>
                        Administracion <span style="color:#00bbf0;">UPRA</span>
                    </span>
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class=""> </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav  ">
                        <li class="nav-item ">
                            <a class="nav-link" href="cursos.php">Cursos <span class="sr-only">(current)</span> </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="secciones.php">Secciones <span class="sr-only">(current)</span> </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reporte_estudiantes.php"> Reportes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cambiar_contra.php"> Cambiar Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="matricula.php?matricula=false">Correr Pre-matricula</a>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="layouts/header.php">
                                <input type="hidden" name="signout" value="1">
                                <button type="submit" class="btn btn-link">
                                    Logout &nbsp;<i class="fa fa-user" aria-hidden="true"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!-- end header section -->

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['signout'])) {
            session_start();
            $_SESSION = array(); // Limpiar todas las variables de sesión
            session_destroy(); // Destruir la sesión

            // Redirigir al usuario al inicio de sesión
            header("Location: ../../index.php");
            exit;
        }
    }
    ?>
</div>