<?php

$row = 1;
$csv = [];
$handle = fopen("/home/naren/Downloads/test1.csv", "rw");

if (($handle) !== FALSE) {
    while (($result = fgetcsv($handle)) !== false) {
	    if (array(null) != $result) { // ignore blank lines
	        
	       	$csv[] = $result;
	    }
	}
}
foreach ($csv as $key => $value) {

	$value = array_filter($value);
	// print_r($key);
	// print_r("aftre filter");
	// print_r($value);
	$csv[$key] = $value;
}

$csv = array_filter($csv);
// print_r($csv);
// die();

foreach ($csv as $fields) {
	print_r($fields);

    fputcsv($handle, $fields);
}


fclose($handle);

?>