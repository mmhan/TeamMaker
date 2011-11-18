<?php
/* MembersSkill Test cases generated on: 2011-11-18 14:11:23 : 1321627463*/
App::import('Model', 'MembersSkill');

class MembersSkillTestCase extends CakeTestCase {
	var $fixtures = array('app.members_skill', 'app.skill', 'app.project', 'app.upload', 'app.admin', 'app.group', 'app.user', 'app.admins_project', 'app.member', 'app.members_project');

	function startTest() {
		$this->MembersSkill =& ClassRegistry::init('MembersSkill');
	}

	function endTest() {
		unset($this->MembersSkill);
		ClassRegistry::flush();
	}

}
?>