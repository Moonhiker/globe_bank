<?php

class Admin {

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

    function find_all_admins() : mysqli_result|bool{
        $sql = "SELECT * FROM admins ORDER BY username ASC";
        $result = mysqli_execute_query($this->db, $sql);
        $this->database->confirm_result_set($result);
        return $result;
    
    }
    
    function find_admin_by_id(int $id) : array{
        $sql = "SELECT * FROM admins WHERE id=? ";
        $query = mysqli_execute_query($this->db, $sql, [$id]);
        $this->database->confirm_result_set($query);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }
    
    function find_admin_by_username(string $username) : array{
        $sql = "SELECT * FROM admins WHERE username=? ";
        $query = mysqli_execute_query($this->db, $sql, [$username]);
        $this->database->confirm_result_set($query);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }
    
    function insert_admin(array $admin) : bool{
        $errors = $this->validate_admin($admin);
        if(!empty($errors)) {
        return $errors;
        }
    
        $hashed_password = password_hash($admin["hashed_password"], PASSWORD_BCRYPT); 
    
        $sql = "INSERT INTO admins (first_name, last_name, email, username, hashed_password ) VALUES (?,?,?,?,?)";
    
        $result = mysqli_execute_query($this->db, $sql, [$admin["first_name"],$admin["last_name"],$admin["email"],$admin["username"], $hashed_password]);
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
    
    
    function update_admin(array $admin) : bool{
        $errors = $this->validate_admin($admin);
        if(!empty($errors)) {
        return $errors;
        }
        $hashed_password = password_hash($admin["hashed_password"], PASSWORD_BCRYPT); 
    
        $sql = "UPDATE admins SET first_name=?, last_name=?, email=?, username=?, hashed_password=? WHERE id=? LIMIT 1";
    
        $result = mysqli_execute_query($this->db, $sql, [$admin["first_name"],$admin["last_name"],$admin["email"],$admin["username"],$hashed_password, $admin["id"]]);
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
    
    function delete_admin(int $id) : bool{
        $sql = "DELETE FROM admins WHERE id=? LIMIT 1";
    
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
    
    function validate_admin(array $admin) : array {
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
        if(!has_unique_admin_username($admin["username"], $current_id, $this->db )) {
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

    function getIdByLastQuery() : int {
        return mysqli_insert_id($this->db); // returns the value generated for an increment column by the last query
      }
  
}
  ?>