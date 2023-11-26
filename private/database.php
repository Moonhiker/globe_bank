<?php

class Database{

  private mysqli|bool $connection = false;

  function connectDatabase(array $optional=[]) : mysqli|false {
  
  if($this->connection === false ){
    $test = $optional["Test"] ?? false;
    if ($test){
      echo "connection is not set -> do connection to Test DB" . PHP_EOL;
      $this->connection = mysqli_connect(TEST_DB_SERVER, TEST_DB_USER, TEST_DB_PASS, TEST_DB_NAME, TEST_DB_PORT);
    }
    else{
      $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    }
  }
  $this->confirn_db_connect();
  return $this->connection;
  }

  function db_disconnect(mysqli $connection) : void {
    if(isset($connection)) {
      mysqli_close($connection);
    }
  }

  private function confirn_db_connect() : void {
    if(mysqli_connect_errno()) {
        $msg = "Database connection failed: ";
        $msg .= mysqli_connect_error();
        $msg .= " (" . mysqli_connect_errno() . ")";
        exit($msg);
      }
  }

  function confirm_result_set($result_set) : void{
    if(!$result_set){
        exit("Database query failed!");
    }
  }

}
?>
