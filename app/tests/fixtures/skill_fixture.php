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

	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'type' => 1,
			'range' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2011-11-18 14:41:16',
			'modified' => '2011-11-18 14:41:16'
		),
	);
}
?>