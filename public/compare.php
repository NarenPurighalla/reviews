<?php
function row_compare($a, $b)
{
    if ($a === $b) {
        return 0;
    }

    return (implode("",$a) < implode("",$b) ) ? -1 : 1;
}

$file1 = new SplFileObject("/home/naren/file1.csv");
$file1->setFlags(SplFileObject::READ_CSV);

$file2 = new SplFileObject("/home/naren/file2.csv");
$file2->setFlags(SplFileObject::READ_CSV);

foreach ($file1 as $row) {
    $csv_1[] = $row;
}

foreach ($file2 as $row) {
    $csv_2[] = $row;
}

$unique_to_csv1 = array_udiff($csv_1, $csv_2, 'row_compare');
$unique_to_csv2 = array_udiff($csv_2, $csv_1, 'row_compare');
print_r (array_diff_assoc($unique_to_csv1[0],$unique_to_csv2[0]));
echo '<br/>';
print_r (array_diff_assoc($unique_to_csv1[1],$unique_to_csv2[1]));

/*$all_unique_rows = array_merge($unique_to_csv1,$unique_to_csv2);

foreach($all_unique_rows as $unique_row) {
    foreach($unique_row as $element) {
        echo $element . "   ";
    }
    echo '<br />';
}*/
?>