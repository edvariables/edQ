<?php /*voir là : http://www.w3schools.com/xpath/xpath_syntax.asp */
$file = dirname(__FILE__) . ".php";
echo "file = " . ($file === null ? "null" : $file);
$doc = new DOMDocument(); 
if(file_exists($file))
    $doc->load($file);

$xpath = new DOMXpath($doc);

$elements = $xpath->query('//city[@name!="stockholm"]'); //city name="stockholm"
var_dump($elements);
foreach($elements as $element){
	 var_dump($element->nodeName);
	 echo $element->attributes->getNamedItem("name")->nodeValue;
	 //$element->attributes->getNamedItem("name")->nodeValue .= "X";
}
$doc->save($file);
?>