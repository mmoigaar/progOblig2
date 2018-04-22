<?php
class StudTakes{
    protected $stud_id;
    protected $course_id;
    protected $course_year;
    protected $grade;
        
    function __construct($stud_id, $course_id, $course_year, $grade){ 
        $this->stud_id = $stud_id;
        $this->course_id = $course_id;
        $this->course_year = $course_year;
        $this->grade = $grade;   
    }
    function __set ($stud_id, $value){
        switch($stud_id){
            case "stud_id":
                $this->stud_id = $value;
                break;
        
            case "course_id":
                $this->course_id = $value;
                break;
        
            case "course_year":
                $this->$course_year = $value;
                break;
       
            case "grade":
                $this->grade = $value;
                break;
        
        default:
                echo $this->stud_id . "not found <br/>";
        } 
        echo "Set " . $stud_id . " to " . $value . "<br/>";
    }
    
    public function newStudTakes(){
        
        $fh = fopen('studTakes.csv', 'a') or die ('Failed!');
        $text = implode(",", get_object_vars($this))."\n";
        fwrite ($fh, $text) or die ("Failed!");

        echo "<br>Course completion registered.";
    }
    
    function __toString(){
        return "<br>Course completion registered for student with id: ". $this->stud_id .". <br>Student completed: ". $this->course_id ."<br>Year: ". $this->course_year ."<br>Grade: ". $this->grade ."<br>";
    }
}
?>