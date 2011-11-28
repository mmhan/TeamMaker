<?php
class TeamsController extends AppController {

	var $name = 'Teams';

	function admin_make($project_id){
		
		
		
		if (!empty($this->data)) {
			
		}
		
		$this->set($this->Team->findMakeData($project_id));
	}
}
?>