<?php require_once('../../../private/initialize.php');
require_login();
$subjectQueries = new Subject();
$pageQueries = new Page();

$id = $_GET['id'] ?? '1'; 

$subject["menu_name"] = '';
$subject["position"] = '';
$subject["visible"] = '';

$subject = $subjectQueries->find_subject_by_id($id);
$pagesInSubject = $pageQueries->find_pages_by_subject_id($id); 
?>

<?php $page_title = 'Show Subjects'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="page show">

    <h1>Subject: <?php echo h($subject["menu_name"]); ?> </h1>

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

  <hr> <!-- PAGES -->
  <div class="pages listing">
    <h2>Pages</h2>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/pages/new.php?subject_id=' . h(u($subject["id"]))); ?>">Create New Page</a>
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
      while($page = mysqli_fetch_assoc($pagesInSubject)) { 
      ?>
        <tr>
          <td><?php echo h($page['id']); ?></td>
          <td><?php echo h($page['position']); ?></td>
          <td><?php echo $page['visible'] == 1 ? 'true' : 'false'; ?></td>
    	    <td><?php echo h($page['menu_name']); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . h(u($page['id']))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/pages/edit.php?id=' . h(u($page['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/pages/delete.php?id=' . h(u($page['id']))); ?>">Delete</a></td>
    	  </tr>
      <?php 
      } // end while loop
      mysqli_free_result($pagesInSubject);
       ?>
  	</table>

  </div>
  


</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
