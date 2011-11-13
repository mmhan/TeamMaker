<?php
class Project extends AppModel {
	var $name = 'Project';
	var $displayField = 'name';
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
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
	
	function getImportData($data){
		$this->data = $data;
		//read all csv data
		$csvData = $this->Upload->readCsv($this->data['Upload']['id']);
		
		$members = array();
		foreach($csvData as $i => $row){
			$m = array();
			foreach($this->data['Import'] as $column){
				if($column['action'] == 'mapField'){
					$m['Member'][$column['maps_to']] = $row[$column['field_name']];
				}else if($column['action'] == 'isSkill'){
					//TODO: add importing of skill.
				}
				if(!empty($m)) $members[$i] = $m;
			}
		}
		
		return $members;
	}
}
?>