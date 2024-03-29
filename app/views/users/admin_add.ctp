<?php

$projectId = (isset($this->params['named']['project_id'])) ? $this->params['named']['project_id'] : false;

?>

<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('New ' . Inflector::singularize($groups[$group_id])); ?></legend>
	<?php
		echo $this->Form->input('given_id', array('type' => 'text', 'label' => 'Given ID'));
		echo $this->Form->input('name');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('confirm_password', array('type' => 'password'));
		echo ($projectId) ?
			$this->Form->hidden("Project.Project.0", array('value' => $projectId)) : '';
	?>
	<div class="input text">
	<?php
		echo $this->Form->label("group_id");
		echo $this->Form->label($groups[$group_id]);
		echo $this->Form->hidden("group_id", array('value' =>$group_id));
	?>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
	</ul>
</div>