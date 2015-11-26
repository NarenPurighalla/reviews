<?php

  $data = file_get_contents('/home/naren/Downloads/vertical_tshirt.json');
  $assocArray = json_decode($data, true);

  $out = [];
  $searchReplaceArray = array(
  ' ' => '_', 
  '/' => '_',
  '-' => '_'
);
  foreach($assocArray["attributeMap"] as &$attributeMap)
  {
  	foreach ($attributeMap as $index => $entity)
    {
  		foreach ($entity as $key => $value)
  	    {
	  	    if($key == 'type')
	  	    {
	  	    	if($entity[$key] == 'select' && array_key_exists('allowedValues', $entity) && array_key_exists('name', $entity)){
	  	    		$name = $entity['name'];
	  	    		foreach ($entity['allowedValues'] as $allowedValue) {
		  	    		$item = [
		  	    			'attribute' => $name,
		  	    			'label-en_US' => $allowedValue,
		  	    			'code' => str_replace(array_keys($searchReplaceArray), array_values($searchReplaceArray), $allowedValue),
		  	    			'sort_order' => ''
		  	    		];
	  	    			$out[] = $item;
	  	    		}
	  	    		
	  	    	}

	  	    }

	  	}

    }

   }
   echo '<pre>' . print_r($out, true) . '</pre>';
  ?>
