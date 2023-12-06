<?php $titulo = "";

$servername = '136.145.29.193';
$dbname = 'natramri_db';
$username = 'natramri';
$password = 'nat84023';

// $servername = 'localhost';
// $dbname = 'matricula';
// $username = 'root';
// $password = '';

$dbc = new mysqli($servername, $username, $password, $dbname);
if ($dbc->connect_error) {
    die("<p>La conexion al servidor fallo. Error: " . $dbc->connect_error . " </p>");
}
$dbc->query("SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="php.css">
</head>

<body>
    <div>
        <?php ?>
    </div>
</body>

</html>