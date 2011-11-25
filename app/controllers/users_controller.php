<?php
/**
 * contains UsersController
 *
 * @author 		@mmhan
 * @version 	$Id$
 * @copyright 	MIT Licence
 * @package 	teammaker.controllers
 */


/**
 * UsersController for the app. Handles CRUD, authentication
 * and ACL. 
 * 
 * Also contains methods to generate ACL. 
 *
 * @package 	default
 * @author  	@mmhan
 * @package		teammaker.controllers
 */ 

class UsersController extends AppController {

    var $name       = 'Users';
    var $helpers    = array('Html', 'Form', 'Javascript', 'Text', 'Time', 'Session');
    var $components = array('Auth', 'Session');
	var $titles		= array(
		'login' => array("Login", "User"),
		'edit' => array("Edit Profile"),
		'reset_password' => array('Reset Password'),
		'forgot_password' =>array('Forgot Password')
	);
	
    /**
     * beforeFilter callback
     * 
	 * (non-PHPdoc)
	 * @see app/AppController#beforeFilter()
	 */
    function beforeFilter () {
        parent::beforeFilter();
		$this->Auth->allowedActions = array(
			'login', 'logout', 'regen_acode', 'forgot_password', 'reset_password', 'profile'
			//TODO: comment below in production.
			,'build_acl', 'init_db'
		);
    }
    
    
 	/**
 	 * Login the user using ajax or direct visit.
 	 */
    function login () {
		$isPost = !empty($this->data) ? TRUE : FALSE;
		
		//if it is posted ajax or not, try logging him in.
		if ($isPost) {
            $status = ($this->Auth->login() || $this->Auth->user());
            $this->set('status', $status);
        }else{
        	//set the status to false
        	$this->set('status', false);
			
			//this is for remember me functionality
        	$cookie = $this->Cookie->read('Auth.User');
			if(!is_null($cookie)){
				$cookieLogin = $this->Auth->login($cookie);
				if($cookieLogin) {
					//Clear auth message, just in case we use it.
					$this->Session->delete('Message.auth');
					$redirectTo = isset($this->params['url']['redirect']) ? $this->params['url']['redirect'] : $this->Auth->redirect();
					
					//also remember user's avatar in session.
					$userId = $this->Auth->user('id');
					$this->User->updateLastLogin($userId);
					
					$this->redirect("/" . $redirectTo);
				} else { // Delete invalid Cookie
					$this->Cookie->delete('Auth.User');
				}
			}
        }
		
		
        $loginError = $this->Auth->loginError;
		
		//set the data to view
        $this->set(compact('loginError', 'isPost'));
        
		//remember_me cookie write.
		if(isset($status) && $status && $isPost){
			$cookie = array();
			if($this->data['User']['remember_me']){
				$cookie['email'] = $this->data['User']['email'];
				$cookie['password'] = $this->data['User']['password'];
				$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
				unset($this->data['User']['remember_me']);				
			}
		}
		
        //also update last login of user.
    	if(isset($status) && $status){
        	$userId = $this->Auth->user('id');
			$this->User->updateLastLogin($userId);
        }
        
        //if user has just logged-in, redirect to auto redirect location or the default redirect location.
        if($isPost && $status){
        	if($redirectTo = $this->Session->read('Auth.redirect')){
        		$this->redirect('/' .$redirectTo);
        	}else{
        		$this->redirect($this->Auth->loginRedirect);
        	}
        }
        if(!$isPost && $this->Auth->user()){
        	$this->redirect($this->Auth->loginRedirect);
        }
    }


    /**
     *
     * User's logout function
     */
    function logout () {
        $this->Session->destroy();
        $this->Session->setFlash("Bye!");
		
		//if there's any remember me option delete it.
		if($this->Cookie->read('Auth.User')){
			$this->Cookie->delete("Auth.User");
		}
		
        $this->redirect($this->Auth->logout());
    }
    
	/**
	 * Verify User activation code function
	 * 
	 * @param	int		id of the user.
	 * @param 	string	hash to verify against
	 * @return 	void
	 */
	/**
	function activate ($userId = 0, $code = '') {
		if (!empty($userId) && !empty($code) && $this->User->verify($userId, $code)) {
			$this->User->activateUser($userId, true);
			$this->flash("You have successfully activated your account.", array('controller' => 'users', 'action' => 'login'));
		} else {
			// error
			$errMsg = "Invalid activation code.";
			$this->_setTitle($errMsg);
			$this->set('errMsg', $errMsg);
		}
	}
	**/
    /**
     * Change the session data to the updated one if user profile has been updated.
     * @param $data
     * @return unknown_type
     
    function _addSessionData ($data) {
    	if($data){
    		if(isset($data['Avatar']['filename']) && !empty($data['Avatar']['filename'])){
    			$data['User']['avatar'] = $data['Avatar']['filename'];
    		}
    		$this->Session->write('Auth.User', $data['User']);
    	}
    }*/
    
    /**
	 * Edit own's account
	 */
    function edit(){
    	$userId = $this->Auth->user('id');
		
		if(!$userId){
			$this->Session->setFlash(__('Invalid User', true));
            $this->redirect('/');
		}
		
		if(!empty($this->data)){
			//if password field is blank don't update password.
        	if (empty($this->data['User']['password']) || $this->data['User']['password'] == $this->Auth->password('')){
        		unset($this->data['User']['password']);
        	}else{//if it's not blank, prepare to update it.
        		$this->data['User']['current_password'] = $this->Auth->password($this->data['User']['current_password']);
        		//$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
	    		$this->data['User']['hashed_confirm_password'] = $this->Auth->password($this->data['User']['confirm_password']);
        	}
			
			if ($this->User->save($this->data)) {
            	$this->Session->setFlash(__('Your account has been saved', true));
				$this->redirect('/');
			} else {
            	$this->Session->setFlash(__('Your account could not be saved. Please, try again.', true));
			}
		}else{
	        $this->data = $this->User->read(null, $userId);
		}
    }
    
    /**
     * log user in
     *
     * @access public
     */
    function admin_login () {
		$this->redirect('/users/login');
    }

	/**
	 * The following function populate the acos_aros table. 
	 * Handy for development.
	 * 
	 * @return unknown_type
	 */
	function init_db() {
	    $group =& $this->User->Group;
	    
	    //Allow super_admins to everything
	    $group->id = ROLE_SU;
	    	$this->Acl->allow($group, 'controllers');
		
		$group->id = ROLE_ADMIN;
			$this->Acl->allow($group, 'controllers');
		
        $group->id = ROLE_MEMBER;
			$this->Acl->deny($group, 'controllers');
			$this->Acl->allow($group, 'controllers/Users/edit');
			$this->Acl->allow($group, 'controllers/Projects/index');
	}

        
	/**
	 * The following functions help in development by creating ACOs for the whole app.
	 * TONOTE: by running the following, it'll add the new controller actions as ACO. But it doesn't remove the actions that are no longer there.
	 */
	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		//TODO: Only put in required controllers to index.
		//$Controllers = array('Images');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';
		debug($Controllers);
		//TODO: Remove comment for the following if plugins are to be indexed for acos as well.
		//$Plugins = $this->_getPluginControllerNames();
		//$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}


	function _getClassMethods($ctrlName = null) {
            App::import('Controller', $ctrlName);
            if (strlen(strstr($ctrlName, '.')) > 0) {
                // plugin's controller
                $num = strpos($ctrlName, '.');
                $ctrlName = substr($ctrlName, $num+1);
            }
            $ctrlclass = $ctrlName . 'Controller';
            $methods = get_class_methods($ctrlclass);

            // Add scaffold defaults if scaffolds are being used
            $properties = get_class_vars($ctrlclass);
            if (array_key_exists('scaffold',$properties)) {
                if($properties['scaffold'] == 'admin') {
                    $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
                } else {
                    $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
                }
            }
            return $methods;
	}

	function _isPlugin($ctrlName = null) {
            $arr = String::tokenize($ctrlName, '/');
            if (count($arr) > 1) {
                return true;
            } else {
                return false;
            }
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

	/**
	 * Get the names of the plugin controllers ...
	 * 
	 * This function will get an array of the plugin controller names, and
	 * also makes sure the controllers are available for us to get the 
	 * method names by doing an App::import for each plugin controller.
	 *
	 * @return array of plugin names.
	 *
	 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}
	
	/**
	 * index of all users
	 * @access	only for SU and admin
	 **/ 
	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
		$this->set('groups', $this->User->Group->find('list'));
	}
	
	/**
	 * Adds users
	 *
	 * @return void
	 * @author 	@mmhan
	 */
	function admin_add() {
		$user = $this->Auth->user();
		$group_id;
		
		/**
		 * At GET
		 */
		if(empty($this->data)){
			if(empty($this->params['named']['group_id'])){
				//fail-safe redirect for cases that role wasn't given.
				$this->redirect(array('controller' => 'users', 'action' => 'add', 'admin' => true, 'group_id' => ROLE_ADMIN));
			}else if($this->params['named']['group_id'] == ROLE_SU && $user['User']['group_id'] != ROLE_SU){
				//if non SU try to add SU
				$this->redirect(array('controller' => 'users', 'actions' => 'add', 'admin' => true, 'group_id' => ROLE_ADMIN));
			}else{
				$group_id = $this->params['named']['group_id'];
			}
		}
		
		/**
		 * At POST
		 */
		if (!empty($this->data)) {
			$this->User->create();
			
			$this->data['User']['hashed_confirm_password'] = $this->Auth->password($this->data['User']['confirm_password']);
			
			$status = false;
			
			//check to see if project_id was also provided.
			//cases coming from /admin/projects/members/$id
			$projectId = isset($this->data['Project']['Project'][0]) && !empty($this->data['Project']['Project'][0]) ? $this->data['Project']['Project'][0] : false;
			if($projectId){
				$status = $this->User->saveAll($this->data);
			}else{
				$status = $this->User->save($this->data);
			} 
			
			//save and redirect
			if ($status) {
				$this->Session->setFlash(__('The user has been saved', true));
				if($projectId){
					$this->redirect(array('controller' => 'projects', 'action' => 'members', $projectId));
				}else{
					$this->redirect(array('action' => 'index'));
				}
				
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$this->set('groups', $this->User->Group->find('list'));
		$this->set('group_id', $group_id);
	}

	/**
	 * To edit an existing user
	 **/
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if($this->data['User']['password'] != $this->Auth->password('')){
				$this->data['User']['hashed_confirm_password'] = $this->Auth->password($this->data['User']['confirm_password']);
				$this->User->disableValidate("resetPassword");
			}else{
				unset($this->data['User']['password']);
			}
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$this->set('groups', $this->User->Group->find('list'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}// END
