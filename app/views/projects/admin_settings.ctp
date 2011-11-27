<?php
//TODO:: refine labels.
?>
<div class="projects">
<?php echo $this->Form->create('Project');?>
	<fieldset>
 		<legend><?php echo $this->data['Project']['name']; ?> : <?php __('Settings'); ?></legend>
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
<div class="clearfix">
	<div class="c50"><?php echo $this->Form->end(__('Submit', true));?></div>
	<div class="c50"><div class="actions autoWidth fRight">
	<ul>
		<li><?php echo $this->Html->link("Delete Project", array('action' => 'delete', $this->data['Project']['id'])); ?></li>
	</ul>
	</div></div>
</div>

<?php echo $this->Html->link("&laquo; Back", array('action' => 'index'), array('escape' => false)); ?>
</div>


