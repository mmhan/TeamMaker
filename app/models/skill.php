<?php
class Skill extends AppModel {
	var $name = 'Skill';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasAndBelongsToMany = array(
		'Member' => array(
			'className' => 'Member',
			'joinTable' => 'members_skills',
			'foreignKey' => 'skill_id',
			'associationForeignKey' => 'member_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	//validation of skills data.
	var $validate = array(
		'type' => array(
			'inList' => array(
				'rule' => array('inList', array(SKILL_NUMERIC_RANGE, SKILL_TEXT_RANGE, SKILL_TEXT)),
				'message' => "Invalid skill type"
			)
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notempty'
			)
		),
		'range' => array(
			'validateRange' => array(
				'rule' => 'validateRangeWithType'
			)
		)
	);
	
	function validateRangeWithType($check){
		$range = array_shift($check);
		$type = isset($this->data[$this->alias]['type']) ? $this->data[$this->alias]['type'] : '';
		$return = false;
		switch ($type) {
			case SKILL_NUMERIC_RANGE:
				list($min, $max) =  explode('-', $range);
				if(
					($min == (string)(int) $min && $max == (string)(int) $max) ||  //if both are int or
					($min == (string)(float) $min && $max == (string)(float) $max)	//both are decimals
				){
					$return = true;
				}else{
					$return = false;
				}
				break;
			case SKILL_TEXT_RANGE:
				$return = count(explode('|', $range)) > 1;
				break;
			case SKILL_TEXT:
				$return = ($range == (string)(int) $range);
				break;
		}
		return $return;
	}
}
?>