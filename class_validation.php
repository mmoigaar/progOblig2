<?php
class Validation{
    private $newStudent = true;
    private $existingStudent = false;
    private $courseCheck = false;
    private $STCheck = true;

    //Put attributes into function as variables instead, probably.

    function validate($input){

        $newStudent = true;
        $existingStudent = false;
        $courseCheck = false;
        $STCheck = true;


        $fStudents = fopen("students.csv", 'r') or die ('Failed!');
        $fCourses = fopen("courses.csv", 'r') or die ('Failed!');
        $fStudTakes = fopen("studTakes.csv", 'r') or die ('Failed!');
        $fInput = fopen($input, 'r') or die ('Failed!');

        $input_data = fgetcsv($fInput);
        $studArray = [$input_data[0], $input_data[1], $input_data[2], strtotime($input_data[3])];
        $courseArray = [$input_data[4], $input_data[6], $input_data[8]];
        $newStudTakes = [$input_data[0], $courseArray[0], $input_data[5], $input_data[9]];

        while (!feof($fStudents)){
            $student = fgetcsv($fStudents);

            if ($student == $studArray){
                $this->existingStudent = true;
                $this->newStudent = false;
                echo "Existing student detected<br>";
                break;
            } else if (!isset($student) || $student[0] != $studArray[1]){
                $this->newStudent = true;
            } else {
                $this->newStudent = false;
                echo "Existing student ID. Values do not match.ss";
                break;
            }
            if(feof($fStudents) && $this->newStudent == true){
                echo "New student detected";
                require_once "class_student.php";
                $newStudIns = new Student($studArray[0], $studArray[1], $studArray[2], $studArray[3]);
                echo $newStudIns;
                $newStudIns->newStudent();
            }
        }
        while (!feof($fCourses)){
            $courses = fgetcsv($fCourses);
            if (($courses[0] == $courseArray[0])
             && ($courses[3] == $courseArray[1])
             && ($courses[5] == $courseArray[2])){
                $this->courseCheck = true;
                echo "<br>Course match: \"".$courses[0].", ".$courses[1]."\".<br>";
                break;
            }
            if(feof($fCourses) && $this->courseCheck == false){
                echo "Course not found. Make sure your input file has correct values.";
            }
        }
        while (!feof($fStudTakes)){
            $studTakes = fgetcsv($fStudTakes);
            if ($newStudTakes == $studTakes){
                $this->STCheck = false;
                echo "Error: duplicate registration attempt. Course completion has already been registered.";
            }
            if (feof($fStudTakes) && $this->STCheck == true){
                require_once "class_studTakes.php";
                $newStudTakesIns = new StudTakes($newStudTakes[0], $newStudTakes[1], $newStudTakes[2], $newStudTakes[3]);
                echo $newStudTakesIns;
                $newStudTakesIns->newStudTakes();
            }
        }
    }
}

?>
