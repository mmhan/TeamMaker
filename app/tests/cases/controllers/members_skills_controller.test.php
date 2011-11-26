<?php
/* MembersSkills Test cases generated on: 2011-11-26 17:11:11 : 1322300171*/
App::import('Controller', 'MembersSkills');

class TestMembersSkillsController extends MembersSkillsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class MembersSkillsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.members_skill', 'app.skill', 'app.project', 'app.upload', 'app.admin', 'app.group', 'app.user', 'app.admins_project', 'app.member', 'app.members_project');

	function startTest() {
		$this->MembersSkills =& new TestMembersSkillsController();
		$this->MembersSkills->constructClasses();
	}

	function endTest() {
		unset($this->MembersSkills);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>