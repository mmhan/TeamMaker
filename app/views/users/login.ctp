<h1>Login</h1>
<?php echo $this->Form->create("User", array("action" => 'login')); ?>
	<?php echo $this->Form->submit("Login"); ?>
<?php echo $this->Form->end(); ?>