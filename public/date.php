<?php

$rows = array_map('str_getcsv', file('/home/naren/Downloads/IDs/fkid.csv'));
$header = array_shift($rows);
$csv = array();

foreach ($rows as $row) {
  $csv[] = array_combine($header, $row);
}

foreach($csv as $id)
{
	
	
}
