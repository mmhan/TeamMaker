<?php
class ProjectsController extends AppController {


	var $name = 'Projects';
	
	/**
	 * Before filter call back that customize a few things.
	 */
	function beforeFilter(){
		//allow this to happen with no authentication, to speed it up.
		//it is used by admin_add_members for the progress indicator.
		$this->Auth->allowedActions = array('admin_add_members_status');
	}
	
	/**
	 * This action will show a list of all projects to admins.
	 * 
	 */
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

	/**
	 * Show dashboard of a project.
	 */
	function admin_dashboard($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid project', true));
			$this->redirect(array('action' => 'index'));
		}
		$project = $this->Project->read(null, $id);
		//TODO: to check if the accessing admin is actually the owner of the project and deny access if he's not.
		$this->set('project', $project);
	}
	
	/**
	 * Add a new project.
	 */
	function admin_add() {
		if (!empty($this->data)) {
			$this->Project->create();
			$status = $this->Project->saveAll($this->data);
			if ($status) {
				$name = Set::classicExtract($this->data, 'Upload.0.file.name');
				if(empty($name)){
					$this->Session->setFlash(__('The project has been saved', true));
					$this->redirect(array('action'=>'dashboard', $this->Project->getLastInsertId()));
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

	
	/**
	 * This action is step2 of add project and 
	 * also edit members's redirect page after file upload.
	 *
	 * @return 	void
	 * @author	@mmhan  
	 */
	function admin_add_members($projectId, $uploadId){
		
		if(empty($projectId) || empty($uploadId)){
			$this->Session->setFlash(__("Invalid project or csv file provided", true));
			$this->redirect(array('action' => 'index'));
		}
		
		//TODO: check whether current admin is one of the admin who is allowed to edit this project.
		//		and redirect if admin doesn't have access.
		
		//AT POST
		if(!empty($this->data) && $this->_isAjax()){
			//get the transformed data and errors.
			$importData = $this->Project->getImportData($this->data);
			$skillImportError = $this->Project->importError;
			 
			//process the import, and its status.
			$returnData = $this->_processImport($importData);
			
			//combine the two errors, use skillImportErrors as warnings cos they aren't that critical
			$returnData = Set::merge($skillImportError, $returnData);
			
			$this->layout= "ajax";
			$this->set('data', $returnData);
			$this->set('fromImport', $this->data['Project']['from_import']);
			$this->set('projectId', $this->data['Project']['id']);
			$this->render('admin_add_members_at_post');
		}else{
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
			
			$this->set('importedFields', $this->Project->Upload->listHeaders($uploadId));
			
			$this->set('userTableFields', $this->Project->Member->getImportableFields());
			
			$this->set("skillFields", $this->Project->Skill->findAllSkills($projectId));
			
			$this->data = array(
				'Project' => array('id' => $projectId),
				'Upload' => array('id' => $uploadId)
			);
		}
	}
	
	/**
	 * To query the status of an import using ajax to show progress indicator.
	 */
	function admin_add_members_status(){	
		$progress = $this->Session->read('Import.progress');
		
		$this->layout = 'ajax';
		$data = array(
			'progress' => $this->Session->read('Import.progress'),
			'total' => $this->Session->read('Import.total')
		);
		$this->set('data', $data);
		$this->render('/ajaxreturn');
	}
	
	/**
	 * To edit the settings of a project.
	 */
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

	/**
	 * This will delete a project.
	 */
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

	/**
	 * This action will show a form where the admin will be able to 
	 * edit the list of members that belongs to the project.
	 */
	function admin_members($id = null){
		
		if(
			(empty($this->data) && $id == null ) || //GET
			(!empty($this->data) && !isset($this->data['Project']['id'])) //POST
		){
			$this->Session->setFlash(__("Invalid project.", true));
			$this->redirect(array('action' => 'index'));
		}
		
		if(!empty($this->data)){
			//AT POST
			
			//save the given data.
			$status = $this->Project->saveAll($this->data);
			
			if($status){
				$this->Session->setFlash("Project Saved.");
				$this->redirect(array('action' => 'members', $this->data['Project']['id']));
			}else{
				$this->Session->setFlash("Couldn't save data. Please try again.");
			}
		}else{
			//AT GET
						
			$this->data = $this->Project->find('first', array(
				'fields' => array('Project.id', 'Project.name'),
				'contain' => array("Member"),
				'conditions' => array('Project.id' => $id)
			));
						
			$this->set('members', $this->Project->Member->find('list', array('order' => "Member.name")));
		}
	}
	
	/**
	 * This will upload another csv file to import.
	 *
	 * @return void
	 * @author  @mmhan
	 */
	function admin_import_file($id = null) {
		
		if(empty($this->data)){
			$this->Session->setFlash(__('Invalid Request', true));
			$this->redirect(array('action' => 'index'));
		}else{
			//get uploaded file.
			$name = Set::classicExtract($this->data, 'Upload.0.file.name');
			$id = Set::classicExtract($this->data, "Project.id");
			
			if(empty($id)){
				//no id provided.
				$this->Session->setFlash(__('Invalid Request', true));
				$this->redirect(array('action' => 'index'));
				
			} else if (empty($name)) {
				//no file uploaded
				$this->Session->setFlash(__('Selected file couldn\'t be uploaded. Please try again.', true));
				$this->redirect(array('action'=>'members', $id));
				
			} else {
				//saved.
				$status = $this->Project->saveAll($this->data);
				if ($status) {
					$this->redirect(array(
						'action' => 'add_members', 
						'admin' => true, 
						$id,
						$this->Project->Upload->getLastInsertId(),
						'from_import' => true
					));
				} else {
					//save error.
					$this->Session->setFlash(__('The project could not be saved. Please, try again.', true));
					$this->redirect(array('action' => 'members', $id));
				}
			}
		}
	}

	/**
	 * This action will be used to edit skills once the project has been created.
	 *
	 * @return 	void
	 * @author  @mmhan
	 */
	function admin_skills($id = null) {
		if(
			(empty($this->data) && $id == null ) || //GET
			(!empty($this->data) && !isset($this->data['Project']['id'])) //POST
		){
			$this->Session->setFlash(__('Invalid Request', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if(!empty($this->data)){
			//AT POST
			$status = $this->Project->saveAll($this->data);
			
			if($status){
				$this->Session->setFlash("Project Saved.");
				$this->redirect(array('action' => 'dashboard', $this->data['Project']['id']));
			}else{
				$this->Session->setFlash("Couldn't save data. Please try again.");
			}
		}else{
			//AT GET
			$this->data = $this->Project->find('first', array(
				'fields' => array('Project.id', 'Project.name'),
				'conditions' => array('Project.id' => $id),
				'contain' => array('Skill')
			));
		}
		
	}
	
	/**
	 * This will process the way imports work.
	 */
	function _processImport($data){
		$this->Session->write('Import.total', count($data));
		$this->Session->write('Import.progress', 0);
		$status = array();
		foreach($data as $i => $member){
			//prep model
			$this->Project->Member->disableValidate('import');
			//import
			$status[$i]['status'] = $this->Project->Member->import($member);
			$status[$i]['error'] = $this->Project->Member->validationErrors;
			$this->Session->write('Import.progress', $i + 1);
		}
		return $status;
	}
}
?>