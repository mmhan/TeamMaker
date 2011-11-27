<?php
/* Teams Test cases generated on: 2011-11-27 21:11:58 : 1322399338*/
App::import('Controller', 'Teams');

class TestTeamsController extends TeamsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TeamsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.team', 'app.project', 'app.skill', 'app.member', 'app.group', 'app.user', 'app.members_skill', 'app.members_project', 'app.upload', 'app.admin', 'app.admins_project', 'app.members_team');

	function startTest() {
		$this->Teams =& new TestTeamsController();
		$this->Teams->constructClasses();
	}

	function endTest() {
		unset($this->Teams);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>