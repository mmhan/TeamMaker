<?php 
$user = $this->Session->read('Auth.User');
?>

<div id="nav">
	<ul class="sf-menu">
	<?php if(!empty($user)): ?>
		<li>
			<?php echo $this->Html->link("Projects", array('controller' => 'projects', 'action' => 'index')); ?>
			<ul>
				<li></li>
				<li></li>
			</ul>
		</li>
		
		<li>
			<?php echo $this->Html->link("Users", array('controller' => 'users', 'action' => 'index')); ?>
			<ul>
				<li><?php echo $this->Html->link("Users", array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?></li>
				<li><?php echo $this->Html->link("User Groups", array('controller' => 'groups', 'action' => 'index'), array('title' => "E.g.: Super Admins, Admins & Team Members")); ?></li>
			</ul>
		</li>
	</ul>
	<?php endif; ?>
</div>