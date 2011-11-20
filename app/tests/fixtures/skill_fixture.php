<?php
/* Skill Fixture generated on: 2011-11-18 14:11:16 : 1321627276 */
class SkillFixture extends CakeTestFixture {
	var $name = 'Skill';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 32, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'length' => 32),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 160, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'range' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	/** Skill fixture to use **/
	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'name' => 'Numeric Range',
			'type' => SKILL_NUMERIC_RANGE,
			'range' => '0-5'
		),
		array(
			'id' => 2,
			'project_id' => 1,
			'name' => 'Numeric Range',
			'type' => SKILL_NUMERIC_RANGE,
			'range' => '0.0-5.0'
		),
		array(
			'id' => 3,
			'project_id' => 1,
			'name' => 'Text Range',
			'type' => SKILL_TEXT_RANGE,
			'range' => 'Hopeless|Bad|Okay|Good|Awesome'
		),
		array(
			'id' => 4,
			'project_id' => 1,
			'name' => 'Text Range',
			'type' => SKILL_TEXT,
			'range' => '10'
		)
	);
}
?>