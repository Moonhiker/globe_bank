<?php

require_once('../../../private/initialize.php');

$menu_name = '';
$visible = '';



$page = [];


if(is_post_request()) {

  // Handle form values sent by new.php

  $page["subject_id"] = $_POST['subject_id'] ?? '';
  $page["menu_name"] = $_POST['menu_name'] ?? '';
  $page["position"] = $_POST['position'] ?? '';
  $page["visible"] = $_POST['visible'] ?? '';
  $page["content"] = $_POST['content'] ?? '';


  $result = insert_page($page);
  if($result === true){
    $id = mysqli_insert_id($db);
    redirect_to( url_for( "/staff/pages/show.php?id=" . h($id)));
  }
  else{
    $errors = $result; 
  }

}
else{

  
  $page["subject_id"] = '';
  $page["menu_name"] = '';
  $page["visible"] = '';
  $page["content"] = '';
}

$rows = find_all_pages();
$count_row = mysqli_num_rows($rows) + 1;  
$page["position"] = $count_row;


?>

<?php $page_title = 'Create Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/pages/index.php'); ?>">&laquo; Back to List</a>

  <div class="page new">
    <h1>Create Page</h1>
    <?php echo display_errors($errors);  ?>
    <form action="<?php echo url_for('/staff/pages/new.php'); ?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="<?php echo h($menu_name); ?>" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
          <select name="position">
            <?php 

            for($i=1; $i <= $count_row ;$i++){
              echo "<option value=\"" . $i ."\"";
              if($page["position"] == $i){
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
          <input type="checkbox" name="visible" value="1"<?php if($visible == "1") { echo " checked"; } ?> />
        </dd>
      </dl>
      
      <dl>
        <dt>Content</dt>
        <dd>
          <textarea name="content" cols="60" rows="10"><?php echo h($page['content']); ?></textarea>
        </dd>
      </dl>

      <div id="operations">
        <input type="submit" value="Create Page" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>