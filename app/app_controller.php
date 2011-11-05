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
	
	function beforeFilter(){
		//allow display action(which is used to render 'pages') so that it's publicly accessible.
		if (!empty($this->Auth->allowedActions)) {
	            $this->Auth->allowedActions[] = 'display';
		} else {
	            $this->Auth->allowedActions = array('display');
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