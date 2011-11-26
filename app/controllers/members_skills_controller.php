<?php
class MembersSkillsController extends AppController {

	var $name = 'MembersSkills';

	/**
	 * Will allow user to enter their data.
	 *
	 * @return void
	 * @author @mmhan
	 */
	function enter_data($projectId = null) {
		
		$userId = $this->Auth->user('id');
		
		if (!$projectId && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Project', true));
			$this->redirect(array('controller' => "projects", 'action' => 'index'));
		}
		
		if(!empty($this->data)) $projectId = $this->data['Project']['id'];
		
		$skills = $this->MembersSkill->Skill->findAllSkills($projectId, 'all');
		$skills = Set::combine($skills, '{n}.Skill.id', '{n}.Skill');
		
		if (!empty($this->data)) {
			if ($this->MembersSkill->saveAll($this->data['MembersSkill'])) {
				$this->Session->setFlash(__('The members skill has been saved', true));
				$this->redirect(array('controller' => "projects", 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The members skill could not be saved. Please, try again.', true));
			}
		}
		
		
		if(empty($this->data)){
			$this->data['MembersSkill'] = Set::combine(
				$this->MembersSkill->find(
					'all', 
					array(
						'conditions' => array('MembersSkill.skill_id' => array_keys($skills)),
						'recursive' => -1
					)
				), '{n}.MembersSkill.id', '{n}.MembersSkill');
			if(empty($this->data['MembersSkill'])){
				$i = 0;
				foreach($skills as $id => $skill){
					$this->data['MembersSkill'][$i]['skill_id'] = $id;
					$this->data['MemebrsSkill'][$i]['user_id'] = $userId;
					$i++;
				}
			}
		}
		
		$this->set(compact('skills', 'projectId'));
	}
}
?>