<php if(!isset($page_title))
{ 
    $page_title = "Staff Area";
} 
?>
<!doctype html>
<html lang="en">
  <head>
    <title> GBI <?php echo h($page_title); ?></title>
    <meta charset="utf-8">
    <link rel="Stylesheet" media="all" href="<?php echo url_for("/stylesheets/staff.css"); ?>">
  </head>

  <body>

    <header> 
      <h1>GBI Staff Area</h1>
    </header>

    <navigation>
      <ul>
        <li>User: <?php echo $_SESSION["username"] ?? ""; ?>  </li>  
        <li><a href= <?php echo WWW_ROOT . "/staff/index.php" ?>> Menu </a> </li>
        <li><a href= <?php echo WWW_ROOT . "/staff/logout.php" ?>> Logout </a> </li>
      </ul>
    </navigation>

    <?php if(isset($_SESSION["status_message"])) {
      echo "<div class=\"message\">" . $_SESSION["status_message"] . "</div>"; 
      unset($_SESSION["status_message"]); } 
      ?>