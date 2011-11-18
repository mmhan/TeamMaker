<?php
/* Skill Test cases generated on: 2011-11-18 14:11:17 : 1321627277*/
App::import('Model', 'Skill');

class SkillTestCase extends CakeTestCase {
	var $fixtures = array('app.skill', 'app.project', 'app.upload', 'app.admin', 'app.group', 'app.user', 'app.admins_project', 'app.member', 'app.members_project', 'app.members_skill');

	function startTest() {
		$this->Skill =& ClassRegistry::init('Skill');
	}

	function endTest() {
		unset($this->Skill);
		ClassRegistry::flush();
	}

}
?>