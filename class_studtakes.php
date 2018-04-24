<?php
class StudTakes{
  protected $stud_id;
  protected $course_id;
  protected $course_year;
  protected $grade;

  // This entire thing probably needs a revamp.

  function __construct($stud_id, $course_id, $course_year, $grade){
      $this->stud_id = $stud_id;
      $this->course_id = $course_id;
      $this->course_year = $course_year;
      $this->grade = $grade;
  }

  public function newStudTakes(){
    $fh = fopen('studTakes.csv', 'a') or die ('Failed!');
    $text = implode(",", get_object_vars($this))."\n";
    fwrite ($fh, $text) or die ("Failed!");
  }

  function __toString(){
    return "<br>Course completion registered for student with id: ". $this->stud_id .". <br>Student completed: ". $this->course_id ."<br>Year: ". $this->course_year ."<br>Grade: ". $this->grade ."<br>";
  }
}
?>
