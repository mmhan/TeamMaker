<?php
//TODO:: refine labels.
?>
<div class="projects">
<?php echo $this->Form->create('Project', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('New Project'); ?></legend>
	<?php
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
	<?php echo $this->Form->input("Upload.0.file", 
		array(
			'type' => 'file', 
			'label' => 'CSV File Import to Users' 
				. $this->Html->tag('span', "(Optional: You can add users later.)", array('class' => 'tip'))
			)
		); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<?php echo $this->Html->link("&laquo; Back", array('action' => 'index'), array('escape' => false)); ?>
</div>