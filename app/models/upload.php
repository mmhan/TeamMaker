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
	
	function listHeaders($id){
		//read the file that has been uploaded.
		$filename = $this->field('name',array('id'=> $id));
		if(empty($filename)){
			return false;
		}
		
		// set the filename to read CSV from
		$filename = WWW_ROOT . 'csv_files' . DS . $filename;
		
		// open the file
 		$handle = fopen($filename, "r");
 		
 		// read the 1st row as headings
 		$header = fgetcsv($handle);
 		
		return $header;
	}
	
	function readCsv($id){
		App::import('Vendor', 'parseCSV', array('file' => 'parse_csv' . DS . 'parsecsv.lib.php'));
		
		//read the file that has been uploaded.
		$filename = $this->field('name',array('id'=> $id));
		if(empty($filename)){
			return false;
		}
		
		// set the filename to read CSV from
		$filename = WWW_ROOT . 'csv_files' . DS . $filename;
		
		$csv = new parseCSV();
		$csv->auto($filename);
		
		return $csv->data;
	}
}
?>