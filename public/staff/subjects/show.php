<?php require_once('../../../private/initialize.php'); ?>

<?php
// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$id = $_GET['id'] ?? '1'; // PHP > 7.0

$subject["menu_name"] = '';
$subject["position"] = '';
$subject["visible"] = '';

$subject = find_subject_by_id($id);
?>

<?php $page_title = 'Show Subjects'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="page show">

    <br>

    <dl>
      <dt>Menu Name</dt>
      <dd> <?php echo $subject["menu_name"];?> </dd>
    </dl>
    <dl>

      <dt>Position</dt>
      <dd> <?php echo $subject["position"];?> </dd>
    </dl>
   
    <dl>
      <dt>Visible</dt>
      <dd> <?php echo $subject['visible'] == '1' ? 'true' : 'false';  ?> </dd>
    </dl>



  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
