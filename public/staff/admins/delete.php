<?php
require_once('../../../private/initialize.php');
require_login();
$adminQueries = new Admin();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/admins/index.php'));
}
$id = $_GET['id'];

if(is_post_request()) {
  $admin = $adminQueries->find_admin_by_id($id); // get information for status message
  $result = $adminQueries->delete_admin($id);
  if($result){
    $_SESSION["status_message"] = "Admin {$admin["username"]} was deleted";
    redirect_to( url_for( "/staff/admins/index.php"));
  }
}
else{ // get request
  $admin = $adminQueries->find_admin_by_id($id);
}
?>

<?php $page_title = 'Delete Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="page delete">
    <h1>Delete Admin</h1>
    <p>Are you sure you want to delete this Admin?</p>
    <p class="item"><?php echo h($admin["username"]); ?></p>

    <form action="<?php echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Admin" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>