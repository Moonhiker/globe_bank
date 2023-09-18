<?php
////////////////// SUBJECTS TABLE QUERIES ///////////////

function find_all_subjects(array $options=[]){
    global $db;

    $visible = $options["visible"] ?? false;
    $sql = "SELECT * FROM subjects ";
    if($visible)
    {
      $sql.=  "WHERE visible = true ";
    }
    $sql.= "ORDER BY position ASC";
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

    $result = mysqli_execute_query($db, $sql, [$subject["menu_name"],$subject["position"],$subject["visible"],$subject["id"]]);

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

function shift_subject_position(int $start_pos, int $end_pos, int $current_id = 0): mysqli_result|bool{
  global $db;
  
  if($start_pos == 0){ // new item, +1 to item greater than $end_pos
    $sql = "UPDATE subjects SET position= position + 1 WHERE position >= ? AND id != ?";
    $result = mysqli_execute_query($db, $sql,[$end_pos,$current_id]);
    return $result;
  }

  else if($end_pos == 0){ // delete item, -1 to item greater than $start_pos
    $sql = "UPDATE subjects SET position= position - 1 WHERE position >= ?";
    $result = mysqli_execute_query($db, $sql,[$start_pos]);
    return $result;
  }

  else if($start_pos < $end_pos){ // move item back, -1 from items beetween
    $sql = "UPDATE subjects SET position= position - 1 WHERE position >= ? AND position <= ? AND id != ?";
    $result = mysqli_execute_query($db, $sql,[$start_pos,$end_pos,$current_id]);
    return $result;
  }

  else if($start_pos > $end_pos){ // move item earlier, +1 from items beetween
    $sql = "UPDATE subjects SET position= position + 1 WHERE position <= ? AND position >= ? AND id != ?";
    $result = mysqli_execute_query($db, $sql,[$start_pos,$end_pos,$current_id]);
    return $result;
  }
}

function count_subjects(): int{
  global $db;

  $sql = "SELECT COUNT(id) FROM subjects"; // do not return date -> return quantity
  $result = mysqli_execute_query($db, $sql);
  confirm_result_set($result);
  $row = mysqli_fetch_row($result); // return one array element with the quantity
  mysqli_free_result($result);
  return $row[0];
}

////////////////// PAGES TABLE QUERIES ///////////////

function find_all_pages(){
    global $db;
    
    $sql = "SELECT * FROM pages ORDER BY subject_id ASC, position ASC";
    $result = mysqli_execute_query($db, $sql);
    confirm_result_set($result);
    return $result;

}


function find_pages_by_id($id, array $options=[] ){
    global $db;

    $visible = $options["visible"] ?? false;
    $sql = "SELECT * FROM pages WHERE id=? ";
    if($visible)
    {
      $sql.= "AND visible = true ";
    }
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


    $sql = "INSERT INTO pages (menu_name, position, visible, subject_id, content ) VALUES (?,?,?,?,?)";

    $result = mysqli_execute_query($db, $sql, [$page["menu_name"],$page["position"],$page["visible"],$page["subject_id"], $page["content"]]);
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

    $sql = "UPDATE pages SET menu_name=?, position=?, visible=?, content=?, subject_id=? WHERE id=? LIMIT 1";

    $result = mysqli_execute_query($db, $sql, [$page["menu_name"],$page["position"],$page["visible"],$page["content"],$page["subject_id"], $page["id"]]);
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

    // subject_id
    if(is_blank($page["subject_id"])){
        $errors[] = "Subject cannot be blank";
    }

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
        $errors[] = "Content cannot be blank.";
      }

    return $errors;
  }


  function find_pages_by_subject_id($subject_id, array $options=[]){
    global $db;

    $visible = $options["visible"] ?? false;
    $sql = "SELECT * FROM pages WHERE subject_id=? ";
    if($visible)
    {
      $sql.= "AND visible = true ";
    } 
    $sql .= "ORDER BY position ASC";
    $result = mysqli_execute_query($db, $sql, [$subject_id]);
    confirm_result_set($result);
    return $result;
  }

  function count_pages_by_subject_id(int $subject_id, array $options=[]): int{
    global $db;

    $sql = "SELECT COUNT(id) FROM pages WHERE subject_id=? "; // do not return date -> return quantity
    $result = mysqli_execute_query($db, $sql, [$subject_id]);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result); // return one array element with the quantity
    mysqli_free_result($result);
    return $row[0];
  }

////////////////// ADMINS TABLE QUERIES ///////////////

function find_all_admins(){
  global $db;
  
  $sql = "SELECT * FROM admins ORDER BY username ASC";
  $result = mysqli_execute_query($db, $sql);
  confirm_result_set($result);
  return $result;

}

function find_admin_by_id($id){
  global $db;

  $sql = "SELECT * FROM admins WHERE id=? ";
  $query = mysqli_execute_query($db, $sql, [$id]);
  confirm_result_set($query);
  $result = mysqli_fetch_assoc($query);
  return $result;
}

function find_admin_by_username($username){
  global $db;

  $sql = "SELECT * FROM admins WHERE username=? ";
  $query = mysqli_execute_query($db, $sql, [$username]);
  confirm_result_set($query);
  $result = mysqli_fetch_assoc($query);
  return $result;
}

function insert_admin($admin){
  global $db;

  $errors = validate_admin($admin);
  if(!empty($errors)) {
    return $errors;
  }

  $hashed_password = password_hash($admin["hashed_password"], PASSWORD_BCRYPT); 

  $sql = "INSERT INTO admins (first_name, last_name, email, username, hashed_password ) VALUES (?,?,?,?,?)";

  $result = mysqli_execute_query($db, $sql, [$admin["first_name"],$admin["last_name"],$admin["email"],$admin["username"], $hashed_password]);
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


function update_admin($admin){
  global $db;

  $errors = validate_admin($admin);
  if(!empty($errors)) {
    return $errors;
  }
  $hashed_password = password_hash($admin["hashed_password"], PASSWORD_BCRYPT); 

  $sql = "UPDATE admins SET first_name=?, last_name=?, email=?, username=?, hashed_password=? WHERE id=? LIMIT 1";

  $result = mysqli_execute_query($db, $sql, [$admin["first_name"],$admin["last_name"],$admin["email"],$admin["username"],$hashed_password, $admin["id"]]);
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

function delete_admin($id){
  global $db;

  $sql = "DELETE FROM admins WHERE id=? LIMIT 1";

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

function validate_admin($admin) {
  $errors = [];

  // First name
  if(is_blank($admin["first_name"])){
      $errors[] = "First name cannot be blank";
  } elseif(!has_length($admin['first_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "First name must be between 2 and 255 characters.";
  }

  // Last name
  if(is_blank($admin["last_name"])){
    $errors[] = "Last name cannot be blank";
  } elseif(!has_length($admin['last_name'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Last name must be between 2 and 255 characters.";
  }

  // E-Mail
  if(is_blank($admin["email"])){
    $errors[] = "E-Mail cannot be blank";
  } elseif(!has_length($admin['email'], ['min' => 2, 'max' => 255])) {
    $errors[] = "E-Mail must be between 2 and 255 characters.";
  } elseif(!has_valid_email_format($admin['email'])) {
    $errors[] = "E-Mail must have a valid E-Mail format.";
  }

  // Username
  if(is_blank($admin["username"])){
    $errors[] = "Username cannot be blank";
  } elseif(!has_length($admin['username'], ['min' => 2, 'max' => 255])) {
    $errors[] = "Username must be between 2 and 255 characters.";
  }
  $current_id = $admin["id"] ?? "0"; 
  if(!has_unique_admin_username($admin["username"], $current_id )) {
    $errors[] = "Admin with this Username already exist. Try another one.";
  }

  // Password
  if(is_blank($admin['hashed_password'])) {
    $errors[] = "Password cannot be blank.";
  } elseif (!has_length($admin['hashed_password'], array('min' => 8))) {
    $errors[] = "Password must contain 8 or more characters";
  } elseif (!preg_match('/[A-Z]/', $admin['hashed_password'])) {
    $errors[] = "Password must contain at least 1 uppercase letter";
  } elseif (!preg_match('/[a-z]/', $admin['hashed_password'])) {
    $errors[] = "Password must contain at least 1 lowercase letter";
  } elseif (!preg_match('/[0-9]/', $admin['hashed_password'])) {
    $errors[] = "Password must contain at least 1 number";
  } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['hashed_password'])) {
    $errors[] = "Password must contain at least 1 symbol";
  }
  
  // Confirm Password
  if(is_blank($admin['confirm_password'])) {
    $errors[] = "Confirm password cannot be blank.";
  } elseif ($admin['hashed_password'] !== $admin['confirm_password']) {
    $errors[] = "Password and confirm password must match.";
  }


  return $errors;
}


?>