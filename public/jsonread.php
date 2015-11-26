<?php

$content = file_get_contents('/home/naren/Downloads/vertical_tshirt.json');
$assocArray = json_decode($content, true);
$keys_to_replace = array('label' => 'label-en_US', 'name'=>'code', 'mandatory'=>'Group');
foreach($assocArray['attributeMap'] as &$variable)
{
	foreach ($variable as &$entity)
    {
  		
  		foreach ($entity as $key => $value)
  	    {
	  		 if($key == 'type')
	  		{
	  			 
	  		 	if($entity[$key] == 'text')
	  		 	{
	  		 		$entity[$key] = 'pim_catalog_text';
	  		 		unset($entity['allowedValues']);
	  		 		unset($entity['multipleEntries']);
	  		 	}
	  		 	elseif($entity[$key] == 'select')
	  		 	{
	  		 		$entity[$key] = 'pim_catalog_simpleselect';
	  		 	}
	  		 	elseif($entity[$key] == 'textarea')
	  		 	{
	  		 		$entity[$key] = 'pim_catalog_text';
	  		 	}

	  		}


	  		if($key == 'Group')
	  		{
	  			if($entity[$key] == '1')
	  			{
	  				$entity[$key] = 'Mandatory';
	  			}
	  			elseif($entity[$key] == '0')
	  			{
	  				$entity[$key] = 'Other';
	  			}
	  		}

	  		if($key == 'multipleEntries')
	  		{
	  			if($entity[$key] == '1')
	  			{
	  				$entity['type'] = 'pim_catalog_multiselect';
	  			}
	  		}
	  		  
	  		$entity['unique'] = '0';
	  		$entity['useable_as_grid_filter'] = '1';
	  		$entity['allowed_extensions'] = '';
	  		$entity['metric_family'] = '';
	  		$entity['default_metric_unit'] = '';
	  		$entity['localizable'] = '0';
	  		$entity['scopable'] = '0';

	  		unset($entity['multipleEntries']);
	  		unset($entity['usedFor']);
	  		unset($entity['qualifier']);
	  		unset($entity['exampleValue']);
	  		unset($entity['allowedValues']);

	  		if(array_key_exists($key, $keys_to_replace))
	  		{
	  			$key_to_replace = $keys_to_replace[$key];

	  			$entity[$key_to_replace] = $value;
	  			
	  			unset($entity[$key]);
	  		}

	  	}

	  	// echo "<pre>";
	  	// print_r($entity);

	  	$fp1 = fopen('/home/naren/Downloads/attributefk.csv', 'w');
	  	fputcsv($fp1, array_keys($entity));

		foreach ($entity[$key] as $fields) 
		{
		    fputcsv($fp1, $fields);
		}

		fclose($fp1);

  	}

}

?>

