<?php
class Upload extends AppModel {
	var $name = 'Upload';
	var $actsAs = array(
		'FileUpload.FileUpload' => array(
			'uploadDir' => 'csv_files',
			'allowedTypes' => array(
				'csv'
			),
			'required' => true, //default is false, if true a validation error would occur if a file wsan't uploaded.
			'maxFileSize' => '10000', //bytes OR false to turn off maxFileSize (default false)
			'fileNameFunction' => 'sha1' //execute the Sha1 function on a filename before saving it (default false)
		)
  );
}
?>