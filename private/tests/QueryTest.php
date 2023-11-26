<?php
 require_once('private/db_credentials.php');
 require_once("private/database.php");
 require_once("private/query_functions.php");
 require_once("private/validation_functions.php");
 require_once("private/query_Functions/subject.php");
 require_once("private/query_Functions/page.php");
 require_once("private/query_Functions/admin.php");

 use PHPUnit\Framework\TestCase;

 class QueryTest extends TestCase {
    
    public function testInsertAndDeleteSubject(): void
    {
        $subjectQueries = new Subject(["Test" => true]);  
         
        // Insert subject
        $subject = ["menu_name" => "Global Topic", "position" => 1, "visible" => true];
        $subjectQueries->insert_subject($subject);
        $subject_id = $subjectQueries->getIdByLastQuery();
        $this->assertEquals(1, $subjectQueries->count_subjects());

        // Delete subject
        $isDeleted = $subjectQueries->delete_subject($subject_id);
        $this->assertEquals(true, $isDeleted);
        $this->assertEquals(0, $subjectQueries->count_subjects());
        //disconnectDatabase();
    }

    public function testFindAllVisibleSubject(): void
    {
        $subjectQueries = new Subject(["Test" => true]);  
        
        // Insert subjects #1
        $subject1 = ["menu_name" => "Global Topic", "position" => 1, "visible" => 1];
        $subjectQueries->insert_subject($subject1);
        echo "inserted first \n";
        $subject_id1 = $subjectQueries->getIdByLastQuery();
        // Insert subjects #2 -> not visible
        $subject2 = ["menu_name" => "Kredit Card", "position" => 2, "visible" => 0];
        $subjectQueries->insert_subject($subject2);
        $subject_id2 = $subjectQueries->getIdByLastQuery();
        echo "inserted 2 \n";
        // Insert subjects #3
        $subject3 = ["menu_name" => "About Us", "position" => 3, "visible" => 1];
        $subjectQueries->insert_subject($subject3);
        $subject_id3 = $subjectQueries->getIdByLastQuery();
        echo "inserted 3 \n";

        echo "call find all subjects \n";
        $subject_set = $subjectQueries->find_all_subjects(["visibile" => true]);
        $subjects = mysqli_fetch_assoc($subject_set);
       

        // get all subjects -> loop
        $allSubjects = [];
        while($subject = mysqli_fetch_assoc($subject_set)) {
          array_push($allSubjects,$subject);
          }
          print_r($allSubjects);
        $this->assertEquals(2, count($allSubjects));

        // delete all created subjects
        $subjectQueries->delete_subject($subject_id1);
        $subjectQueries->delete_subject($subject_id2);
        $subjectQueries->delete_subject($subject_id3);
        $this->assertEquals(0, $subjectQueries->count_subjects());


    }

    // ONLY VISIBLE SUbjects
    // moving positions

}

?>