<?php 
$user = $this->Session->read('Auth.User');
?>

<div id="nav">
	<ul class="sf-menu">
	<?php if(!empty($user)): ?>
		<li>
			<?php echo $this->Html->link("Projects", array('controller' => 'projects', 'action' => 'index', 'admin' => 'true')); ?>
			<ul>
				<li><?php echo $this->Html->link("List Projects", array('controller' => 'projects', 'action' => 'index', 'admin' => 'true')); ?></li>
				<li><?php echo $this->Html->link("Add Project", array('controller' => 'projects', 'action' => 'add', 'admin' => 'true')); ?></li>
			</ul>
		</li>
		
		<li>
			<?php echo $this->Html->link("Users", array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?>
			<ul>
				<li><?php echo $this->Html->link("Users", array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?></li>
				<li><?php echo $this->Html->link("User Groups", array('controller' => 'groups', 'action' => 'index', 'admin' => 'true'), array('title' => "E.g.: Super Admins, Admins & Team Members")); ?></li>
			</ul>
		</li>
	</ul>
	<?php endif; ?>
</div>
<?php if(!empty($user)): ?>
<div id="secondNav">
	<ul class="sf-menu">
		<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?></li>
	</ul>
</div>
<?php endif; ?>