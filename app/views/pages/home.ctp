<?php 
$user = $this->Session->read("Auth.User"); 
?>

<h1>Welcome to Project TeamMaker</h1>
<?php if(empty($user)): ?>
<?php echo $this->element("users/login"); ?>
<?php endif; ?>