<?php
class ProjectsController extends AppController {

	var $name = 'Projects';

	function admin_index() {
		$user = $this->Auth->user('id');
		//get user's projects.
		$result = $this->Project->find('all', array(
			'recursive' => -1,
			'conditions' => array('AdminProject.user_id' => $user), 
			'fields' => array('Project.id', 'Project.name'),
			'joins' => array(
				array(
						'table' => 'admins_projects',
						'alias' => 'AdminProject',
						'type' => 'LEFT',
						'foreignKey' => false,
						'conditions'=> 'Project.id = AdminProject.project_id'
				)
			)
		));
		$list = Set::classicExtract($result,'{n}.Project.id');
		
		//set all other projects.
		$currProjects = $this->Project->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'Project.status <>' => PROJECT_ARCHIVE,
				'Project.id' => $list				
			)
		));
		$projects = Set::combine($currProjects, '{n}.Project.id', '{n}.Project', '{n}.Project.status');
		
		FireCake::log($projects);
		
		//sort phase 1 projects by their modified date
		if(array_key_exists(PROJECT_SEED, $projects))
			$projects[PROJECT_SEED] = Set::sort(array_values($projects[PROJECT_SEED]), '{n}.modified', 'desc'); 
			
		//sort phase 2 projects by their cut-off date
		if(array_key_exists(PROJECT_COLLECT, $projects))
			$projects[PROJECT_COLLECT] = Set::sort(array_values($projects[PROJECT_COLLECT]), '{n}.collection_end', 'asc');
			
		//sort phase 3 projects by their cut-off date
		if(array_key_exists(PROJECT_FEEDBACK, $projects))
			$projects[PROJECT_FEEDBACK] = Set::sort(array_values($projects[PROJECT_FEEDBACK]), '{n}.feedback_end', 'asc');
		
		//set archived projects.
		$this->Project->recursive = 0;
		$this->paginate['Project']['order'] = "Project.feedback_end DESC";
		$projects[PROJECT_ARCHIVE] = $this->paginate(array('Project.status' => PROJECT_ARCHIVE, 'Project.id' => $list));
		
		$this->set(compact('projects'));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Project->create();
			if ($this->Project->save($this->data)) {
				$this->Session->setFlash(__('The project has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.', true));
			}
		}else{
			//make it so that the person who's creating is one of the admin by default.
			$this->data['Admin']['Admin'] = array($this->Auth->user('group_id'));
		}
		$admins = $this->Project->Admin->find('list', array('group_id' => array(ROLE_SU, ROLE_ADMIN)));
		
		
		$this->set(compact('admins'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid project', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Project->save($this->data)) {
				$this->Session->setFlash(__('The project has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Project->read(null, $id);
		}
		$admins = $this->Project->Admin->find('list');
		$this->set(compact('admins'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for project', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Project->delete($id)) {
			$this->Session->setFlash(__('Project deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Project was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>