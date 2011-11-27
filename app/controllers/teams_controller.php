<?php
class TeamsController extends AppController {

	var $name = 'Teams';

	function make($project_id){
		
		if (!empty($this->data)) {
			
		}
		
		
		$this->set(compact('projects', 'members'));
	}
}
?>