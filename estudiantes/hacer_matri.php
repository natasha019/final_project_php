<?php
session_start();

include_once("../db_info.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['course_id'])) {
        $student_id = '000001111';
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $credits = $_POST['credits'];
        $capacity = $_POST['capacity'];

        // Determine the status based on capacity
        $status = ($capacity > 0) ? 0 : 1;

        // Insert the selected course into the database with the current timestamp
        $sql = "INSERT INTO enrollment (student_id, course_id, section_id, timestamp, status) 
                VALUES ('$student_id', '$course_id', '$section_id', NOW(), '$status')";
        if ($dbc->query($sql) === TRUE) {
            echo "Course added to the database successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $dbc->error;
        }
    }
}



$query = "SELECT course_id, title, section_id, credits, capacity FROM course NATURAL JOIN section ORDER BY course_id, section_id";
$result = $dbc->query($query);

?>


<table>
  <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["course_id"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["section_id"] . "</td>";
        echo "<td>" . $row["credits"] . "</td>";
        echo "<td>" . $row["capacity"] . "</td>";
        echo "<td>";
        echo "<form method='POST'>"; 
        echo "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'>";
        echo "<input type='hidden' name='title' value='" . $row["title"] . "'>";
        echo "<input type='hidden' name='section_id' value='" . $row["section_id"] . "'>";
        echo "<input type='hidden' name='credits' value='" . $row["credits"] . "'>";
        echo "<input type='hidden' name='capacity' value='" . $row["capacity"] . "'>";
        echo "<input type='submit' value='Add to Database'>";
        echo "</form>"; 
        echo "</td>";
        echo "</tr>";
    }
}
  ?>
</table>


<?php

$dbc->close();
?>
