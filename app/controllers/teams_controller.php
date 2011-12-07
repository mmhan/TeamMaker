<?php
class TeamsController extends AppController {

	var $name = 'Teams';

	function admin_make($project_id){
		
		if (!empty($this->data)) {
			
		}
		
		$this->set($this->Team->findMakeData($project_id));
		$this->set("projectId", $project_id);
	}
	
	function admin_save($project_id = null){
		$data = array();
		$this->layout = "ajax";
		if($project_id == null || empty($this->data)){
			$data['status'] = false;
		}else{
			$saveData = array();
			$saveStatus = array();
			
			$existingTeams = Set::extract($this->Team->find('all', array(
				'fields' => array('Team.id', 'Team.project_id'),
				'conditions' => array('project_id' => $project_id),
				"recursive" => -1
			)), "{n}.Team.id");
			
			
			foreach($this->data['Teams'] as $i => $team){
				$id = array_shift($existingTeams);
				if($id) $saveData[$i]['Team']['id'] = $id;
				$saveData[$i]['Team']['project_id'] = $project_id;
				$saveData[$i]['Member']["Member"] = $team;
				$saveStatus[$i] = $this->Team->saveAll($saveData[$i]);
			}
			$data['status'] = $saveStatus;
		}
		$this->set('data', $data);
		$this->render("/ajaxreturn");
	}
}
?>