<?php
class User extends AppModel {
	var $name	= 'User';

	var $belongsTo	= array('Group');
	var $actsAs = array(
		'Acl' => 'requester',
		'Containable'
	);
	
	var $validate = array(
		'given_id' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,
				'message' => 'The Given ID you have provided is already in use, please use a different one.'
			)
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'allowEmpty' => false,
				'message' => 'Please enter a valid email.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,
				'on' => 'create',
				'message' => 'The email you\'ve selected is already in use, please use a different one.'
			)
		),
		'username' => array(
			'alphaNumericAndSome' => array(
				'rule' => RULE_USERNAME,
				'allowEmpty' => false,
				'on' => 'create',
				'message' => 'Please enter a valid username with only small letter characters, numbers, underscore(_) and/or dot(.) are accepted.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,
				'on' => 'create',
				'message' => 'The username you\'ve selected is already in use, please use a different one.'
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'allowEmpty' => false,
				'on' => 'create',
				'message' => 'The username must have at least 4 characters.'
			)
		),
		'password' => array(
			'confirm' => array(
				'rule' => array('confirm'),
				'message' => 'Please make sure the password provided is the same in both fields',
				'allowEmpty' => false
			),
			'minLength' => array(
				'rule' => array('passwordMinLength', 6),
				'message' => 'Please make sure the password provided is at least 6 characters long',
				'allowEmpty' => false
			),
			'currentPasswordIsCorrect' => array(
				'rule' => array('currentPasswordIsCorrect'),
				'message' => 'Current password you have provided is not correct. Please try again.',
				'on' => 'update'
			)
		),
	);
	/**
	 * Find the parent node of the user and returns. Used for acl.
	 *  
	 * @return array
	 */
	function parentNode() {
	    if (!$this->id && empty($this->data)) {
	        return null;
	    }
	    $data = $this->data;
	    if (empty($this->data['User']['group_id'])) {
	        $data = $this->read();
	    }
	    if (!array_key_exists('group_id', $data['User']) && !$data['User']['group_id']) {
	        return null;
	    } else {
	        return array('Group' => array('id' => $data['User']['group_id']));
	    }
	}


	/**
	 * After save callback
	 *
	 * Update the aro for the user.
	 *
	 * @access public
	 * @return void
	 */
	function afterSave($created) {
        if (!$created) {
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
            $this->Aro->save($aro);
			
			$key = 'CreatedBy_' . $this->data['User']['id'];
			Cache::delete($key);
        }
	}
	
	/**
	 * Will take care of validating fields that requires confirmations.
	 * 
	 * @param	array	An array containing the value of the field that needs to be validated.
	 * @return	boolean
	 */
	function confirm($field = array()){
		foreach($field as $key => $value){
			if($key == 'password'){
				if(empty($this->data[$this->name]['hashed_confirm_' . $key]) || empty($this->data[$this->name]['confirm_' . $key])){
					return false;
				}
			}else{
				if(empty($this->data[$this->name]['confirm_' . $key])){
					return false;
				}
			}
			
			$v1 = $value;
			$v2 = '';
			if($key == 'password'){
				$v2 = $this->data[$this->name]['hashed_confirm_' . $key];
			}else{
				$v2  = $this->data[$this->name]['confirm_' . $key];				
			}
			return ($v1 === $v2); 
		}
	}
	/**
	 * This function will validate in making sure that the new passwords have minLength of given number.
	 * 
	 * @param	array	An array containing the value of the field that needs to be validated.
	 * @param 	int	minmum required length of password.
	 * @return	boolean	
	 */
	function passwordMinLength($field = array(), $length = null){
		foreach ($field as $key => $value){
			
			//if there's no confirm_password field then return false
			if(!isset($this->data[$this->name]['confirm_' . $key])) return false;
			
			$pwValue = $this->data[$this->name]['confirm_' . $key];
			return strlen($pwValue) >= $length;
		}		
	}
	
	/**
	 * This function will validate in making sure that current_password field is the same as the one that's stored in the db.
	 * Solely for use in change_password.
	 * 
	 * @param	array	An array containing the value of the field that needs to be validated.
	 * @return	boolean
	 */
	function currentPasswordIsCorrect($field = array()){
		foreach ($field as $key => $value){
			//find the value of the current password
			if ($key != 'password') return false;
			$pwValue = $this->data[$this->name]['current_password'];
			$correctValue = $this->field('password');
			return ($pwValue === $correctValue);
		}
	}
	
	
	/**
	 * generate hash code and return
	 * 
	 * @param	mixed	as array: User's data. Require only id and status.
	 * 					as int: User's id.
	 */
	function generateCode($user = array()) {
		if(!is_array($user) && is_int($user)){
			$this->recursive = -1;
			$user = $this->read(array('id', 'status'), $user);
		}
		$saltValue = Configure::read('Security.salt');
		$code = array();
		$code["User"]["hash"] = md5($user["User"]["id"] . $user["User"]["status"] . $saltValue);
		$code["User"]["hash_generated"] = date("Y-m-d H:i:s");
		
		$this->id = $user["User"]["id"];
		$this->save($code, false);
		return $code["User"]["hash"];
	}
	
	
	/**
	 * verify given hash code
	 * 
	 * @param	int	id of the user.
	 * @param 	string	hash
	 * @param 	boolean	true to clear the field of the user table, false to not. default to true.
	 * @return	boolean
	 */
	function verify ($userId, $hash, $clear = true) {
		$user = $this->read(array('hash', 'hash_generated'),$userId);
		if (empty($user)) {
			return false;
		}
		
		$timeNow = time();
		$timeGenerated = strtotime($user["User"]["hash_generated"]);
		// check if within 24 hours
		if (($timeNow < ($timeGenerated + ONE_WEEK)) && ($user["User"]["hash"] == $hash)) {
			return true;
		} else {
			// err
			return false;
		}
	}
	
	/**
	 * function used to pick and choose the validation methods based on use cases.
	 * 
	 * @param	string	type of action the model is going to perform.
	 * @return	boolean	true if it was in specified actions, false if it wasn't.
	 */
	function disableValidate ($type) {
		switch ($type) {
			case "resetPassword" : 
				unset($this->validate['password']['currentPasswordIsCorrect']);
				break;
			default :
				return false;
		}
		return true;
	}
	
	/**
	 * Will remeber last login time.
	 */
	function updateLastLogin($id = false){
		if(!$id) $id = $this->id;
		if(!$id) return false;
		
		$this->id = $id;
		$status = $this->saveField('last_login_time', date("Y-m-d H:i:s"));
		return $status;
	}
}
?>