<?php

 // require_once('db_credentials.php');

 
define("DB_SERVER", "localhost");
define("DB_USER", "Daniel");
define("DB_PASS", "123456");
define("DB_NAME", "globe_bank");
define("DB_PORT", 3306);


  function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME,DB_PORT);
  //$connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME,DB_PORT);
    confirn_db_connect();
    return $connection;
  }

  function db_disconnect($connection) {
    if(isset($connection)) {
      mysqli_close($connection);
    }
  }


  function confirn_db_connect(){
    if(mysqli_connect_errno()) {
        $msg = "Database connection failed: ";
        $msg .= mysqli_connect_error();
        $msg .= " (" . mysqli_connect_errno() . ")";
        exit($msg);
      }
  }

  function confirm_result_set($result_set){
    if(!$result_set){
        exit("Database query failed!");
    }
  }
?>
