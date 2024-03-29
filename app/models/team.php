<?php
class Team extends AppModel {
	var $name = 'Team';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id'
		)
	);

	var $hasAndBelongsToMany = array(
		'Member' => array(
			'className' => 'Member',
			'joinTable' => 'members_teams',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'user_id',
			'unique' => true
		)
	);
	
	
	/**
	 * This function will find all the required data for make()
	 *
	 * @return 	mixed
	 * @author  @mmhan  
	 */
	function findMakeData($id) {
		$data = array();
		
		//get the ids of the skills
		$skills = $this->Project->Skill->find('all', array(
			'fields' => array('Skill.id', 'Skill.name', 'Skill.range','Skill.type'),
			'conditions' => array(
				'project_id' => $id
			),
			'recursive' => -1
		));
		$data['skills'] = Set::combine($skills, "{n}.Skill.id", "{n}.Skill");
		$skillIds = Set::extract($skills, "{n}.Skill.id");
		
		//prep the model so that only the skills that belongs to this project gets retrieved.
		$this->Member->unbindModel(array("hasMany" => array("MembersSkill")));
		$this->Member->bindModel(array(
			'hasMany' => array(
				"MembersSkill" => array(
					'className' => 'MembersSkill',
					'foreign_key' => 'user_id',
					'dependent' => true,
					'fields' => array('MembersSkill.skill_id','MembersSkill.skill_value'),
					"conditions" => array("MembersSkill.skill_id" => $skillIds)
				)
			)
		));
		
		//retrieve members and their skills
		$data['members'] = Set::combine($this->Member->find('all', array(
			'fields' => array('Member.id', 'Member.name', 'Member.email', 'Member.given_id'),
			'contain' => array("MembersSkill"),
			'joins' => array(
				array(
				'table' => "members_projects",
				'alias' => "MembersProject",
				'conditions' => array(
					'MembersProject.user_id = Member.id',
					'MembersProject.project_id = ' . $id
				)
				)
			)
		)), "{n}.Member.id", "{n}");
		foreach($data['members'] as $k => $member){
			$data['members'][$k]['MembersSkill'] = Set::combine($member['MembersSkill'], "{n}.skill_id", "{n}.skill_value");
		} 
		
		//retrieve the rules if any
		$this->Project->id = $id;
		$rules = $this->Project->field('rules');
		if(!empty($rules)) $rules = unserialize($rules);
		$data['rules'] = $rules;
		
		//retrive the teams if any
		$teamsRaw = $this->find("all", array(
			'fields' => array('Team.id'),
			'contain' => array('Member.id'),
			'conditions' => array('Team.project_id' => $id)
		));
		$teams = array();
		foreach(Set::extract($teamsRaw, "{n}.Team.id") as $i => $teamId){
			$teams[$teamId] = Set::extract($teamsRaw, "$i.Member.{n}.MembersTeam.user_id");
		}
		$data['teams'] = $teams;
		
		return $data;
	}
}
?>