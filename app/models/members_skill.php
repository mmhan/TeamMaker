<?php
class MembersSkill extends AppModel {
	var $name = 'MembersSkill';
	var $displayField = 'skill_value';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Skill' => array(
			'className' => 'Skill',
			'foreignKey' => 'skill_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array(
		'skill_id' => array(
			'notempty' => array(
				'rule' => 'notempty'
			),
			'numeric' => array(
				'rule' => 'numeric'
			)
		),
		'skill_value' => array(
			'notempty' => array(
				'rule' => 'notempty'
			),
			'validateValueWithSkill' => array(
				'rule' => 'validateValueWithSkill',
				'message' => "Invalid value provided."
			)
		)
	);
	
	function validateValueWithSkill($check){
		$val = array_shift($check);
		return $this->Skill->isValidValue($val, $this->data[$this->alias]['skill_id']);
	}
}
?>