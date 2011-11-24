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
	
	/***
	 * This function will validate a Range's validatity using type
	 * Used for creation of the skills data. 
	 * 
	 */
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
	
	/**
	 * This value will validate a skill value to be populated into the members_skills table 
	 * using the range and type specified in this table.
	 * 
	 * @param	mixed	any value
	 * @param	int		id of the skill that is in question.
	 * @return	boolean
	 */
	function isValidValue($value, $id){
		
		if(empty($value)) return false;
		
		$skill = Cache::read("Skill_" . $id);
		if(empty($skill)){
			$this->id = $id;
			$this->recursive = -1;
			$skill = $this->read();
			if(empty($skill)){
				return false;
			}
			//flat it down by a level.
			$skill = $skill[$this->alias];
			//write it to cache
			Cache::write('Skill_' . $id, $skill);
		}
		
		//get the type.
		$type = $skill['type'];
		switch($type){
			case SKILL_NUMERIC_RANGE:
				//only integers??
				$isOnlyIntegers = preg_match("|^\d+-\d+$|", $skill['range']);
				list($min, $max) = explode('-', $skill['range']);
				
				if($isOnlyIntegers){
					//true if value is int and in range
					return
						preg_match("|^\d+$|", $value) &&
						$value == (string)(int) $value && 
						(int) $value >= (int) $min && 
						(int) $value <= (int) $max;
				}else{
					//true if value is float and in range
					return 
						$value == (string)(float) $value &&
						(float) $value >= (float) $min && 
						(float) $value <= (float) $max;
				}
				break;
			case SKILL_TEXT_RANGE:
				//true if the index is in range.
				return
					is_numeric($value) && 
					(int) $value <= count(explode('|', $skill['range'])) - 1 &&
					(int) $value >= 0;
				break;
			case SKILL_TEXT:
				//true if the text is less than given number of characters.
				return strlen($value) <= $skill['range'];
				break;
		}
	}

	/**
	 * This function will return the list of skills that are to be imported
	 * for the given project
	 * 
	 * @param	int		id of the project
	 * @return	mixed	an array of the all the skill names
	 */
	function findAllSkills($projectId){
		return $this->find("list", array("conditions" => array("Skill.project_id" => $projectId)));
	}
	
	/**
	 * This function will remove the cache of cached skills after a saveAll action.
	 * 
	 * @param	array 	an array of any size listing all ids.
	 */
	function removeCache($ids = array()){
		foreach($ids as $id){
			Cache::delete('Skill_' . $id);
		}
	}
}
?>