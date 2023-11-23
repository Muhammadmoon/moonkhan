<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for table */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th, table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        table th {
            background-color: #f2f2f2;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f2f2f2;
        }
        
        a.view-link {
            color: #007bff;
            text-decoration: none;
        }
        
        a.view-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mt-4 mb-4">Student Data</h2>
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th>SNo</th>
                <th>Name</th>
                <th>Class</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
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
            $sql = "SELECT student_id, name, class FROM students"; // Update column names here
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Error executing the query: " . $conn->error;
            } elseif ($result->num_rows > 0) {
                $counter = 1;
                // Loop through each row of student data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["class"] . "</td>";
                    echo '<td><a class="view-link" href="view.php?id=' . $row["student_id"] . '">View</a></td>'; // Include student_id in URL
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='4'>No records found</td></tr>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
