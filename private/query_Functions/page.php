<?php

class Page {

    private mysqli|bool $db;
    private Database $database;

    function __construct(array $optional=[])
    {
        $this->database = new Database();
        $this->db = $this->database->connectDatabase($optional);
    }

    function __destruct()
    {
        $this->database->db_disconnect($this->db);
    }


function find_all_pages() : mysqli_result|bool{
    $sql = "SELECT * FROM pages ORDER BY subject_id ASC, position ASC";
    $result = mysqli_execute_query($this->db, $sql);
    $this->database->confirm_result_set($result);
    return $result;
  
  }
  
  
  function find_pages_by_id($id, array $options=[]) : array{
    $visible = $options["visible"] ?? false;
    $sql = "SELECT * FROM pages WHERE id=? ";
    if($visible)
    {
      $sql.= "AND visible = true ";
    }
    $query = mysqli_execute_query($this->db, $sql, [$id]);
    $this->database->confirm_result_set($query);
    $result = mysqli_fetch_assoc($query);
    return $result;
  }
  
  function insert_page(array $page) : bool{
    $errors = $this->validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }
  
    $sql = "INSERT INTO pages (menu_name, position, visible, subject_id, content ) VALUES (?,?,?,?,?)";
  
    $result = mysqli_execute_query($this->db, $sql, [$page["menu_name"],$page["position"],$page["visible"],$page["subject_id"], $page["content"]]);
    if($result)
    {
      return true;   
    }
    else{
      //insert failed
      echo mysqli_error($this->db);
      $this->database->db_disconnect($this->db);
      exit;
    }
  }
  
  function update_page(array $page) : bool{
    $errors = $this->validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }
  
    $sql = "UPDATE pages SET menu_name=?, position=?, visible=?, content=?, subject_id=? WHERE id=? LIMIT 1";
  
    $result = mysqli_execute_query($this->db, $sql, [$page["menu_name"],$page["position"],$page["visible"],$page["content"],$page["subject_id"], $page["id"]]);
    if($result)
    {
      return true;   
    }
    else{
      //update failed
      echo mysqli_error($this->db);
      $this->database->db_disconnect($this->db);
      exit;
    }
  }
  
  function delete_page(int $id) : bool{ 
    $sql = "DELETE FROM pages WHERE id=? LIMIT 1";
  
    $result = mysqli_execute_query($this->db, $sql, [$id]);
    if($result)
    {
      return true;   
    }
    else{
      //update failed
      echo mysqli_error($this->db);
      $this->database->db_disconnect($this->db);
      exit;
    }
  }
  
  function validate_page(array $page) : array {
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
      if(!has_unique_page_menu_name($page["menu_name"], $current_id, $this->db )){
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
  
  
    function find_pages_by_subject_id(int $subject_id, array $options=[]) :mysqli_result|bool {
      $visible = $options["visible"] ?? false;
      $sql = "SELECT * FROM pages WHERE subject_id=? ";
      if($visible)
      {
        $sql.= "AND visible = true ";
      } 
      $sql .= "ORDER BY position ASC";
      $result = mysqli_execute_query($this->db, $sql, [$subject_id]);
      $this->database->confirm_result_set($result);
      return $result;
    }
  
    function count_pages_by_subject_id(int $subject_id, array $options=[]): int{
      //$db = connectDatabase();
  
      $sql = "SELECT COUNT(id) FROM pages WHERE subject_id=? "; // do not return data -> return quantity
      $result = mysqli_execute_query($this->db, $sql, [$subject_id]);
      $this->database->confirm_result_set($result);
      $row = mysqli_fetch_row($result); // return one array element with the quantity
      mysqli_free_result($result);
      return $row[0];
    }
  
    function shift_page_position(int $start_pos, int $end_pos, int $subject_id, int $current_id = 0): mysqli_result|bool{
      //$db = connectDatabase();
  
      if($start_pos == $end_pos) return false; // nothing changed 
      
      if($start_pos == 0){ // new item, +1 to item greater than $end_pos
        $sql = "UPDATE pages SET position= position + 1 WHERE position >= ? AND id != ? AND subject_id = ?";
        $result = mysqli_execute_query($this->db, $sql,[$end_pos,$current_id, $subject_id]);
        return $result;
      }
    
      else if($end_pos == 0){ // delete item, -1 to item greater than $start_pos
        $sql = "UPDATE pages SET position= position - 1 WHERE position >= ? AND subject_id = ?";
        $result = mysqli_execute_query($this->db, $sql,[$start_pos, $subject_id]);
        return $result;
      }
    
      else if($start_pos < $end_pos){ // move item back, -1 from items beetween
        $sql = "UPDATE pages SET position= position - 1 WHERE position >= ? AND position <= ? AND id != ? AND subject_id = ?";
        $result = mysqli_execute_query($this->db, $sql,[$start_pos,$end_pos,$current_id, $subject_id]);
        return $result;
      }
    
      else if($start_pos > $end_pos){ // move item earlier, +1 from items beetween
        $sql = "UPDATE pages SET position= position + 1 WHERE position <= ? AND position >= ? AND id != ? AND subject_id = ?";
        $result = mysqli_execute_query($this->db, $sql,[$start_pos,$end_pos,$current_id, $subject_id]);
        return $result;
      }
    }

    function getIdByLastQuery() : int {
        return mysqli_insert_id($this->db); // returns the value generated for an increment column by the last query
      }
}
?>