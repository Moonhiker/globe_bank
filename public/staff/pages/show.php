<?php require_once('../../../private/initialize.php'); ?>

<?php
// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$id = $_GET['id'] ?? '1'; // PHP > 7.0

$page["menu_name"] = '';
$page["position"] = '';
$page["visible"] = '';

$page = find_pages_by_id($id);
echo $page["menu_name"];

?>

<?php $page_title = 'Show Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/pages/index.php'); ?>">&laquo; Back to List</a>

  <div class="page show">
     <?php //$subject = find_subject_by_id($page["subject_id"]); ?>
    <dl>
      <dt>Subject</dt>
      <dd> <?php //echo h($page["menu_name"]);?> </dd>
    </dl>
    <dl>

    <dl>
      <dt>Menu Name</dt>
      <dd> <?php echo h($page["menu_name"]);?> </dd>
    </dl>
    <dl>

      <dt>Position</dt>
      <dd> <?php echo h($page["position"]);?> </dd>
    </dl>
   
    <dl>
      <dt>Visible</dt>
      <dd> <?php echo $page["visible"] == 1 ? "true" : "false";  ?> </dd>
    </dl>

    <dl>
       <dt>Conent</dt>
       <dd> <?php echo h($page["content"]);?> </dd> 

    </dl>



  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
