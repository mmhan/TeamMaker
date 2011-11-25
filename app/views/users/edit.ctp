<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit Account'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('given_id');
		echo $this->Form->input('name');
		echo $this->Form->input('email');
	?>
	<div class="input">
		<label>Given ID</label>
		<input type="text" name="" value="<?php echo $this->data['User']['given_id'] ?>" id="" disabled="disabled" />
	</div>
	<?php
		echo $this->Form->input('current_password', array('value' => '', 'type' => 'password'));
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
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link("Back to Home",'/') ?></li>
	</ul>
</div>