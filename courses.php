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
        session_start();
        $_SESSION["sessionTableData"] = array();
        $_SESSION["courseCount"] = 0;
        include 'class_course.php';
        Course::pullCourses();
        $data = $_SESSION['sessionTableData'];

        uasort($data, function($a, $b){
          return $b['numOfStudents'] <=> $a['numOfStudents'];
        }); //fuck yes I love my life
      ?>

      <div id="container">
        <div id="counterBox">
          <h2>There are currently <?php echo $_SESSION["courseCount"]; ?> courses listed</h2>
        </div>
        <table>
        <tr>
            <th><h3>Course code</h3></th>
            <th><h3>Course name</h3></th>
            <th><h3>Year</h3></th>
            <th><h3>Semester</h3></th>
            <th><h3>Instructor</h3></th>
            <th><h3>Credits</h3></th>
            <th><h3>Numer of students</h3></th>
            <th><h3>Passed</h3></th>
            <th><h3>Failed</h3></th>
            <th><h3>Average grade</h3></th>
        </tr>
        <?php
            tableData($data);
            function tableData($courses){
              foreach ($courses as $course){
                $course['avgGrade'] = round($course['avgGrade'], 2);
                echo "<tr>";
                foreach($course as $cellValue){
                    echo "<td><p>".$cellValue."</p></td>";
                }
              }
            }
            session_destroy();
        ?>
        </table>
      </div>
</body>
</html>
