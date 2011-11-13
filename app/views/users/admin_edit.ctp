<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('given_id', array('type' => 'text', 'label' => 'Given ID'));
		echo $this->Form->input('name');
		echo $this->Form->input('email');
	?>
	<div class="input password">
	<?php
		echo $this->Form->input('password', array(
			'value' => '', 
			'div' => false, 
			'label' => 'Password' . $this->Html->tag('span', "Please leave this field as blank, if you don't wish to change user's password", array('class' => 'tip'))
		));
	?>
	</div>
	<?php
		echo $this->Form->input('confirm_password', array('value' => '', 'type' => 'password'));
		echo $this->Form->input('group_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
	</ul>
</div>