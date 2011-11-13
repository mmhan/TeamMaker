<?php 
/**
 * This model act as a model for members of projects.
 * This is a more pattern-compliant way than to query on the controller side of projects.
 **/
App::import('Model', 'User');
class Member extends User{
	var $name = "Member";
	var $useTable = "users";
	
	var $noImport = array(
		'id',
		'password',
		'group_id',
		'last_login_time', 'created', 'modified'
	);
	
	function beforeFind($queryData){
		$queryData['conditions']['Member.group_id'] = array(ROLE_MEMBER);
		return $queryData;
	}
	
	/** 
	 * get the schema and return only the importable fields
	 **/
	function getImportableFields(){
		$schema = $this->schema();
		
		foreach($this->noImport as $fieldname){
			unset($schema[$fieldname]);
		}
		$fields = array();
		foreach($schema as $field => $meta){
			$fields[$field] = Inflector::humanize($field);
		}
		return $fields;
	}
}
?>