<?php
/**
 * Base app controller, all your controllers inherit this class
 * 
 * @author		  rynop and the paypal IPN pieces are thanks to webtechnick's example
 * @link          http://rynop.com, http://github.com/webtechnick/CakePHP-Paypal-IPN-Plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class AppController extends Controller {

	var $helpers = array('Html', 'Form', 'Session', 'Asset.asset');
	var $components = array(
		'Acl','Auth',
		'Session','Cookie',
	   	'DebugKit.Toolbar' => array('panels' => array('history' => false))
	);	
	
	/**
	* @var array array('function_name' => array('Users', "Reset Password")) will result title "User : Reset Password : MuzicDB"
	*/
    var $titles = array();
	
	function beforeFilter(){
		//Configure AuthComponent
        $this->Auth->authorize      = 'actions';
        $this->Auth->userModel      = 'User';
		$this->Auth->loginAction    = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect  = array('controller' => 'pages', 'action'=> 'display', 'home' );
		$this->Auth->loginError     = "Invalid Login, please try again.";
        $this->Auth->authError      = "You are not authorized to access " . Router::url('/' . $this->params['url']['url'], true);
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->autoRedirect   = false; // allow Auth to redirect to the previous page
		$this->Auth->fields         = array('username' => 'email', 'password' => 'password');
		//let AuthComponent know that there's ACO object root, that is global control.
        $this->Auth->actionPath = 'controllers/';
		
		//allow display action(which is used to render 'pages') so that it's publicly accessible.
		if (!empty($this->Auth->allowedActions)) {
	            $this->Auth->allowedActions[] = 'display';
		} else {
	            $this->Auth->allowedActions = array('display');
		}
		
		//cookie set up
	    $this->Cookie->name = 'teammaker';
		$this->Cookie->time =  3600;  // or '1 hour'
		
		//this is for remember me functionality
		$cookie = $this->Cookie->read('Auth.User');
		$user = $this->Auth->user();
		if(!is_null($cookie) && empty($user)){
			if(!$this->Session->read("Auth.redirect")){
				$this->Session->write('Auth.redirect', true);
				$this->redirect("/users/login");
			}else{
				$this->Session->delete('Auth.redirect');
			}
		}
		
		//set default title
		$this->_setTitle();
	}
	
	/**
     * Set canned titles for the actions
     * 
     * @param	mixed	as array('Users', 'Reset Password') will result title "User : Reset Password "  
     * 					as string 'Users : Reset Password'  will result title "User : Reset Password "
     */
    function _setTitle ($titles = array()) {
        if (!empty($titles)) {
            $this->titles[$this->action] = $titles;
        }
        
        if (isset($this->titles) && !empty($this->titles[$this->action])) {
            if (is_array($this->titles[$this->action])) {
                $this->set('title_for_layout', implode($this->titles[$this->action], ' : '));
            } else {
                $this->set('title_for_layout', $this->titles[$this->action]);
            }
        }
    }
	
	/**
     * Misc function to check if it was an ajax call.
     * @return boolean
     */
    function _isAjax () {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}
?>