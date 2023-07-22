<?php require_once('../private/initialize.php'); ?>

<?php
if(isset($_GET["id"]))
{
  $page_id = $_GET["id"];
  $page = find_pages_by_id($page_id);
  if(!$page){
    redirect_to(url_for("/index.php"));
  }
  else
  {
    // nothin selected; show the homepage
  }
}
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>
  
  <div id="page">

  <?php 
  if(isset($page))
  {
    // show the page from the database
    echo h($page["content"]);
  }
  else
  {
    // Show the homepage
    // The homepage content could be:
    // * static content (here or in a shared file)
    // * show the first page from the nav
    // * from databese 
    include(SHARED_PATH . '/static_homepage.php'); 
  }

  ?>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
