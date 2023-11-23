<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Collect form data
    $name = $_POST['name'];
    $fatherName = $_POST['fatherName'];
    $age = $_POST['age'];
    $class = $_POST['class'];
    $address = $_POST['address'];

    // File upload handling
    $targetDirectory = "uploads/"; // Change this to your desired directory
    $targetFile = $targetDirectory . basename($_FILES["profilePicture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size (optional)
    if ($_FILES["profilePicture"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (optional)
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["profilePicture"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $subjectMarks = array();
    for ($i = 1; $i <= 8; $i++) {
        $subject = $_POST['subject' . $i];
        $marks = $_POST['marks' . $i];
        $totalMarks = $_POST['totalMarks' . $i]; // Fetch total marks

        $subjectMarks[] = array('subject' => $subject, 'marks' => $marks, 'total_marks' => $totalMarks);
    }
    
    // Insert student information into the database
    $sqlStudentInfo = "INSERT INTO students (name, father_name, age, class, address, profile_picture)
                        VALUES ('$name', '$fatherName', '$age', '$class', '$address', '$targetFile')";

    if ($conn->query($sqlStudentInfo) !== TRUE) {
        echo "Error: " . $sqlStudentInfo . "<br>" . $conn->error;
    }

    // Get the inserted student ID
    $studentID = $conn->insert_id;

    // Insert subject names and marks into the database
    foreach ($subjectMarks as $subjectMark) {
        $subject = $subjectMark['subject'];
        $marks = $subjectMark['marks'];
        $totalMarks = $subjectMark['total_marks']; // Retrieve total marks

        $sqlSubjectMarks = "INSERT INTO subject_marks (student_id, subject_name, obtained_marks, total_marks)
                            VALUES ('$studentID', '$subject', '$marks', '$totalMarks')";

        if ($conn->query($sqlSubjectMarks) !== TRUE) {
            echo "Error: " . $sqlSubjectMarks . "<br>" . $conn->error;
        }
    }
    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Root English School</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="mb-4">Student Information</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
           <div class="form-row">
        <div class="form-group col-md-6">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" >

        </div>
        <div class="form-group col-md-6">
          <label for="fatherName">Father's Name</label>
          <input type="text" class="form-control" id="fatherName" name="fatherName" placeholder="Enter father's name">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="age">Age</label>
          <input type="number" class="form-control" id="age" name="age" placeholder="Enter your age">
        </div>
        <div class="form-group col-md-6">
          <label for="class">Class</label>
          <input type="text" class="form-control" id="class" name="class" placeholder="Enter your class">
        </div>
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"></textarea>
      </div>

      <div class="form-group">
                <label for="profilePicture">Upload Profile Picture</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="profilePicture" name="profilePicture">
                    <label class="custom-file-label" for="profilePicture">Choose File</label>
                </div>
            </div>
      
      <hr>

      <?php for ($i = 1; $i <= 8; $i++) { ?>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="subject<?php echo $i; ?>">Subject <?php echo $i; ?> Name</label>
            <input type="text" class="form-control" id="subject<?php echo $i; ?>" name="subject<?php echo $i; ?>" placeholder="Subject <?php echo $i; ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="marks<?php echo $i; ?>">Obtained</label>
            <input type="number" class="form-control" id="marks<?php echo $i; ?>" name="marks<?php echo $i; ?>" placeholder="">
        </div>
        <div class="form-group col-md-3">
            <label for="totalMarks<?php echo $i; ?>">Total</label>
            <input type="number" class="form-control" id="totalMarks<?php echo $i; ?>" name="totalMarks<?php echo $i; ?>" placeholder="">
        </div>
    </div>
<?php } ?>

<button type="submit" class="btn btn-primary" name="submit">Submit</button>
<a class="btn btn-primary" href="list.php">View</a>
    </form>
  </div>
</div>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
