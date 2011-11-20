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
		foreach($csvData as $i => $row){
			$m = array();
			foreach($this->data['Import'] as $column){
				if($column['action'] == 'mapField'){
					//map using given data.
					$m['Member'][$column['maps_to']] = trim($row[$column['field_name']]);
				}else if($column['action'] == 'isSkill'){
					//TODO: add importing of skill.
				}
				
				if(!empty($m)){
					//add member to current project.
					$m['Project']['Project'] = array($projId);
					$members[$i] = $m;
				}
			}
		}
		
		return $members;
	}
}
?>