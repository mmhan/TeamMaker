<?php
//TODO:: refine labels.
?>
<div class="projects">
<?php echo $this->Form->create('Project', array('action' => 'add_users', 'admin' => true));?>
	<fieldset>
 		<legend><?php __('New Project : Step 2'); ?></legend>
	<?php
		echo $this->Form->input('id');
	?>
	
	</fieldset>
<?php echo $this->Form->submit(__('Next', true) . ' &raquo;', array('escape' => false)); ?>
<?php echo $this->Form->end();?>
<?php echo $this->Html->link('Skip this step &raquo;', array('action' => 'index'), array('escape' => false)); ?>
</div>