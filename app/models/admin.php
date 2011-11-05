<?php 
/**
 * This model act as a model for admins of projects.
 * This is a more pattern-compliant way than to query on the controller side of projects.
 **/
App::import('Model', 'User');
class Admin extends User{
	var $name = "Admin";
	var $useTable = "users";
	
	function beforeFind($queryData){
		$queryData['conditions']['Admin.group_id'] = array(ROLE_SU, ROLE_ADMIN);
		return $queryData;
	}
}
?>