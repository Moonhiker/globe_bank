<?php 
require_once("../../../private/initialize.php");
require_login();
$subjectQueries = new Subject();

if(is_post_request()){
  // Handle form values sent by new.php
  $subject = [];
  $subject["menu_name"] = $_POST['menu_name'] ?? '';
  $subject["position"]  = $_POST['position'] ?? '';
  $subject["visible"]  = $_POST['visible'] ?? '';
  
  $result = $subjectQueries->insert_subject($subject);
  if($result === true)
  {
      $_SESSION["status_message"] = "The subject {$subject["menu_name"]} was created successfully";
      $new_id = $subjectQueries->getIdByLastQuery(); // returns the value generated for an increment column by the last query
      $subjectQueries->shift_subject_position(0,$subject["position"],$new_id); // automatically reorder positions
      redirect_to(url_for("/staff/subjects/show.php?id=" . $new_id));
  }
  else{
      $errors = $result;
  }
  
  
}

$menu_name = '';
$visible = '';
$subject = [];
$subject_count = $subjectQueries->count_subjects() +1;
$subject["position"]= $subject_count; 

?>

<?php $page_title = 'Create Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Create Subject</h1>
    <?php echo display_errors($errors);  ?>
    <form action="<?php echo url_for("/staff/subjects/new.php") ?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
          <select name=position>
          <?php
            for($i=1; $i <= $subject_count; $i++) {
              echo "<option value=\"". $i . "\"";
              if($subject["position"] == $i){
                echo " selected";
              }
              echo ">" . $i . "</option>";      
            }
            ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0" />
          <input type="checkbox" name="visible" value="1" <?php if($visible == "1") { echo " checked"; } ?> />
        </dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Create Subject" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

