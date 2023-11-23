<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <link rel="stylesheet" href="view.css">
    <style>
        /* Your additional CSS styles here */
        /* Basic table styles */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table img {
            max-width: 100px;
            height: auto;
        }

        .passed {
            color: green;
            font-weight: bold;
        }

        .failed {
            color: red;
            font-weight: bold;
        }

        /* Define styles for delete button */
a.delete-button {
  display: inline-block;
  padding: 8px 16px;
  text-align: center;
  text-decoration: none;
  color: #fff;
  background-color: #f44336;
  border-radius: 4px;
  transition: background-color 0.3s;
}

a.delete-button:hover {
  background-color: #d32f2f;
}

/* Define styles for add button */
a.add-button {
  display: inline-block;
  padding: 8px 16px;
  text-align: center;
  text-decoration: none;
  color: #fff;
  background-color: #4caf50;
  border-radius: 4px;
  transition: background-color 0.3s;
}

a.add-button:hover {
  background-color: #388e3c;
}


        /* Other styles */
        /* Add your custom styles here */
    </style>

<style media="print">
        /* Print-specific styles */
        body {
            font-size: 12px; /* Adjust font size for printing */
        }
        .print-button {
            display: none; /* Hide print button in print view */
        }
        /* Add additional print styles as needed */
    </style>
</head>
<body>

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

// Fetch student records from the database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each row of student data
    while ($row = $result->fetch_assoc()) {
        
        echo '<tr>
                <td><a class="delete-button" href="delete.php?student_id=' . $row['student_id'] . '">Delete</a></td>
                <td><a class="add-button" href="index.php?student_id=' . $row['student_id'] . '">Add</a></td>
            </tr>';


        
        // Display table for each student
        echo '<table border="2" >
        <tr>
                        <td colspan="5" ></td>
                    </tr>
        <thead>
        <th colspan="8" style="text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center;">
                <div class="circleimg" style="width: 100px; height: 100px; overflow: hidden; border-radius: 50%; margin-right: 10px;">
                    <img src="logo.jpeg" alt="School Logo" style="width: 100%; height: auto;">
                </div>
                <span style="font-size: 30px;">The Roots English School</span>
                <div class="circleimg" style="width: 100px; height: 100px; overflow: hidden; border-radius: 50%; margin-left: 10px;">
                <img src="' . $row["profile_picture"] . '" alt="Profile Picture"> style="width: 100%; height: auto;">
                </div>
            </div>
        </th>
    </thead>
    
                <tbody>
                <div class="data">
                
                    <tr >
                        <td colspan="5">Name: ' . $row['name'] . '</td>
                    </tr>

                    <tr>
                       <td  colspan="5">Father: ' . $row['father_name'] . '</td>
                    </tr>

                    <tr>
                        <td colspan="5">Age: ' . $row['age'] . '</td>
                    </tr>

                    <tr>
                        <td colspan="5">Class: ' . $row['class'] . '</td>
                    </tr>

                    <tr>
                        <td colspan="5">Address: ' . $row['address'] . '</td>
                    </tr>

                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                

                    
                </div>
                    
                    <tr >
                        <th>Subject Name</th>
                        <th>Obtained Marks</th>
                        <th>Total Marks</th>
                        <th>Percentage</th>
                        <th>Result</th>
                    </tr>';

        // Fetch subject-wise marks for the current student
        $student_id = $row['student_id'];
        $marks_sql = "SELECT * FROM subject_marks WHERE student_id = $student_id";
        $marks_result = $conn->query($marks_sql);

        $total_obtained_marks = 0;
        $total_marks = 0;

        if ($marks_result->num_rows > 0) {
            while ($marks_row = $marks_result->fetch_assoc()) {
                $total_obtained_marks += $marks_row['obtained_marks'];
                $total_marks += $marks_row['total_marks']; // Use the actual total marks from the database
        
                $percentage = 0; // Initialize percentage to handle division by zero
        
                // Check if total marks is not zero to avoid division by zero error
                if ($marks_row['total_marks'] != 0) {
                    $percentage = ($marks_row['obtained_marks'] / $marks_row['total_marks']) * 100;
                }
        
                // Determine the status (Pass/Fail) based on the obtained marks percentage
                $status = '';
if ($marks_row['total_marks'] == 0 || $marks_row['obtained_marks'] == 0) {
    $status = '--';
} else {
    $percentage = ($marks_row['obtained_marks'] / $marks_row['total_marks']) * 100;
    $status = ($percentage < 40) ? 'Failed' : 'Passed';
}

$class = ($status === 'Passed') ? 'passed' : 'failed';

// Display subject-wise marks for the current student
echo '<tr>
    <td>' . $marks_row['subject_name'] . '</td>
    <td>' . ($marks_row['obtained_marks'] == 0 ? '--' : $marks_row['obtained_marks']) . '</td>
    <td>' . ($marks_row['total_marks'] == 0 ? '--' : $marks_row['total_marks']) . '</td>
    <td>' . ($status === '--' ? '--' : number_format($percentage, 2) . '%') . '</td>
    <td class="' . $class . '">' . $status . '</td>
</tr>';
    }
            // Calculate the total percentage for all subjects
            $total_percentage = 0; // Initialize total percentage to handle division by zero

            // Check if total marks is not zero to avoid division by zero error
            if ($total_marks != 0) {
                $total_percentage = ($total_obtained_marks / $total_marks) * 100;
            }
            
            $total_status = ($total_percentage < 40) ? 'Failed' : 'Passed';
            $total_class = ($total_status === 'Passed') ? 'passed' : 'failed';
            
            // Determine the grade based on total percentage
            $grade = '';
            if ($total_percentage >= 90) {
                $grade = 'A1';
            } elseif ($total_percentage >= 80) {
                $grade = 'A';
            } elseif ($total_percentage >= 70) {
                $grade = 'B';
            } elseif ($total_percentage >= 60) {
                $grade = 'C';
            } elseif ($total_percentage >= 50) {
                $grade = 'D';
            } else {
                $grade = 'Fail';
            }
            
            echo '<tr>
                    <td><strong>Total:</strong></td>
                    <td>' . $total_obtained_marks . '</td>
                    <td>' . $total_marks . '</td>
                    <td>' . number_format($total_percentage, 2) . '%</td>
                    <td class="' . $total_class . '">' . $total_status . ' (' . $grade . ')</td>
                </tr>';
        } else {
            echo "<tr><td colspan='5'>No subjects found for this student</td></tr>";
        }

        echo '</tbody></table>';
    }
} else {
    echo "No records found";
}

// Close the database connection
$conn->close();
?>
<button class="print-button" onclick="printTable()">Print Table</button>

<script>
function printTable() {
    window.print();
}
</script>

</body>
</html>
