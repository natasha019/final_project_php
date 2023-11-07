<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Insertar Cursos</title>

    <link rel="stylesheet" href="php.css" type="text/css" />

</head>

<body>

<div id="contenido">   

<h1>Insertar Cursos</h1>


<?php

include("db_info.php");



if(isset($_GET['student_id']) && is_numeric($_GET['student_id']))

{

    $query = "SELECT *

              FROM student

              WHERE student_id={$_GET['student_id']}";  

    //echo "<p>query: ".$query."</p>";

    if($result = $dbc->query($query))

    {

        if ($result->num_rows==1)

        {

            $row = $result->fetch_assoc();

            //mostrar datos en formulario

            print '<div>

            

            <form action="insertar_estudiante_de_honor.php" method="post">

            <table border=0>

            <tr>

              <td>Apellido Paterno: </td><td>

              <input type="text" name="apellidoP" id="apellidoP" value="' .$row['apellidoP'].'" required/></td>

            </tr>   

            <tr>

              <td>Apellido Materno: </td><td>

              <input type="text" name="apellidoM"  value="' .$row['apellidoM'].'" /></td>

            </tr>

            <td>Nombre: </td><td>

              <input type="text" name="nombre"  value="' .$row['nombre'].'" required/></td>

            </tr>

            <tr>

              <td>Email: </td><td>

              <input type="email" name="email"  value="'.$row['email'].'" required/></td>

            </tr>

            <tr>

              <td>Departamento: </td><td>

              <select name="deptoID" >';

          

            $query2 = "SELECT * FROM departamento";

            $result2 = $dbc->query($query2);

            while($row2 = $result2->fetch_assoc()) 

            {

                print "<option value=".$row2['depto_ID'];

                if ($row2['depto_ID']==$row['deptoID'])

                    print " selected >";

                else

                    print " >";

                print $row2['codigo']."</option>";

            }

            print '</select></td>

            </tr>

            <tr>

              <td>Promedio: </td><td>

              <input type="number" name="promedio" step=0.01 min=0.00 max=4.00 value="'. $row['promedio'].'" required/></td>

            </tr>

            <tr>

            

              <input type="hidden" name="estID" value="'.$_GET['estID'].'" />

            <td colspan=2>';

            print '<div style="text-align:center;"><input type="submit" name="editar" id="Editar" value="Editar" /></div></td>

            

            </tr>

            </table>

            </form>

            </div>';  

            

       }

       else

          print '<h3 style="color:red;">No se pudo traer la información del estudiante. Error:<br />' . $dbc->error . '</h3>';

    } 

    else

        print'<h3 style="color:red;">Error en el query: '.$dbc->error.'</h3>';

}

else if(isset($_POST['estID']) && is_numeric($_POST['estID']))

{

    //$nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

    //echo "<p>Nombre después htmlspecialchars: $nombre</p>";

    //echo "<p>Nombre antes strip_tags: ". $_POST['nombre']."</p>";

    $nombre = strip_tags($_POST['nombre']);

    //echo "<p>Nombre después strip_tags: $nombre</p>";

    $nombre = htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

    //echo "<p>Nombre después htmlspecialchars: $nombre</p>";

    $apellido_p = htmlspecialchars($_POST['apellidoP'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

    $apellido_m = htmlspecialchars($_POST['apellidoM'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

    $email = htmlspecialchars($_POST['email'], ENT_QUOTES | ENT_SUBSTITUTE,'UTF-8', false);

    $promedio = filter_input(INPUT_POST, 'promedio', FILTER_VALIDATE_FLOAT);

    if ($promedio <=0 or $promedio>4 or !is_numeric($promedio))

        $promedio = 0;

    $id_dept = filter_input(INPUT_POST, 'deptoID', FILTER_VALIDATE_INT);

    if ($id_dept >13 or $id_dept < 1 or !is_numeric($id_dept))

        $id_dept = 0;

 

    

    // include("db_info.php");

    //echo "<p>Conexión exitosa al servidor.</p>";

    

    $query = "INSERT INTO estudiantes (nombre, apellidoP, apellidoM, email, promedio, nombreDepto)
    VALUES ('$nombre', '$apellidoP', '$apellidoM', '$email', '$promedio', '$nombreDepto')";
    //echo "<p>update query: ".$query."</p>";

    

    if ($dbc->query($query) === TRUE)

        print '<h3>El estudiante ha sido actualizado exitosamente</h3>';

    else

        print '<h3 style="color:red;">No se pudo actualizar el estudiante porque:<br />'.$dbc->error.'</h3>';

}

else

   print '<h3 style="color:red;">Esta página ha sido accedida con error</h3>'; 

 

$dbc->close();

?>

<h3><a href="index.php"> Ver estudiantes </a></h3>

</div>

</body>

</html>