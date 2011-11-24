<h1>Edit Skills</h1>
<?php echo $this->Form->create('Project', array('action' => 'skills')) ?>
<?php echo $this->Form->input('id'); ?>
<?php echo $this->element('skills_form'); ?>
<?php echo $this->Form->submit("Submit"); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->link("&laquo; Back to Dashboard", array('action' => 'dashboard'), array('escape' => false)); ?>