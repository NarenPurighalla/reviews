<?php

[...]

class MyService implements ContainerAwareInterface
{
    public method readSpreadsheet()
    {
        $loader = $this->container->get('akeneo_spreadsheet_parser.workbook_loader');
        $workbook = $loader->open('/home/naren/Downloads/init.xlsx');

        $myWorksheetIndex = $workbook->getWorksheetIndex('myworksheet');

        foreach ($workbook->createIterator($myWorksheetIndex) as $rowIndex => $values) {
            var_dump($rowIndex, $values);
        }
    }

    [...]
}
?>