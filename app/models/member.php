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
	
	/**
	 * hasAndBelongsToMany associations.
	 *
	 * @var array
	 */
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
		),
		'Team' => array(
			'className' => "Team",
			'joinTable' => "members_teams",
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'team_id',
			'unique' => true
		)
	);
	
	/**
	 * hasMany association with skills
	 *
	 * @var array
	 */
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
	
	/**
	 * Override beforeFind to only find ROLE_MEMBER
	 *
	 * @return	array of queryData to find. 
	 * @author 	@mmhan
	 */
	function beforeFind($queryData){
		$queryData['conditions']['Member.group_id'] = array(ROLE_MEMBER);
		return $queryData;
	}
	
	/**
	 * Override before save to save only the members
	 *
	 * @return	mixed	See User
	 */
	function beforeSave($data){
		$this->data['Member']['group_id'] = ROLE_MEMBER;
		return parent::beforeSave($data);
	}
	
	/** 
	 * This funciton get the schema and return only the importable fields
	 * 
	 * @return	array 	of fields that are importable.
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
	 * This will prep the model for import process.
	 *
	 * @return void
	 * @author  @mmhan
	 */
	function beforeImport() {
		//Prep model for saving.
		//For the sake of ACL we can't save directly with Member model,
		//So import User, and save as that.
		$this->User = ClassRegistry::init("User");
		$this->User->disableValidate('import');
		//Associate with skills so that saveAll will save everything.
		$this->User->bindModel(array(
		'hasMany' => array(
			'MembersSkill' => array(
				'className' => 'MembersSkill',
				'foreign_key' => 'user_id',
				'dependent' => true
			)
		),
		'hasAndBelongsToMany' => array(
			'Project' => array(
				'className' => 'Project',
				'joinTable' => 'members_projects',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'project_id',
				'unique' => true
			)
		)), false); //don't let it reset, we'll remove the association when all is done.
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
		//import user data and save member as user
		$userData = $data['Member'];
		$userData['group_id'] = ROLE_MEMBER;
		unset($data['Member']);
		$data['User'] = $userData;
		FireCake::log($data);
		return $this->User->saveAll($data);
	}
	
	/**
	 * This will be executed to clean up after the import process is done.
	 *
	 * @return void
	 * @author @mmhan
	 */
	function afterImport() {
		//now remove the association (Just in case there needs to be some other call after this) for performance.
		$this->User->unbindModel(array(
			'hasMany' => array('MembersSkill'),
			'hasAndBelongsToMany' => array("Project")
		));
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