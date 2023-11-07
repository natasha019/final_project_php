<?php $titulo = ""; ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="php.css">
</head>

<body>
    <div>
        
        <?php
        include_once("db_info.php");
        $query = " SELECT * FROM course";

        try {
            if ($result = $dbc->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    print "<p>" . $row['course_id'] . " --> " . $row['title'] ." --> " . $row['credits']. "</p>";
                    
                }
            }
        } catch (Exception $e) {
            print "<h3 style=\"color:red\">Error en el query: " . $dbc->error . "</h3>";
        } finally {
            $dbc->close();
        }

        ?>
    </div>
</body>

</html>