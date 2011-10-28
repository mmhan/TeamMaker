<?php
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
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
    }

    /**
     * Public profile
	 * TODO: change to email.
     * 
     * @access public
     */
    function profile ($userName = false){
		//if there's no user name check for currently logged-in user, and show his profile.
    	if(!$userName){
    		$userName = $this->Auth->user('username');
			//if not logged in, redirect to login page.
    		if(!$userName) $this->redirect(array('action' => 'login'));
			//if logged in redirect to his page.
    		$this->redirect(array('action' => 'profile', $userName));
    	}
		//if user name is still not given, (considering it has been redirected.
    	if(empty($userName)){
    		$this->Session->setFlash(__('Invalid User', true));
    		$this->redirect('/');
    	}
		//find user data.
    	$user = $this->User->find('first', array(
    		'conditions' => array(
    			'username' => $userName
    		)
    	));
		//if user is not found show 404.
    	if(empty($user)){
    		$this->cakeError('error404');
    	}		
    	$this->set('user', $user);
    	$this->_setTitle($user['User']['display_name'] . "'s Profile");
    	$this->set('isOwner', $userName == $this->Auth->user('username'));
    }
    /**
     * For editing a person's own profile.
	 * 
     * @access	only logged in users.
     */
    function edit ($id = false) {
		//get logged in user's id
    	$userId = $this->Auth->user('id');
    	//if id is not given use logged in user's id.
    	if(empty($id)){
    		$id = $userId;
    	}
		
		//if log in user is not editing his own profile
		//this is invalid. redirect it.
    	if ($userId != $id || !$id && empty($this->data)) {
        	$this->Session->setFlash(__('Invalid User', true));
            $this->redirect(array('action' => 'edit'));
		}
		
        if (!empty($this->data)) {
        	//remove email field from data as long as it's not update-able
        	if(!empty($this->data['User']['email'])){
        		$email = $this->data['User']['email'];
        		unset($this->data['User']['email']);
        	}
        	
        	//if password field is blank don't update password.
        	if (empty($this->data['User']['password'])){
        		unset($this->data['User']['password']);
        	}else{//if it's not blank, prepare to update it.
        		$this->data['User']['current_password'] = $this->Auth->password($this->data['User']['current_password']);
        		$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
	    		$this->data['User']['hashed_confirm_password'] = $this->Auth->password($this->data['User']['confirm_password']);
        	}
			
        	if ($this->User->save($this->data)) {
        		$this->_addSessionData($this->User->read(null, $id));
            	$this->Session->setFlash(__('Your profile has been saved', true));
			} else {
            	$this->Session->setFlash(__('Your profile could not be saved. Please, try again.', true));
			}
			
			//put email back in, in case it has been removed from data (since user didn't want to update)
			if(isset($email) && $email){
				$this->data['User']['email'] = $email;
			}
		}
		// if data is empty (when Displaying form), get it from db and set.
		if (empty($this->data)) {
        	$this->data = $this->User->read(null, $id);
        }
    }
    
    
    /**
     * Reset Password using $userId and $hash 
     * 
     * @param	int	id of user to reset password
     * @param	string	hash.
     */
    function reset_password ($userId = 0, $hash = '') {
		//this is the variable that is to be sent to View, true means the token is correct, false incorrect.
    	$isMatch = false;
    	
    	//generate a token out of salt, time and userId to secure frontend manipulations.
    	$time = time();
    	$token = md5(Configure::read('Security.salt') . $time . (empty($userId) ? $this->data['User']['id'] : $userId));
    	$isPost = !empty($this->data);
    	
    	if($isPost){//for submits
    		if(
				//time and token are there.
    			isset($this->data['User']['time']) && isset($this->data['User']['token']) &&
				//user id is given.
    			isset($this->data['User']['id']) &&
				//and given token is correct token
    			$this->data['User']['token'] == md5(Configure::read('Security.salt') . $this->data['User']['time'] . $this->data['User']['id'])
    		){
				//when we are here, we can be sure that the user has the right to reset $this->data['User']['id']'s password
	    		$this->User->disableValidate("resetPassword");	//disable some of the validations for resetting password.
	    		$this->User->id = $this->data['User']['id'];
	    		
				//hash password and set 
				$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
				//has confirm password and set to confirm that user typed in correct password twice.
	    		$this->data['User']['hashed_confirm_password'] = $this->Auth->password($this->data['User']['confirm_password']);
	    		if ($this->User->save($this->data)) {
	    			$this->flash(
	    				"You have successfully changed your password.", 
	    				array(
	    					"controller" => "users",
	    					"action" => "login"
	    				)
	    			);
	    		}
	    		
				$isMatch = true;
    		}
    	}elseif (!empty($userId) && !empty($hash)) {//for visits coming in from email links.
    		$this->User->id = $userId;
    		$this->User->recursive = -1;
    		$user = $this->User->read(array('hash', 'hash_generated'));
    		if ($user && $user['User']['hash'] == $hash && strtotime($user['User']['hash_generated']) + ONE_WEEK > time()) {
    				$isMatch = true;
    				$this->data['User']['id'] = $userId;
    		}
    	}
    	
    	$this->data['User']['time'] = $time;
    	$this->data['User']['token'] = $token;
    	$this->set('isMatch', $isMatch);
    }

    
    /**
     * Ask for a link to reset password.
     */
    function forgot_password () {
    	$isPost = !empty($this->data) ? TRUE : FALSE;
    	
    	if ($isPost) {
    		//TODO:to use find() instead of findByEmail and use cache.
    		$this->User->recursive = -1;
    		$user = $this->User->findByEmail($this->data['User']['email']);
    		
    		// user exists
    		if ($user) {
    			$this->set('foundUser', true);
    			$this->set('emailStatus', $this->_send_code($user, 'resetPassword'));
    		}
    		else {
    			$this->set('foundUser', false);
    		}
    	}
    }
    
    
 	/**
 	 * Login the user using ajax or direct visit.
 	 */
    function login () {
		$isPost = !empty($this->data) ? TRUE : FALSE;
		$isAjax = $this->_isAjax();
		
		if ($isAjax) {
            $this->layout = 'ajax';
        }
		
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
		        	$avatar = $this->User->findAvatar($userId);
					$this->User->updateLastLogin($userId);
		        	$this->Session->write('Auth.User.avatar', $avatar['Avatar']['filename']);
					
					$this->redirect("/" . $redirectTo);
				} else { // Delete invalid Cookie
					$this->Cookie->delete('Auth.User');
				}
			}
        }
		
		
        $loginError = $this->Auth->loginError;
		//if there's login error and it is ajax, just don't keep the auth message.
		if($loginError && $isAjax) $this->Session->delete('Message.auth');
		//set the data to view
        $this->set(compact('isAjax', 'loginError', 'isPost'));
        
		//remember_me cookie write.
		if(isset($status) && $status && $isPost){
			$cookie = array();
			if($this->data['User']['remember_me']){
				$cookie['username'] = $this->data['User']['username'];
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
        if(!$isAjax && $isPost && $status){
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
		if($this->Cookie->read('Auth.User')) $this->Cookie->delete("Auth.User");
		
        $this->redirect($this->Auth->logout());
    }
    
	/**
	 * Verify User activation code function
	 * 
	 * @param	int		id of the user.
	 * @param 	string	hash to verify against
	 * @return 	void
	 */
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

    /**
     * Change the session data to the updated one if user profile has been updated.
     * @param $data
     * @return unknown_type
     */
    function _addSessionData ($data) {
    	if($data){
    		if(isset($data['Avatar']['filename']) && !empty($data['Avatar']['filename'])){
    			$data['User']['avatar'] = $data['Avatar']['filename'];
    		}
    		$this->Session->write('Auth.User', $data['User']);
    	}
    }
    
    /**
     * log user in
     *
     * @access public
     */
    function admin_login () {
        $isPost = isset($this->data) ? TRUE : FALSE;

        if ($isPost) {
            if ($this->Auth->login() || $this->Auth->user()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash("Incorrect login information!");
            }
        }
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
		
        $group->id = ROLE_USER;            
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
}
?>