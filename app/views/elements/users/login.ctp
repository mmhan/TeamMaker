<h1>Login</h1>
<?php 
	if($message = $session->read('Message.auth')){
		echo $html->div('error auth-messsage', $html->tag('p', $message['message']));
	}elseif((isset($status) && isset($isPost)) && !$status && $isPost){
		echo $html->div('error auth-message', $html->tag('p', $loginError));
	}
?>
<?php echo $this->Form->create("User", array("action" => 'login')); ?>
	<?php echo $this->Form->input("User.email", array()); ?>
	<?php echo $this->Form->input("User.password", array()); ?>
	<?php echo $this->Form->submit("Login"); ?>
<?php echo $this->Form->end(); ?>