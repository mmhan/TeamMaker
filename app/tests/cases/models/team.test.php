<?php
/* Team Test cases generated on: 2011-11-27 21:11:30 : 1322399310*/
App::import('Model', 'Team');

class TeamTestCase extends CakeTestCase {
	var $fixtures = array('app.team', 'app.project', 'app.skill', 'app.member', 'app.group', 'app.user', 'app.members_skill', 'app.members_project', 'app.upload', 'app.admin', 'app.admins_project', 'app.members_team');

	function startTest() {
		$this->Team =& ClassRegistry::init('Team');
	}

	function endTest() {
		unset($this->Team);
		ClassRegistry::flush();
	}

}
?>