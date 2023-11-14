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
                    <h2 class="mb-5">
                        Cursos <span>Disponibles</span>
                    </h2>
                </div>
                <div>
                    <?php
                    include_once("../db_info.php");
                    //query para insertar clases
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                        //esto lo hice con chat pq cuando le daba al boton como no tenia un name como tal pues boom
                        // yo dije pero pq no me funciona esto xd y era el name ese que faltaba
                        //pero esto divide los valores del post
                        list($course_id, $section_id) = explode("|", $_POST['delete']);

                        $query_eli = "DELETE FROM section 
                                      WHERE course_id = '$course_id' AND section_id = '$section_id'";

                        if ($dbc->query($query_eli) === TRUE) {
                            echo "Curso eliminado correctamente";
                        } else {
                            echo "Error: " . $query_eli . "<br>" . $dbc->error;
                        }
                    }



                    // if (isset($_POST['course_id']) && isset($_POST['editar'])) {
                    //     $student_id = '000001111';
                    //     $course_id = $_POST['course_id'];
                    //     $section_id = $_POST['section_id'];
                    //     $capacity = $_POST['capacity'];

                    //     // Determine the status based on capacity
                    //     $status = ($capacity > 0) ? 0 : 1;

                    //     // Insert the selected course into the database with the current timestamp
                    //     $sql = "INSERT INTO enrollment (student_id, course_id, section_id, timestamp, status) 
                    //     VALUES ('$student_id', '$course_id', '$section_id', NOW(), '$status')";
                    //     if ($dbc->query($sql) === TRUE) {
                    //         echo "Course added to the database successfully.";
                    //     } else {
                    //         echo "Error: " . $sql . "<br>" . $dbc->error;
                    //     }
                    // }


                    //query para ver las clases
                    $query = "SELECT COUNT(course_id) as contador
                     FROM course";

                    $result = $dbc->query($query);
                    $row = $result->fetch_assoc();
                    $contador = $row['contador'];
                    $total_pags = ceil($contador / $limite);
                    $pag_actual = ceil($desde / $limite) + 1;

                    $query = "SELECT DISTINCT c.course_id, c.title, s.section_id, c.credits, s.capacity
                    FROM course c
                    INNER JOIN section s ON s.course_id = c.course_id
                    ORDER BY c.course_id, s.section_id";

                    try {
                        if ($result = $dbc->query($query)) {
                            print   "<div class='row d-flex justify-content-end pr-3'>
                            <div class='col'><!-- Button trigger modal -->
                            <button type='button' class='btn btn-primary d-flex' data-bs-toggle='modal' data-bs-target='#crearSeccion'>
                              Crear seccion&nbsp;&nbsp;<i class='gg-add-r'></i>
                            </button></div>
                            <div class='col-'><form class='d-flex'>
                            <input class='form-control me-sm-2' type='search' placeholder='Search'>
                            <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
                          </form></div>
                          </div>";

                            print " <table class='table table-striped'>";
                            print "<tr> 
                            <th></th>
                            <th></th>
                        <th>Codigo</th>
                        <th>Seccion</th>                        
                        <th>Nombre del curso</th>
                        <th>Creditos</th>
                        <th>Cupo</th>                        
                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                print "<tr><form method='POST'>
                            <td><button type='submit' name='delete' class='delete' value='" . $row['course_id'] . "|" . $row['section_id'] . "'><i class='gg-trash-empty'></i></button></td>
                            <td><a href='editar_curso.php?estID=" . $row['course_id'] . "'>Editar</a></td>
                            <td>" . $row['course_id'] . "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'></td>
                            <td>" . $row['section_id'] . "<input type='hidden' name='section_id' value='" . $row["section_id"] . "'></td>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['credits'] . "</td>
                            <td>" . $row['capacity'] . "<input type='hidden' name='capacity' value='" . $row["capacity"] . "'></td>
                        </form></tr>";
                            }
                            print "</table>";
                            echo "<h2 style='text-align:center'>";

                            for ($i = 1; $i <= $total_pags; $i++)
                                echo "<a  class='btn pages' href='courses.php?desde=" . (($i - 1) * $limite) . "&limite=$limite'> $i </a>&nbsp;&nbsp;";

                            echo "</h2>";
                        }
                    } catch (Exception $e) {
                        print "<h3 style=\"color:red\">Error en el query: " . $dbc->error . "</h3>";
                    } finally {
                        $dbc->close();
                    }

                    ?>


                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="crearSeccion" tabindex="-1" aria-labelledby="crearSeccionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="crearSeccionLabel">Crear seccion</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label mt-4">Email address</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" class="form-label mt-4">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelect1" class="form-label mt-4">Example select</label>
                                        <select class="form-select" id="exampleSelect1">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelect1" class="form-label mt-4">Example disabled select</label>
                                        <select class="form-select" id="exampleDisabledSelect1" disabled="">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelect2" class="form-label mt-4">Example multiple select</label>
                                        <select multiple="" class="form-select" id="exampleSelect2">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea" class="form-label mt-4">Example textarea</label>
                                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="formFile" class="form-label mt-4">Default file input example</label>
                                        <input class="form-control" type="file" id="formFile">
                                    </div>
                                    <fieldset class="form-group">
                                        <legend class="mt-4">Radio buttons</legend>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                            <label class="form-check-label" for="optionsRadios1">
                                                Option one is this and that—be sure to include why it's great
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                            <label class="form-check-label" for="optionsRadios2">
                                                Option two can be something else and selecting it will deselect option one
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled="">
                                            <label class="form-check-label" for="optionsRadios3">
                                                Option three is disabled
                                            </label>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <legend class="mt-4">Checkboxes</legend>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Default checkbox
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked="">
                                            <label class="form-check-label" for="flexCheckChecked">
                                                Checked checkbox
                                            </label>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <legend class="mt-4">Switches</legend>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <legend class="mt-4">Ranges</legend>
                                        <label for="customRange1" class="form-label">Example range</label>
                                        <input type="range" class="form-range" id="customRange1">
                                        <label for="disabledRange" class="form-label">Disabled range</label>
                                        <input type="range" class="form-range" id="disabledRange" disabled="">
                                        <label for="customRange3" class="form-label">Example range</label>
                                        <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                                    </fieldset>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
    </section>


    <!-- end courses section -->

    <!-- footer section -->

    <?php include("layouts/footer.php") ?>

    <!-- end footer section -->


</body>

</html>