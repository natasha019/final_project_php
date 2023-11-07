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
        $query = "SELECT course_id, title, section_id, credits FROM course NATURAL JOIN section";

        try {
            if ($result = $dbc->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    print "<p>" . $row['course_id'] . " --> " . $row['title'] . " --> " . $row['credits'] . "</p>";
                }
            }

            function insertar_cursos($student_id, $course_id, $section_id, $timestamp, $status)
            {
                global $dbc; // usamos la coneccion de la db 

                $sql = "INSERT INTO enrollment (student_id, course_id, section_id, timestamp, status) VALUES (?, ?, ?, ?, ?)";
                $stmt = $dbc->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sssss", $student_id, $course_id, $section_id, $timestamp, $status);

                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

                $stmt->close(); // Close the statement, not the connection
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
