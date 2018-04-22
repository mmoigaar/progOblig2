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


<?php
  include 'class_validation.php';
  include 'class_student.php';

    //$validate = new Validation();
    //$validate->checkDuplicate();

    //Does this thing accept multiple input lines?
?>
</body>
</html>
