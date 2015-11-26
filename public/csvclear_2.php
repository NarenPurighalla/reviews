<?php

$handle = fopen("/home/naren/Downloads/Kurtis_Master_Sheet_akeneo.csv", 'r'); //your csv file
$clean = fopen("/home/naren/Downloads/clean.csv", 'a+'); //new file with no empty rows

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    if($num > 1)
    fputcsv($clean, $data ,";");
}
fclose($handle);
fclose($clean);

?>