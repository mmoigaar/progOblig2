<?php

class Course{
    //Direct input
    protected $id;
    protected $name;
    protected $year;
    protected $semester;
    protected $instructor;
    protected $credits;
    //Calculated
    protected $numOfStudents;
    protected $passed;
    protected $failed;
    protected $avgGrade;

    function __construct($id, $name, $year, $semester, $instructor, $credits){
        $this->id = $id;
        $this->name = $name;
        $this ->year = $year;
        $this ->semester = $semester;
        $this ->instructor = $instructor;
        $this ->credits = $credits;

        $returnArray = $this->calcThings($id);

        $this->numOfStudents = $returnArray[0];
        $this->passed = $returnArray[1];
        $this->failed = $returnArray[2];
        $this->avgGrade = $returnArray[3];

        $_SESSION["sessionTableData"][] = get_object_vars($this);
        $_SESSION["courseCount"]++;
    }

    function pullCourses(){
        $fCourses = fopen("courses.csv", 'r') or die ('Failed!');
        while (!feof($fCourses)){
            $course = fgetcsv($fCourses);
            if(is_array($course)){
                $newCourse = new Course($course[0], $course[1], $course[2], $course[3], $course[4], $course[5]);
                //echo $newCourse;
            }
        }
    } //End function pullCourses

    function calcThings($id){
        $numOfStudents = 0;
        $grades = 0;
        $passed = 0;
        $failed = 0;
        $avgGrade = 0;
        $fStudTakes = fopen('studTakes.csv', 'r') or die ('Failed');
        while(!feof($fStudTakes)){
            $STRow = fgetcsv($fStudTakes);
            if($STRow[1] == $id){
                $numOfStudents++;
                $passed++;
                switch($STRow[3]){
                    case 'A':
                        $grades += 5;
                        break;
                    case 'B':
                        $grades += 4;
                        break;
                    case 'C':
                        $grades += 3;
                        break;
                    case 'D':
                        $grades += 2;
                        break;
                    case 'E':
                        $grades += 1;
                        break;
                    case 'F':
                        $passed--;
                        $failed++;
                }
            }
        } //End while
        if(feof($fStudTakes)){
            $avgGrade = $grades/$numOfStudents;
            $returnArray = [$numOfStudents, $passed, $failed, $avgGrade];
            return $returnArray;
        }
    } //End function calcThings
}
?>
