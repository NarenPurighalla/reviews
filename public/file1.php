<?php
$file1 = fopen("/home/naren/file1.csv","r");
$file2 = fopen("/home/naren/file2.csv","r");
// echo $file;
while(! feof($file1))
  {
  print_r(fgetcsv($file1));
  }
  while(! feof($file2))
  {
 print_r(fgetcsv($file2));
  }
   $result = array_merge(fgetcsv($file1),fgetcsv($file2));
   echo $result;
fclose($file1);
 fclose($file2);
?>