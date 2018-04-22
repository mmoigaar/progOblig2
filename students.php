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
        $_SESSION["studCount"] = 0;
        include 'class_student.php';
        Student::pullStudents();
        $data = $_SESSION['sessionTableData'];

        uasort($data, function($a, $b){
          return $b['GPA'] <=> $a['GPA'];
        }); //fuck yes I love my life
    ?>

    <div id="container">
      <div id="counterBox">
        <h2>There are currently <?php echo $_SESSION["studCount"]; ?> students listed</h2>
      </div>

      <table>
          <tr>
              <th><h3>Student number</h3></th>
              <th><h3>First name</h3></th>
              <th><h3>Last name</h3></th>
              <th><h3>Birth date</h3></th>
              <th><h3>Courses taken</h3></th>
              <th><h3>Courses failed</h3></th>
              <th><h3>GPA</h3></th>
              <th><h3>Status</h3></th>
          </tr>

      <?php
          tableData($data);
          function tableData($students){
            foreach ($students as $student){
              $student['bDate'] = gmdate('d-m-Y', $student['bDate']);
              $student['GPA'] = round($student['GPA'], 2);
              echo "<tr>";
              foreach($student as $cellValue){
                  echo "<td><p>".$cellValue."</p></td>";
              }
            }
          }
          session_destroy();
      /*

      TO DO LIST:
          * Fix existing validation
          * Add validation for accepted index values
          * Make program work with any number of  lines in input file.
          * Data page
          * Dropdown to change entire table based on year?

      */
      ?>
      </table>
    </div>
</body>
</html>
