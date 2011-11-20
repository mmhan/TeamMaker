<?php
/* Skill Test cases generated on: 2011-11-18 14:11:17 : 1321627277*/
App::import('Model', 'Skill');
App::import("Vendor", 'DebugKit.FireCake');
class SkillTestCase extends CakeTestCase {
	var $fixtures = array('app.skill', 'app.project', 'app.upload', 'app.admin', 'app.group', 'app.user', 'app.admins_project', 'app.member', 'app.members_project', 'app.members_skill');
	
	function start(){
		$this->Skill =& ClassRegistry::init("Skill");
	}
	function end(){
		unset($this->Skill);
		ClassRegistry::flush();
	}
	function startTest() {
		$this->Skill =& ClassRegistry::init('Skill');
	}

	function endTest() {
		unset($this->Skill);
		ClassRegistry::flush();
	}
	
	function testValidateRangeWithType(){
		$data = array(
		array(
			'type' => SKILL_NUMERIC_RANGE,
			'name' => "Numeric Range",
			'range' => '0.0-2.0',
		),
		array(
			'type' => SKILL_NUMERIC_RANGE,
			'name' => "Numeric Range",
			'range' => '0-1',
		),
		array(
			'type' => SKILL_NUMERIC_RANGE,
			'name' => "Numeric Range",
			'range' => '1.5-2'
		),
		array(
			'type' => SKILL_NUMERIC_RANGE,
			'name' => "Numeric Range",
			'range' => 'abc-2'
		),
		array(
			'type' => SKILL_NUMERIC_RANGE,
			'name' => "Numeric Range",
			'range' => 'abc-2.0'
		));
		
		$dToSave = array('Skill' => $data[0]);
		$status = $this->Skill->save($dToSave);
		$this->assertNotEqual($status, false, "validate should allow float and float as min and max");
		
		$dToSave = array('Skill' => $data[1]);
		$status = $this->Skill->save($dToSave);
		$this->assertNotEqual($status, false, "validate should allow int and int as min and max");
		
		$dToSave = array('Skill' => $data[2]);
		$status = $this->Skill->save($dToSave);
		$this->assertNotEqual($status, false, "validate should allow float and int as min and max");
		
		$dToSave = array('Skill' => $data[3]);
		$status = $this->Skill->save($dToSave);
		$this->assertEqual($status, false, "validate should not allow txt and int as min and max");
		
		$dToSave = array('Skill' => $data[4]);
		$status = $this->Skill->save($dToSave);
		$this->assertEqual($status, false, "validate should not allow txt and float as min and max");
		
		$status = $this->Skill->save(array(
			'Skill' => array(
				'type' => SKILL_TEXT_RANGE,
				'range' => "abc"
			)
		));
		$this->assertEqual($status, false, "validate should not allow text range with only one option.");
		
		$status = $this->Skill->save(array(
			'Skill' => array(
				'type' => SKILL_TEXT_RANGE,
				'range' => "abc|def"
			)
		));
		$this->assertNotEqual($status, false, "validate should allow text range with more than one option.");
		
		$status = $this->Skill->save(array(
			'Skill' => array(
				'type' => SKILL_TEXT,
				'range' => "abc"
			)
		));
		$this->assertEqual($status, false, "validate should not allow text with non-integer as range");
		
		$status = $this->Skill->save(array(
			'Skill' => array(
				'type' => SKILL_TEXT,
				'range' => "20"
			)
		));
		$this->assertNotEqual($status, false, "validate should allow text with integer as range");
	}

}
?>