<?php
// Establish connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "result";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Delete associated subject marks first
    $delete_marks_sql = "DELETE FROM subject_marks WHERE student_id = $student_id";
    if ($conn->query($delete_marks_sql) === TRUE) {
        // Proceed to delete the student record after deleting associated subject marks
        $delete_student_sql = "DELETE FROM students WHERE student_id = $student_id";
        if ($conn->query($delete_student_sql) === TRUE) {
            // Record deleted successfully, redirect to view.php
            header("Location: view.php");
            exit(); // Ensure that no other content is sent after redirection
        } else {
            echo "Error deleting student record: " . $conn->error;
        }
    } else {
        echo "Error deleting subject marks: " . $conn->error;
    }
} else {
    echo "No student ID provided for deletion.";
}

// Close the database connection
$conn->close();
?>
