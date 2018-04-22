<?php
class Student{
    //Direct input
    protected $id;
    protected $fName;
    protected $surname;
    protected $bDate;
    //Calculated
    protected $taken;
    protected $failed;
    protected $GPA;
    protected $status;

    function __construct($id, $fName, $surname, $bDate, $new){
        $this->id = $id;
        $this->fName = $fName;
        $this->surname = $surname;
        $this->bDate = $bDate;

        if($new == false){
          $returnArray = $this->calcGPA($id);
          $this->GPA = $returnArray[0];
          $this->taken = $returnArray[1];
          $this->failed = $returnArray[2];

          switch($this->GPA){
              case $this->GPA >= 0 && $this->GPA < 2:
                  $this->status = 'Unsatisfactory';
                  break;
              case $this->GPA >= 2 && $this->GPA < 3:
                  $this->status = 'Satisfactory';
                  break;
              case $this->GPA >= 3 && $this->GPA < 4:
                  $this->status = 'Honour';
                  break;
              case $this->GPA >= 4:
                  $this->status = 'High  honour';
                  break;
          }

          $_SESSION["sessionTableData"][] = get_object_vars($this);
          $_SESSION["studCount"]++;
        }

        if($new == true){
          $stud = array_slice(get_object_vars($this), 0, 4);
          $this->newStudent($stud);
        }

    } //End function 'construct'

    function pullStudents(){
        $fileStud = fopen("students.csv", 'r') or die ('Failed!');
        while (!feof($fileStud)){ //somehow endless loop
            $stud = fgetcsv($fileStud);
            if(is_array($stud)){
                $newStudent = new Student($stud[0], $stud[1], $stud[2], $stud[3], false);
            }
        }
    } //End function 'pullStudents'

    function calcGPA($id){
        $grades = [];
        $credits = [];
        $taken = 0;
        $failed = 0;
        $fStudTakes = fopen('studTakes.csv', 'r') or die ('Failed');
        while(!feof($fStudTakes)){
            $STRow = fgetcsv($fStudTakes);
            if($STRow[0] == $id){
                $taken++;
                switch($STRow[3]){
                    case 'A':
                        $grades[] = 5;
                        break;
                    case 'B':
                        $grades[] = 4;
                        break;
                    case 'C':
                        $grades[] = 3;
                        break;
                    case 'D':
                        $grades[] = 2;
                        break;
                    case 'E':
                        $grades[] = 1;
                        break;
                    case 'F':
                        $grades[] = 0;
                        $failed++;
                }
                switch($STRow[1]){
                    case "IMT2671":
                        $credits[] = 10;
                        break;
                    case "IMT3851":
                        $credits[] = 10;
                        break;
                    case "IMT1004":
                        $credits[] = 10;
                }
            }
        } //End while

        if(feof($fStudTakes)){ //This guy calculates
            $g1 = 0;
            $g2 = 0;
            for($i = 0; $i <= count($grades)-1; $i++){
                $x = $grades[$i];
                for($j = 0; $j <= count($credits)-1; $j++){
                    $y = $credits[$j];
                }
                $g1 = $g1+($x*$y);
                $g2 = $g2+$y;
            }
            $GPA = $g1/$g2;
            $returnArray = [$GPA, $taken, $failed];
            return $returnArray;
        }
    } //End function 'calcGPA'

    function newStudent($stud){
        $fh = fopen('students.csv', 'a') or die ('Failed!');
        $text = implode(",", $stud)."\n";
        fwrite ($fh, $text) or die ("Failed!");
    } //End function newStudent
} //End class 'Student'
?>
