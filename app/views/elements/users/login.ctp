<h1>Login</h1>
<?php 
	echo $this->Session->flash("auth");
?>
<?php echo $this->Form->create("User", array("action" => 'login')); ?>
	<?php echo $this->Form->input("User.email", array()); ?>
	<?php echo $this->Form->input("User.password", array()); ?>
	<?php echo $this->Form->submit("Login"); ?>
<?php echo $this->Form->end(); ?>