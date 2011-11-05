<div class="projects">
<?php echo $this->Form->create('Project');?>
	<fieldset>
 		<legend><?php __('Edit Project'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	<div class="colContainer">
		<div class="c50">
		<?php
			echo $this->Form->input('collection_end', array(
				'type'=>'text',
				'div' => 'input datetimeui'
			));
		?>
		</div>
		<div class="c50">
		<?php
			echo $this->Form->input('feedback_end', array(
				'type'=>'text',
				'div' => 'input datetimeui'
			));
		?>
		</div>
	</div>
	<?php
		echo $this->Form->input('description');
		echo $this->Form->input("Admin", array('div' => 'input select habtmSelector'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<?php echo $this->Html->link("&laquo; Back", array('action' => 'index'), array('escape' => false)); ?>
</div>