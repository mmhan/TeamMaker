<?php
class Project extends AppModel {
	var $name = 'Project';
	var $displayField = 'name';
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Project name should not be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'collection_end' => array(
			//TODO: To add validation to check if time is in future.
			/* 'time' => array(
				'rule' => array('time'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			), */
		),
		'feedback_end' => array(
			//TODO: To add validation to check if given input is later than collection_end.
			/* 'time' => array(
				'rule' => array('time'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			), */
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasAndBelongsToMany = array(
		'Admin' => array(
			'className' => 'Admin',
			'joinTable' => 'admins_projects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'user_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Member' => array(
			'className' => 'Member',
			'joinTable' => 'members_projects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'user_id',
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
	
	var $hasMany = array(
		'Skill' => array(
			'className' => "Skill"
		),
		'Upload' => array(
			'className' => 'Upload'
		)
	);
	
	/**
	 * This function will take the given mapping data and return the data in the cakephp format.
	 **/
	function getImportData($data){
		$this->data = $data;
		
		//read all csv data
		$csvData = $this->Upload->readCsv($this->data['Upload']['id']);
		
		$projId = $this->data['Project']['id'];
		
		$members = array();
		$skillErrors = array();
		foreach($csvData as $i => $row){
			$m = array();
			$skillCount = 0;
			foreach($this->data['Import'] as $column){
				if($column['action'] == 'mapField'){
					//map using given data.
					$m['Member'][$column['maps_to']] = trim($row[$column['field_name']]);
				}else if($column['action'] == 'isSkill'){
					//TODO: refactor it so that a new function `isValidValues()` that takes all id and values, 
					//		returning the true/false in an array.
					
					$field = $column['field_name'];	//field name of CSV that's currently considered.
					$value = $row[$field];			//value of that field.
					
					if(empty($value)){
						$skillErrors[$i][$field] = sprintf("Value cannot be empty.");						 
					}else if($this->Skill->isValidValue($value, $column['skill_id'])){
						$m['MembersSkill'][$skillCount]['skill_id'] = $column['skill_id'];
						$m['MembersSkill'][$skillCount]['skill_value'] = $value;
						$skillCount++;
					}else{
						$skillErrors[$i]['warning'][$field] = sprintf("Given value `%s` is not within defined range", $value);
					}
				}
				
				if(!empty($m)){
					//add member to current project.
					$m['Project']['Project'] = array($projId);
					$members[$i] = $m;
				}
			}
		}
		
		$this->importError = $skillErrors;
		
		return $members;
	}

	/**
	 * Will find the remaining users who hasn't filled in all their skills
	 *
	 * @return	mixed	an array of members with data. 
	 * @author  @mmham
	 */
	function findRemaining($id) {
		
		$skills = $this->Skill->find('list', array('Skill.project_id' => $id));
		
		$members = $this->Member->find('all', array(
			'fields' => array('Member.id', 'Member.name', 'Member.email'),
			'conditions' => array(),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'members_skills',
					'alias' => 'MembersSkill',
					'type'	=> "LEFT",
					'conditions' => array(
						'Member.id = MembersSkill.user_id',
						'MembersSkill.skill_value IS NOT NULL',
						'MembersSkill.skill_id IN (' . implode(",", array_keys($skills)) . ')'
					)
				)
			)
		));
		
		return $members;
	}
}
?>