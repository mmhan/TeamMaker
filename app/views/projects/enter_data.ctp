<?php $user = $this->Session->read("Auth.User.id"); ?>
<h1>Enter Your Skill Data</h1>
<?php echo $this->Form->create("Project", array('action' => 'enter_data')); ?>
<fieldset>
	<legend>Skills</legend>
	<?php echo $this->Form->input("Project.id", array('value' => $projectId)) ?>
<?php foreach($skills as $i => $skill): ?>
	<?php echo $this->Form->hidden("MembersSkill.$i.skill_id", array('value' => $skill['Skill']['id'])); ?>
	<?php echo $this->Form->hidden("MembersSkill.$i.user_id", array('value' => $user)); ?>
	<?php
	$options = array(
		'label' => $skill['Skill']['name']
	);
	switch ($skill['Skill']['type']) {
		case SKILL_NUMERIC_RANGE:
			$options['type'] = 'text';
			break;
		case SKILL_TEXT_RANGE:
			$options['options'] = explode("|", $skill['Skill']['range']);
			break;
		case SKILL_TEXT:
			$options['type'] = 'text';
			break;
		default:
			break;
	} 
	?>
	<?php echo $this->Form->input("MembersSkill.$i.skill_value", $options); ?>
<?php endforeach; ?>
</fieldset>
<?php echo $this->Form->submit("Submit") ?>
<?php echo $this->Form->end(); ?>
