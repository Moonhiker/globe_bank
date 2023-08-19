<?php

require_once('../../../private/initialize.php');

if(is_post_request()) {

  // Handle form values sent by new.php
  $admin = [];
  $admin["first_name"] = $_POST['first_name'] ?? '';
  $admin["last_name"] = $_POST['last_name'] ?? '';
  $admin["email"] = $_POST['email'] ?? '';
  $admin["username"] = $_POST['username'] ?? '';
  $admin["hashed_password"] = $_POST['hashed_password'] ?? '';
  $admin["confirm_password"] = $_POST['confirm_password'] ?? '';
  
  $result = insert_admin($admin);
  if($result === true){
    $id = mysqli_insert_id($db); // returns the value generated for an increment column by the last query
    $_SESSION["status_message"] = "The admin {$admin["username"]} was added successfully";
    redirect_to( url_for( "/staff/admins/show.php?id=" . h($id)));
  }
  else{
    $errors = $result; 
  }

}
else{
  // display the blank form
  $admin = [];
  $admin["first_name"] = '';
  $admin["last_name"] = '';
  $admin['email'] = '';
  $admin["username"] = '';
  $admin["hashed_password"] = '';
  $admin["confirm_password"] = '';
}

?>

<?php $page_title = 'Add new Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="page new">
    <h1>Add Admin</h1>
    <?php echo display_errors($errors);  ?>
    <form action="<?php echo url_for('/staff/admins/new.php'); ?>" method="post">
        <dl>
            <dt>First Name</dt>
            <dd><input type="text" name="first_name" value="<?php echo h($admin["first_name"]); ?>" /></dd>
        </dl>
        
        <dl>
            <dt>Last Name</dt>
            <dd><input type="text" name="last_name" value="<?php echo h($admin["last_name"]); ?>" /></dd>
        </dl>

        <dl>
            <dt>E-Mail</dt>
            <dd><input type="email" name="email" value="<?php echo h($admin["email"]); ?>" /></dd>
        </dl>

        <dl>
            <dt>Username</dt>
            <dd><input type="text" name="username" value="<?php echo h($admin["username"]); ?>" /></dd>
        </dl>

        <dl>
            <dt>Password</dt>
            <dd><input type="password" name="hashed_password" value="" /></dd>
        </dl>

        <dl>
            <dt>Confirm Password</dt>
            <dd><input type="password" name="confirm_password" value="" /></dd>
        </dl>
        <p> Password should be at least 8 characters and include at least one upercase letter, lowercase letter, number and symbol </p>

        <div id="operations">
            <input type="submit" value="Add Admin" />
        </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
