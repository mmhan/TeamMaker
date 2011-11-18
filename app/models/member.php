<?php 
/**
 * This model act as a model for members of projects.
 * This is a more pattern-compliant way than to query on the controller side of projects.
 **/
App::import('Model', 'User');
class Member extends User{
	var $name = "Member";
	var $useTable = "users";
	var $actsAs = array("Containable");
	
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
	function import($data, $merge = true){
		if($merge){
			$data = Set::merge($this->findMergeableData($data), $data);
		}
		return $this->saveAll($data);
	}
	
	/**
	 * This will check the current existing user data for merging
	 * Will find using given_id and see if there's any data that can be merged.
	 * If it found any mergeable data, it'll return the data in the mergeable format
	 **/
	function findMergeableData($data){
		$givenId = Set::extract($data, 'Member.given_id');
		if(!empty($givenId)){
			$data = $this->find("first", array(
				'contain' => array("Project.id"),
				'conditions' => array("Member.given_id" => $givenId)
			));
			if(!empty($data)){
				//reformat projects data.
				if(!empty($data['Project'])){
					$projData = $data['Project'];
					unset($data['Project']);
					
					$data['Project']['Project'] = array();
					foreach($projData as $project){
						$data['Project']['Project'][] = $project['id'];
					}
				}
				
				return $data;
			}
		}
		return array();
	}
	
	/**
	 * function used to pick and choose the validation methods based on use cases.
	 * 
	 * @override		parent's disableValidate
	 * @param	string	type of action the model is going to perform.
	 * 
	 * @return	boolean	true if it was in specified actions, false if it wasn't.
	 */
	function disableValidate ($type) {
		switch ($type) {
			case "import" : 
				unset($this->validate['password']);
				break;
			default :
				return parent::disableValidate($type);
		}
	}
}
?>