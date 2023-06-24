<?php

function find_all_subjects(){
    global $db;
    
    $sql = "SELECT * FROM subjects ORDER BY position ASC";
    $result = mysqli_execute_query($db, $sql);
    confirm_result_set($result); // check if we get data back
    return $result;
}

function find_subject_by_id($id){
    global $db;

    $sql = "SELECT * FROM subjects WHERE id=?";
    $query = mysqli_execute_query($db, $sql,[$id]);
    confirm_result_set($query);
    $result = mysqli_fetch_assoc($query);
    return $result;
}

function validate_subject($subject) {
    $errors = [];

    // menu_name
    if(is_blank($subject['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif(!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be between 2 and 255 characters.";
    }

    // position
    // Make sure we are working with an integer
    $postion_int = (int) $subject['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $subject['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }

    return $errors;
  }

function insert_subject($subject){
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)){
        return $errors;
    }

    $sql = "INSERT INTO subjects (menu_name, position, visible) VALUES (?,?,?)";

    $result = mysqli_execute_query($db, $sql, [$subject["menu_name"],$subject["position"],$subject["visible"]]);
    if($result)
    {
        return true;
    }
    else
    {
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function update_subject($subject){
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE subjects SET menu_name=?, position=?, visible=? WHERE id=? LIMIT 1";

    $result = mysqli_execute_query($db, $sql, [$subject["menu_name"],$subject["position"],$subject["position"],$subject["id"]]);

    // For UPDATE statement, result is true/false
    if($result){
      return true;
    }
    else
    {   // UPDATE FAILED
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function delete_subject($id){
    global $db;

    $sql = "DELETE FROM subjects WHERE id=? LIMIT 1";

    $result = mysqli_execute_query($db, $sql, [$id]);

    // For DELETE statement, result is true/false
    if($result){
     return true;
    }
    else{
      //delete failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}


function find_all_pages(){
    global $db;
    
    $sql = "SELECT * FROM pages ORDER BY subject_id ASC, position ASC";
    $result = mysqli_execute_query($db, $sql);
    confirm_result_set($result);
    return $result;

}


function find_pages_by_id($id){
    global $db;

    $sql = "SELECT * FROM pages WHERE id=?";
    $query = mysqli_execute_query($db, $sql, [$id]);
    confirm_result_set($query);
    $result = mysqli_fetch_assoc($query);
    return $result;
}

function insert_page($page){
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }


    $sql = "INSERT INTO pages (menu_name, position, visible) VALUES (?,?,?)";

    $result = mysqli_execute_query($db, $sql, [$page["menu_name"],$page["position"],$page["visible"]]);
    if($result)
    {
     return true;   
    }
    else{
      //insert failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}


function update_page($page){
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE pages SET menu_name=?, position=?, visible=? WHERE id=? LIMIT 1";

    $result = mysqli_execute_query($db, $sql, [$page["menu_name"],$page["position"],$page["position"],$page["id"]]);
    if($result)
    {
     return true;   
    }
    else{
      //update failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

function delete_page($id){
    global $db;

    $sql = "DELETE FROM pages WHERE id=? LIMIT 1";

    $result = mysqli_execute_query($db, $sql, [$id]);
    if($result)
    {
     return true;   
    }
    else{
      //update failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
}

function validate_page($page) {
    $errors = [];

    // // subject_id
    // if(is_blank($page["subject_id"])){
    //     $errors[] = "Subject cannot be blank";
    // }

    // menu_name
    if(is_blank($page['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be between 2 and 255 characters.";
    }
    $current_id = $page["id"] ?? "0";
    if(!has_unique_page_menu_name($page["menu_name"], $current_id )){
        $errors[] = "Menu name must be unique";
    }


    // position
    // Make sure we are working with an integer
    $postion_int = (int) $page['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }

    // content
    if(is_blank($page['content'])) {
        $errors[] = "Name cannot be blank.";
      } elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
      }

    return $errors;
  }


?>