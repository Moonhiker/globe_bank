<?php
require_once('../../../private/initialize.php');
require_login();
$pageQueries = new Page();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/pages/index.php'));
}
$id = $_GET['id'];

$page = $pageQueries->find_pages_by_id($id);

if(is_post_request()) {
  $result = $pageQueries->delete_page($id);
  if($result){
    $_SESSION["status_message"] = "The page {$page["menu_name"]} was deleted";
    $pageQueries->shift_page_position($page["position"],0,$page["subject_id"]); // automatically reorder positions
    redirect_to( url_for( "/staff/subjects/show.php?id=" . h(u($page["subject_id"])))); // back to nested subject
  }
}

?>

<?php $page_title = 'Delete Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/show.php?id=' . h(u($page["subject_id"]))); ?>">&laquo; Back to Subject Page</a>

  <div class="page delete">
    <h1>Delete Page</h1>
    <p>Are you sure you want to delete this page?</p>
    <p class="item"><?php echo h($page['menu_name']); ?></p>

    <form action="<?php echo url_for('/staff/pages/delete.php?id=' . h(u($page['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete page" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
