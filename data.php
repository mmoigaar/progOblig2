<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <link rel="stylesheet" href="css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
  <header>
    <h1>HEADLINE</h1>
    <div class="nav">
      <a href="students.php"><p>STUDENTS</p></a>
      <a href="courses.php"><p>COURSES</p></a>
      <a href=""><p>UPLOAD</p></a>
    </div>
  </header>
  <div id="container">
    <h2>Upload new data from a CSV file</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="file" class="button"/>
      <input type="submit" value="Upload File" />
    </form>
    <div>
      <?php

      // Check if uploaded file exists
      if(isset($_FILES['file'])){
        $file = $_FILES['file'];

        // Convert file name to array and get its last element
        $file_ext = explode('.', $file['name']);
        $file_ext = strtolower(end($file_ext));

        // Check if last elemnent of the array equals 'csv'
        if ($file_ext === 'csv') {
          validate($file['tmp_name']);
        }
      }

      function validate($input){

        //Predefined values
        $newStudent = null;
        $existingStudent = null;
        $courseCheck = null;
        $STCheck = true;

        //Opens all required files
        $file_students = fopen("students.csv", 'r') or die ('Failed!');
        $file_courses = fopen("courses.csv", 'r') or die ('Failed!');
        $file_studTakes = fopen("studTakes.csv", 'r') or die ('Failed!');
        $file_input = fopen($input, 'r') or die ('Failed!');

        // Distribute input data between different arrays to compare values against their respective target files, and converts date to unix timestamp
        $input_data = fgetcsv($file_input);

        $student_array = [$input_data[0], $input_data[1], $input_data[2], strval(strtotime($input_data[3]))];
        $course_array = [$input_data[4], $input_data[6], $input_data[8]];
        $newStudTakes = [$input_data[0], $course_array[0], $input_data[5], $input_data[9]];

        // Compares input data against students.csv
        while (!feof($file_students)){
          $student = fgetcsv($file_students);

          // Checks if the student already exists
          if ($student === $student_array){
            $existingStudent = true;
            $newStudent = false;
            echo "Existing student detected (".$student_array[0].").<br>";
            break;
          }

          // Checks if the student ID exists, but other values are incorrect
          if ($student[0] === $student_array[0] &&
             ($student[1] != $student_array[1] ||
              $student[2] != $student_array[2] ||
              $student[3] != $student_array[3])){
            $newStudent = false;
            echo "The personal data recorded for this student (".$student[0].") does not match input data.";
            break;
          }

          // Checks if input contains a new student ID
          else if ($student[0] != $student_array[0]){
            $newStudent = true;
          }
          else{
            $newStudent = false;
          }

          // Initiates class_student if end of file is reached and $newStudent returns true
          if(feof($file_students) && $newStudent == true){
            echo "New student detected";
            require_once "class_student.php";
            $newStudIns = new Student($student_array[0], $student_array[1], $student_array[2], $student_array[3], true);
          }
        } // End while

        // Compares input data against courses.csv
        while (!feof($file_courses)){
          $courses = fgetcsv($file_courses);

          // Returns $courseCheck = true if $course_array matches any of the courses in courses.csv
          if (($courses[0] === $course_array[0]) &&
              ($courses[3] === $course_array[1]) &&
              ($courses[5] === $course_array[2])){

            $courseCheck = true;
            echo "<br>Course match: ".$courses[0].", ".$courses[1]."<br>";
            break;
          } else{
            $courseCheck = false;
          }

          //
          if(feof($file_courses) && $courseCheck == false){
            echo "Course not found. Make sure your input file has correct values.";
          }
        } // End while

        // Why am I not implementing courseCheck here?
        while (!feof($file_studTakes)){
          $studTakes = fgetcsv($file_studTakes);
          if ($newStudTakes == $studTakes){
            $STCheck = false;
            echo "Error: duplicate registration attempt. Course completion has already been registered.";
          }
          if (feof($file_studTakes) && $STCheck == true){
            require_once "class_studTakes.php";
            $newStudTakesIns = new StudTakes($newStudTakes[0], $newStudTakes[1], $newStudTakes[2], $newStudTakes[3]);
            echo $newStudTakesIns;
            $newStudTakesIns->newStudTakes();
          }
        } // End while
      } // End function 'validate'
      ?>
    </div>
  </div>


</body>
</html>
