<div class="membersSkills">
<?php echo $this->Form->create('MembersSkill', array('action' => 'enter_data'));?>
	<fieldset>
 		<legend><?php __('Enter Your Skill Data'); ?></legend>
 	<?php echo $this->Form->hidden("Project.id", array('value' => $projectId)) ?>
	<?php foreach($this->data['MembersSkill'] as $i => $val): ?>
	<div>
	<?php
		$skill = $skills[$val['skill_id']];
		$options = array(
			'type' => 'text', 
			'label' => $skill['name']
		);
		$tip = '';
		switch($skill['type']){
			case SKILL_NUMERIC_RANGE:
				$tip = "Allowed Range : " . $skill['range'];
				break;
			case SKILL_TEXT_RANGE:
				unset($options['type']);
				$options['options'] = explode('|', $skill['range']);
				break;
			case SKILL_TEXT:
				$tip = "Maximum " . $skill['range'] . " characters";
				break;
		}
		if(!empty($tip)){
			$options['label'] .= $this->Html->tag('span', $tip, array('class' => 'tip'));
		}
		
		if(isset($val['id']) && !empty($val['id'])) echo $this->Form->input("MembersSkill.$i.id");
		echo $this->Form->hidden("MembersSkill.$i.skill_id");
		echo $this->Form->hidden("MembersSkill.$i.user_id");
		echo $this->Form->input("MembersSkill.$i.skill_value", $options);
	?>
	</div>
	<?php endforeach; ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<?php echo $this->Html->link("&laquo; Back", array('controller' => 'projects', 'action' => 'index'), array('escape' => false)); ?>