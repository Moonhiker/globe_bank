<?php

class Subject {

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

    function find_all_subjects(array $options=[]) : mysqli_result|bool{
        $visible = $options["visible"] ?? false;
        $sql = "SELECT * FROM subjects ";
        if($visible)
        {
            $sql.=  "WHERE visible = true ";
        }
        $sql.= "ORDER BY position ASC";
        $result = mysqli_execute_query($this->db, $sql);
        $this->database->confirm_result_set($result); // check if we get data back
        return $result;
    }

    function find_subject_by_id(int $id) : array{
        $sql = "SELECT * FROM subjects WHERE id=?";
        $query = mysqli_execute_query($this->db, $sql,[$id]);
        $this->database->confirm_result_set($query);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }

    function get_subject_position_by_id(int $id) : int{
        $sql = "SELECT position FROM subjects WHERE id=?";
        $query = mysqli_execute_query($this->db, $sql,[$id]);
        $this->database->confirm_result_set($query);
        $result = mysqli_fetch_assoc($query);
        return $result["position"];
    }

    function validate_subject(array $subject) : array{
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
            echo "Visible must be true or false.";
            $errors[] = "Visible must be true or false.";
        }

        return $errors;
    }

    function insert_subject(array $subject) : bool|array{
        $errors = $this->validate_subject($subject);
        if(!empty($errors)){
            return $errors;
        }

        $sql = "INSERT INTO subjects (menu_name, position, visible) VALUES (?,?,?)";

        $result = mysqli_execute_query($this->db, $sql, [$subject["menu_name"],$subject["position"],$subject["visible"]]);
        if($result)
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->db);
            $this->database->db_disconnect($this->db);
            exit;
        }
        }

        function update_subject(array $subject) : bool{
        $errors = $this->validate_subject($subject);
        if(!empty($errors)) {
            return $errors;
        }

        $sql = "UPDATE subjects SET menu_name=?, position=?, visible=? WHERE id=? LIMIT 1";

        $result = mysqli_execute_query($this->db, $sql, [$subject["menu_name"],$subject["position"],$subject["visible"],$subject["id"]]);

        // For UPDATE statement, result is true/false
        if($result){
            return true;
        }
        else
        {   // UPDATE FAILED
            echo mysqli_error($this->db);
            $this->database->db_disconnect($this->db);
            exit;
        }
    }

    function delete_subject(int $id) : bool{
        $sql = "DELETE FROM subjects WHERE id=? LIMIT 1";

        $result = mysqli_execute_query($this->db, $sql, [$id]);

        // For DELETE statement, result is true/false
        if($result){
            return true;
        }
        else{
            //delete failed
            echo mysqli_error($this->db);
            $this->database->db_disconnect($this->db);
            exit;
        }
        }

        function shift_subject_position(int $start_pos, int $end_pos, int $current_id = 0): mysqli_result|bool{
        if($start_pos == $end_pos) return false; // nothing changed
        
        if($start_pos == 0){ // new item, +1 to item greater than $end_pos
            $sql = "UPDATE subjects SET position= position + 1 WHERE position >= ? AND id != ?";
            $result = mysqli_execute_query($this->db, $sql,[$end_pos,$current_id]);
            return $result;
        }

        else if($end_pos == 0){ // delete item, -1 to item greater than $start_pos
            $sql = "UPDATE subjects SET position= position - 1 WHERE position >= ?";
            $result = mysqli_execute_query($this->db, $sql,[$start_pos]);
            return $result;
        }

        else if($start_pos < $end_pos){ // move item back, -1 from items beetween
            $sql = "UPDATE subjects SET position= position - 1 WHERE position >= ? AND position <= ? AND id != ?";
            $result = mysqli_execute_query($this->db, $sql,[$start_pos,$end_pos,$current_id]);
            return $result;
        }

        else if($start_pos > $end_pos){ // move item earlier, +1 from items beetween
            $sql = "UPDATE subjects SET position= position + 1 WHERE position <= ? AND position >= ? AND id != ?";
            $result = mysqli_execute_query($this->db, $sql,[$start_pos,$end_pos,$current_id]);
            return $result;
        }
    }

    function count_subjects(): int{
        $sql = "SELECT COUNT(id) FROM subjects"; // do not return data -> return quantity
        $result = mysqli_execute_query($this->db, $sql);
        $this->database->confirm_result_set($result);
        $row = mysqli_fetch_row($result); // return one array element with the quantity
        mysqli_free_result($result);
        return $row[0];
    }
    

    function getIdByLastQuery(): int{
        return mysqli_insert_id($this->db); // returns the value generated for an increment column by the last query
    }

}

?>