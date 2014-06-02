<?php /*é*/
$file = dirname(__FILE__) . ".php";
echo "file = " . ($file === null ? "null" : $file);
$doc = new DOMDocument(); 
$doc->load($file);


$xpath = new DOMXpath($doc);

// example 1: for everything with an id
$elements = $xpath->query('//city[@name="stockholm"]'); //city name="stockholm"
var_dump($elements);
foreach($elements as $element){
	 var_dump($element->nodeName);
	 $element->parentNode->removeChild($element);
}

// example 2: for node data in a selected id
//$elements = $xpath->query("/html/body/div[@id='yourTagIdHere']");

$doc->save($file);
?>