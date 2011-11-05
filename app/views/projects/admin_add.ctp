<div class="projects">
<?php echo $this->Form->create('Project');?>
	<fieldset>
 		<legend><?php __('New Project'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('collection_end');
		echo $this->Form->input('feedback_end');
		echo $this->Form->input('description');
		echo $this->Form->input("Admin");
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<?php echo $this->Html->link("&laquo; Back", array('action' => 'index'), array('escape' => false)); ?>
</div>