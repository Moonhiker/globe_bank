<?php require_once('../private/initialize.php'); ?>

<?php
if(isset($_GET["id"]))
{
  $page_id = $_GET["id"];
  $page = find_pages_by_id($page_id, ["visible" => true]);
  if(!$page){
    redirect_to(url_for("/index.php"));  // id not exist
  }
  $subject_id = $page["subject_id"];
} 
else
{
  // nothin selected; show the homepage
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
    // HTML tags white list, tags which are allowed in content -> for security
    // all others tags will be deleted
    $allowed_tags = "<div><img><h1><h2><p><br><strong><em><ul><li>";
    echo strip_tags($page["content"],$allowed_tags);
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
