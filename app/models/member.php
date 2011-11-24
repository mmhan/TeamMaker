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
	
	/** Associate with skills **/
	public $hasMany = array(
		'MembersSkill' => array(
			'className' => 'MembersSkill',
			'foreign_key' => 'user_id',
			'dependent' => true
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
		return $queryData;
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
	 * 
	 * @param 	mixed 	that data that is to be imported.
	 * @return 	mixed 	Return data of a saveAll();
	 **/
	function import($data){
		
		$data = Set::merge($this->findMergeableData($data), $data);
		
		if(empty($data['MembersSkill'])) unset($data['MembersSkill']);
		
		FireCake::log($data);
		
		return $this->saveAll($data);
	}
	
	/**
	 * This method will check the current existing user data for merging
	 * Will find using given_id and see if there's any data that can be merged.
	 * If it found any mergeable data, it'll return the data in the mergeable format
	 * 
	 * @param 	mixed 	This data will be used to find existing data and manipulate it
	 * @return 	mixed 	Formatted array to be thrown into saveAll();
	 **/
	function findMergeableData($importData){
		$givenId = Set::extract($importData, 'Member.given_id');
		$currProjId = Set::extract($importData, "Project.Project.0");
		
		if(!empty($givenId)){
			
			$data = $this->find("first", array(
				'contain' => array("Project.id", "MembersSkill"),
				'conditions' => array("Member.given_id" => $givenId)
			));
			
			if(!empty($data)){
				
				//reformat projects data.
				if(!empty($data['Project'])){
					$projData = $data['Project'];
					
					$data['Project']['Project'] = array();
					foreach($projData as $project){
						//don't add duplicates and don't add currentProjId cos, the $importData already have it.
						if(!in_array($project['id'], $data['Project']['Project']) && $project['id'] != $currProjId)
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
	
	/**
	 * This function will find users who are in the given project
	 * 		- categorize them in new and existing users.
	 * 		- return the data.
	 *
	 * @return void
	 * @author  
	 */
	function findForLaunch($projectId) {
		
		$retData = array();
		
		//find all members that belong to the project.
		$members = $this->find('all', array(
			'fields' => array('Member.id', 'Member.given_id', 'Member.name', 'Member.email', 'Member.password', 'Member.last_login_time'),
			'joins' => array(
			    array(
			    	'table' => 'members_projects',
			        'alias' => 'MembersProject',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'MembersProject.user_id = Member.id',
			        )
			    )
			),
			'conditions' => array(
				'MembersProject.project_id' => $projectId
			),
			'recursive' => -1
		));
		
		//categorize them
		$new = array();
		$existing = array();
		
		foreach($members as $member){
			$password = Set::extract($member, 'Member.password');
			$loginTime = Set::extract($member, "Member.last_login_time");
			if(empty($password) || empty($loginTime)){
				$new[] = $member;
			}else{
				$existing[] = $member;
			}
		}
		
		$retData = compact('new', 'existing');
		
		return $retData;
	}
}
?>