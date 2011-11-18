<?php
/* MembersSkill Fixture generated on: 2011-11-18 14:11:23 : 1321627463 */
class MembersSkillFixture extends CakeTestFixture {
	var $name = 'MembersSkill';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 32, 'key' => 'primary'),
		'skill_id' => array('type' => 'integer', 'null' => false, 'length' => 32),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 32),
		'skill_value' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'skill_id' => 1,
			'user_id' => 1,
			'skill_value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2011-11-18 14:44:23',
			'modified' => '2011-11-18 14:44:23'
		),
	);
}
?>