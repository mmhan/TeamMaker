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

	function testIsValidValue(){
		foreach(range(1, 4) as $i)
			Cache::delete("Skill_" . $i);
		
		$this->assertFalse($this->Skill->isValidValue('0', -1), 'Given skill is non-existent');
		
		$this->assertTrue($this->Skill->isValidValue('1', 1), '1 is in 0-5');
		$this->assertTrue($this->Skill->isValidValue('5', 1), '5 is in 0-5');
		$this->assertTrue($this->Skill->isValidValue('0', 1), '0 is in 0-5');
		$this->assertFalse($this->Skill->isValidValue('-1', 1), '-1 is not in 0-5');
		$this->assertFalse($this->Skill->isValidValue('10', 1), '10 is not in 0-5');
		$this->assertFalse($this->Skill->isValidValue('4.0', 1), '4.0 shouldnt be allowed');
		$this->assertFalse($this->Skill->isValidValue('4.5', 1), '4.5 shouldnt be allowed');
		
		$this->assertTrue($this->Skill->isValidValue('1', 2), '1 is in 0.0-5.0');
		$this->assertTrue($this->Skill->isValidValue('1.5', 2), '1.5 is in 0.0-5.0');
		$this->assertTrue($this->Skill->isValidValue('0.0', 2), '0.0 is in 0.0-5.0');
		$this->assertTrue($this->Skill->isValidValue('5.0', 2), '5.0 is in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('-1', 2), '-1 is not in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('-1.0', 2), '-1.0 is not in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('-0.1', 2), '-0.1 is not in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('5.1', 2), '5.1 is not in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('7', 2), '7 is not in 0.0-5.0');
		$this->assertFalse($this->Skill->isValidValue('7.0', 2), '7.0 is not in 0.0-5.0');
		
		$this->assertTrue($this->Skill->isValidValue('1', 3), '1 is in valid textRange value');
		$this->assertTrue($this->Skill->isValidValue('0', 3), '0 is in valid textRange value');
		$this->assertTrue($this->Skill->isValidValue('4', 3), '4 is in valid textRange value');
		$this->assertFalse($this->Skill->isValidValue('asd', 3), 'asd is not in valid textRange value');
		$this->assertFalse($this->Skill->isValidValue('-1', 3), '-1 is not in valid textRange value');
		$this->assertFalse($this->Skill->isValidValue('5', 3), '5 is not in valid textRange value');
		
		$this->assertTrue($this->Skill->isValidValue('abcde', 4), 'abcde is valid text of 10 char');
		$this->assertTrue($this->Skill->isValidValue('a', 4), 'a is valid text of 10 char');
		$this->assertTrue($this->Skill->isValidValue('abcdefghij', 4), 'abcdefghij is valid text of 10 char');
		$this->assertFalse($this->Skill->isValidValue('abcdefghijk', 4), 'abcdefghijk is not valid text of 10 char');
		
		
	}

}
?>
