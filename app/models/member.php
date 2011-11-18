<?php 
/**
 * This model act as a model for members of projects.
 * This is a more pattern-compliant way than to query on the controller side of projects.
 **/
App::import('Model', 'User');
class Member extends User{
	var $name = "Member";
	var $useTable = "users";
	
	/** Associate with projects **/
	var $hasAndBelongsToMany = array(
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'members_projects',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'project_id',
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
	
	/**
	 * A list of fields that should not be imported.
	 **/
	var $noImport = array(
		'id',
		'password',
		'group_id',
		'last_login_time', 'created', 'modified'
	);
	
	//only find members.
	function beforeFind($queryData){
		$queryData['conditions']['Member.group_id'] = array(ROLE_MEMBER);
		return parent::beforeFind($queryData);
	}
	
	//only save as members
	function beforeSave($data){
		$this->data['Member']['group_id'] = ROLE_MEMBER;
		return parent::beforeFind($data);
	}
	
	/** 
	 * get the schema and return only the importable fields
	 **/
	function getImportableFields(){
		$schema = $this->schema();
		
		foreach($this->noImport as $fieldname){
			unset($schema[$fieldname]);
		}
		$fields = array();
		foreach($schema as $field => $meta){
			$fields[$field] = Inflector::humanize($field);
		}
		return $fields;
	}
	
	/**
	 * Import a single row of user
	 **/
	function import($data){
		FireCake::log($data);
		return $this->saveAll($data);
	}
}
?>