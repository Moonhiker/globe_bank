<?php require_once('../../../private/initialize.php');
require_login();

$page_title = 'Admins'; 
include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="pages listing">
    <h1>Admins</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/admins/new.php'); ?>">Add New Admin</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>E-Mail</th>
  	    <th>Username</th>
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

      <?php 
      $all_admins = find_all_admins();
      while($admin = mysqli_fetch_assoc($all_admins)) { 
      ?>
        <tr>
          <td><?php echo h($admin['id']); ?></td>
          <td><?php echo h($admin["first_name"]); ?></td>
          <td><?php echo h($admin['last_name']); ?></td>
    	    <td><?php echo h($admin['email']); ?></td>
    	    <td><?php echo h($admin['username']); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/show.php?id=' . h(u($admin['id']))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/edit.php?id=' . h(u($admin['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id']))); ?>">Delete</a></td>
    	  </tr>
      <?php 
      } // end of while loop
      mysqli_free_result($all_admins);
       ?>
  	</table>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
