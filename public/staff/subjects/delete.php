<?php
require_once('../../../private/initialize.php');
require_login();
$subjectQueries = new Subject();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/subjects/index.php'));
}
$id = $_GET['id'];

if(is_post_request()) {
  $subject = $subjectQueries->find_subject_by_id($id); // get information for status message
  $result = $subjectQueries->delete_subject($id);
  if($result){
    $_SESSION["status_message"] = "The subject {$subject["menu_name"]} was deleted";
    $subjectQueries->shift_subject_position($subject["position"],0); // automatically reorder positions
    redirect_to( url_for( "/staff/subjects/index.php"));
  }
 
}
else{ // get request
  $subject = $subjectQueries->find_subject_by_id($id);
}


?>

<?php $page_title = 'Delete Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject delete">
    <h1>Delete Subject</h1>
    <p>Are you sure you want to delete this subject?</p>
    <p class="item"><?php echo h($subject['menu_name']); ?></p>

    <form action="<?php echo url_for('/staff/subjects/delete.php?id=' . h(u($subject['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Subject" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
