<?php
class ProjectsController extends AppController {

	var $name = 'Projects';
	function beforeFilter(){
		//allow this to happen with no authentication, to speed it up.
		$this->Auth->allowedActions = array('admin_add_members_status');
	}
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

	function admin_dashboard($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid project', true));
			$this->redirect(array('action' => 'index'));
		}
		$project = $this->Project->read(null, $id);
		//TODO: to check if the accessing admin is actually the owner of the project and deny access if he's not.
		$this->set('project', $project);
	}
	
	function admin_add() {
		if (!empty($this->data)) {
			$this->Project->create();
			$status = $this->Project->saveAll($this->data);
			if ($status) {
				$name = Set::classicExtract($this->data, 'Upload.0.file.name');
				if(empty($name)){
					$this->Session->setFlash(__('The project has been saved', true));
					$this->redirect(array('action'=>'dashboard'));
				}else{
					$this->redirect(array(
						'action' => 'add_members', 
						'admin' => true, 
						$this->Project->getLastInsertId(),
						$this->Project->Upload->getLastInsertId()
					));
				}
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.', true));
			}
		}else{
			//make it so that the person who's creating is one of the admin by default.
			$this->data['Admin']['Admin'] = array($this->Auth->user('group_id'));
		}
		$admins = $this->Project->Admin->find('list');
		
		
		$this->set(compact('admins'));
	}
	
	function admin_add_members($projectId, $uploadId){
		if(empty($projectId) || empty($uploadId)){
			$this->Session->setFlash(__("Invalid project or csv file provided", true));
			$this->redirect(array('action' => 'index'));
		}
		//TODO: check whether current admin is one of the admin who is allowed to edit this project.
		//		and redirect if admin doesn't have access.
		
		//AT POST
		if(!empty($this->data) && $this->_isAjax()){
			$importData = $this->Project->getImportData($this->data);
			$returnData = $this->_processImport($importData);
			
			exit;
		}
		
		
		
		//AT GET
		//read the project file.
		$this->Project->id = $projectId;
		$this->Project->recursive = -1;
		$project = $this->Project->read();
		if(empty($project)){
			$this->Session->setFlash(__("Invalid project.", true));
			$this->redirect(array('action' => 'index'));
		}
		
		//read the file that has been uploaded.
		$filename = $this->Project->Upload->field('name',array('id'=> $uploadId));
		if(empty($filename)){
			$this->Session->setFlash(__("Invalid csv file provided", true));
			$this->redirect(array('action' => "dashboard", $projectId));
		}
		$importedFields = $this->Project->Upload->listHeaders($uploadId);
		$this->set('importedFields', $importedFields);
		
		$userTableFields = $this->Project->Member->getImportableFields();
		$this->set('userTableFields', $userTableFields);
		
		$this->data = array(
			'Project' => array('id' => $projectId),
			'Upload' => array('id' => $uploadId)
		);
	}
	
	function admin_add_members_status(){
		//fake progress
		$progress = $this->Session->read('Import.progress');
		$this->Session->write('Import.progress', $progress + 20);
		//fake progress ends
		
		$this->layout = 'ajax';
		$data = array(
			'progress' => $this->Session->read('Import.progress'),
			'total' => $this->Session->read('Import.total')
		);
		$this->set('data', $data);
		$this->render('/ajaxreturn');
	}
	
	function admin_settings($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid project', true));
			$this->redirect(array('action' => 'index'));
		}
		//TODO: to check if the accessing admin is actually the owner of the project and deny access if he's not.
		
		if (!empty($this->data)) {
			if ($this->Project->save($this->data)) {
				$this->Session->setFlash(__('The project has been saved', true));
				$this->redirect(array('action' => 'dashboard', $id));
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
	
	function _processImport($data){
		//$this->Session->write('Import.total', count($data));
		$this->Session->write('Import.total', 100); //FAKE
		$this->Session->write('Import.progress', 0);
		foreach($data as $member){
			$status = $this->Project->Member->import($data);
		}
	}
}
?>