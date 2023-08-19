<?php require_once('../../../private/initialize.php');
require_login();

$page_title = 'Subjects';
include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="subjects listing">
    <h1>Subjects</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for("/staff/subjects/new.php") ?>">Create New Subject</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>Position</th>
        <th>Visible</th>
  	    <th>Name</th>
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

      <?php  
      $subject_set = find_all_subjects();
       //while($subject = mysqli_fetch_assoc($subject_set)) { 
        foreach($subject_set as $subject) {
        ?>
        <tr>
          <td><?php echo $subject['id']; ?></td>
          <td><?php echo $subject['position']; ?></td>
          <td><?php echo $subject['visible'] == 1 ? 'true' : 'false'; ?></td>
    	    <td><?php echo $subject['menu_name']; ?></td>
          <td><a href="<?php echo url_for("/staff/subjects/show.php?id=") . h(u($subject['id'])) ; ?>">View</a></td>
          <td><a href="<?php echo url_for("/staff/subjects/edit.php?id=") . h(u($subject['id'])) ; ?>">Edit</a></td>
          <td><a href="<?php echo url_for("/staff/subjects/delete.php?id=") . h(u($subject['id'])) ; ?>">Delete</a></td>
    	  </tr>
      <?php 
      } // endling of foreach
      mysqli_free_result($subject_set); // free the memory from data of data base
      ?>
  	</table>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
