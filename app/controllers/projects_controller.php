<?php
class ProjectsController extends AppController {


	var $name = 'Projects';
	
	var $components = array('Email');
	
	var $helpers = array('Time');
	
	/**
	 * Before filter call back that customize a few things.
	 */
	function beforeFilter(){
		
		$this->Auth->allowedActions = array(
			//allow cron to happen with no authentication, we don't need to.
			'cron',
			//allow add_memebers_status to happen with no authentication, to speed it up.
			//it is used by admin_add_members for the progress indicator.
			'admin_add_members_status'
		);
	}
	
	/**
	 * Index page of projects for Members to see the list of all the projects that they belong to.
	 *
	 * @return	void
	 * @author  @mmhan
	 */
	function index(){
		$user = $this->Auth->user('id');
		
		$user = $this->Auth->user('id');
		//get user's projects.
		$result = $this->Project->find('all', array(
			'recursive' => -1,
			'fields' => array('Project.id', 'Project.name'),
			'joins' => array(
				array(
					'table' => 'admins_projects',
					'alias' => 'AdminProject',
					'type' => 'LEFT',
					'foreignKey' => false,
					'conditions'=> array('Project.id = AdminProject.project_id','AdminProject.user_id =' . $user) 
				)
			)
		));
		$list = Set::classicExtract($result,'{n}.Project.id');
		
		//set all other projects.
		$currProjects = $this->Project->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'Project.status NOT' => array(PROJECT_SEED, PROJECT_ARCHIVE),
				'Project.id' => $list				
			)
		));
		$projects = Set::combine($currProjects, '{n}.Project.id', '{n}.Project', '{n}.Project.status');
			
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
	 * This action should be called by cron that's running preferably at every ten minutes.
	 * What this function will do is that 
	 * 		1)	it'll check all projects that are under collection phase and grouping phase and upgrade their status when necessary. 
	 * 
	 * This action also features a hack under debug mode and accepts a fake date under parameter `fake`.
	 * 		e.g: /projects/cron/fake:2012-01-01 (Y-m-d)
	 *
	 * @return void
	 * @author @mmhan
	 */
	function cron() {
		//look at the clock.
		$now = strtotime('now');
		
		//check for the hack
		if(Configure::read('debug') != 0 && isset($this->params['named']['fake'])){
			$now = strtotime($this->params['named']['fake']);
		}
		
		$this->Project->upgradeProjects();
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
		switch ($project['Project']['status']) {
			case PROJECT_SEED:
				$this->render("admin_dashboard_seed");
				break;
			case PROJECT_COLLECT:
				$this->set('remaining', $this->Project->findRemaining($id));
				$this->set("total", $this->Project->findTotal($id));
				$this->render("admin_dashboard_collect");
				break;
			case PROJECT_FEEDBACK:
				$this->set('remaining', $this->Project->findRemaining($id));
				$this->set("total", $this->Project->findTotal($id));
				$this->render("admin_dashboard_feedback");
				break;
			case PROJECT_ARCHIVE:
				$this->render("admin_dashboard_archive");
			default:
				break;
		}
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
			$this->data['Admin']['Admin'] = array($this->Auth->user('id'));
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
		
		$status = false;
		
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
			
			//for extending deadlines.
			
			//currently only handles going back to PROJECT_COLLECT
			if(!empty($this->params['named']['status_to'])){
				$status = $this->params['named']['status_to'];
				switch ($status) {
					case PROJECT_COLLECT:
						$this->data['Project']['status'] = PROJECT_COLLECT;
						$this->data['Project']['collection_end'] = date("Y-m-d 00:00:00", strtotime("+2 week"));
						$this->data['Project']['feedback_end'] = date("Y-m-d 00:00:00", strtotime("+4 week"));
						FireCake::log($this->data);
						break;
					default:
						break;
				}
			}
		}
		$admins = $this->Project->Admin->find('list');
		$this->set(compact('admins', 'status'));
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
	 * Viewless action to launch the project.
	 * 
	 */
	function admin_launch(){
		if(empty($this->data) || (!empty($this->data) && empty($this->data['Project']['id']))){
			$this->Session->setFlash(__('Invalid Request', true));
			$this->redirect(array('action' => 'index'));
		}
		
		//get project's data
		$project = $this->Project->find('first', array('conditions' => array('Project.id' => $this->data['Project']['id'])));
		
		//get all users
		$members = $this->Project->Member->findForLaunch($this->data['Project']['id']);
				
		//generating passwords for new users.
		if (!empty($members['new'])) {
			foreach ($members['new'] as $i => $m) {
				$m['Member']['g_password'] = $password = $this->_generatePassword($m);
				$m['Member']['password'] = $this->Auth->password($password);
				$members['new'][$i] = $m;
			}
			//reformat the data for saveAll()
			$saveData = Set::combine($members['new'], '{n}.Member.id', '{n}.Member');
			
			//save it
			$this->Project->Member->disableValidate("import");
			$status = $this->Project->Member->saveAll($saveData);
			
			if(!$status){
				$this->Session->setFlash("Project failed to launch. Passwords can't be generated.");
				//$this->redirect(array('action' => 'dashboard', $project['Project']['id']));
			}
		}
		
		//send email to everyone of them
		foreach($members as $type => $group){
			$isNew = ($type == 'new');
			foreach($group as $i =>  $member){
				$members[$type][$i]['email'] = $this->_sendLaunchMail($project, $member, $isNew);
			}
		}
		
		$this->Project->id = $project['Project']['id'];
		$this->Project->saveField("status", PROJECT_COLLECT);
		
		$this->Session->setFlash("Project successfully launched");
		$this->set('members', $members);
	}
	
	/**
	 * This will process the way imports work.
	 */
	function _processImport($data){
		$this->Session->write('Import.total', count($data));
		$this->Session->write('Import.progress', 0);
		$status = array();
		
		//Prep model
		$this->Project->Member->beforeImport();
		
		//Import.
		foreach($data as $i => $member){			
			//import
			$status[$i]['status'] = $this->Project->Member->import($member);
			$status[$i]['error'] = $this->Project->Member->User->validationErrors;
			$this->Session->write('Import.progress', $i + 1);
		}
		//clean up
		$this->Project->Member->afterImport();
		
		return $status;
	}
	
	/**
	 * This will take a member's data,
	 * generate a password for that person and return the password in plain text
	 *
	 * @return void
	 * @author  
	 */
	function _generatePassword($member) {
		return substr(
			MD5($member['Member']['name'] . $member['Member']['email'] . strtotime('now')), 0 , 6);
	}
	
	/**
	 * This function will send an email to given member
	 *
	 * @return  boolean		status
	 * @author  @mmhan
	 */
	function _sendLaunchMail($project, $member, $isNew) {
		$this->Email->reset();
        //prepare subject
        $this->Email->subject = '[TeamMaker] You have a New Project "' . $project['Project']['name'] . '"';
        //prepare from
        $this->Email->from = EMAIL_FROM;
        //prepare to
        $this->Email->to = $member['Member']['email'];
        //prepare template
        $this->Email->template = $isNew ? "launch_new" : "launch_existing";
        //send as both/text, use text only first.
        $this->Email->sendAs = 'text';
        //pass variables
        $this->set(compact('project', 'member'));
        
        //send email, only when server is in production, otherwise put it to session data.
        if(Configure::read("debug") != 0){
        	$this->Email->delivery = 'debug';
			$status = $this->Email->send();
			FireCake::info($this->Session->read("Message.email"));
			$this->Session->delete("Message.email");
			return $status;
        }else{
        	//uncomment this if required.
			//$this->_setSmtpOptions();
        	return $this->Email->send();
        }
	}
	
	/**
	 * This function will set up SMTP options (if required by server)
	 */
	function _setSmtpOptions(){
		if(isset($this->Email)){
            $this->Email->smtpOptions = array(
                'host' => SMTP_HOST,
                'port' => SMTP_PORT
            );
        }

	}
}
?>