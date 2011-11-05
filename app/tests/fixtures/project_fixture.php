<?php
/* Project Fixture generated on: 2011-11-05 09:11:59 : 1320485699 */
class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 16, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'collection_end' => array('type' => 'datetime', 'null' => false),
		'feedback_end' => array('type' => 'datetime', 'null' => false),
		'description' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'collection_end' => '2011-11-05 09:34:59',
			'feedback_end' => '2011-11-05 09:34:59',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'status' => 1,
			'created' => '2011-11-05 09:34:59',
			'modified' => '2011-11-05 09:34:59'
		),
	);
}
?>