<?php /*voir là : http://www.w3schools.com/xpath/xpath_syntax.asp */
$ids = preg_split("/\\n/", str_replace('\r', '', "
041064
072671
021986
029209
012299
087399

156429
008304
049246
112445
021344
024637

028990
078699
048536
085035
243774
088483

143403
243253
110465
157996
114392
242290

083579
018495
022152
150779
195843
126357

037807
046813
191392
021162
135904
143064

036398
000381
000669
029312
223708
085715

061830
085557
110348
015744
108340
046743

021867
029897
083167
017091
260351
041077
"));
function isValidId($text, $ids){
	 //echo('<br>test de ' . $text);
	 for($i = 0; $i < count($ids); $i++){
	 	 $test = $ids[$i];
	 	 if($test != "") { 
	 	 	 if(preg_match("/-$test-/", $text)) return true;
	 	 }
	 }
	 return false;
}

$file = dirname(__FILE__) . ".php";
echo "file = " . ($file === null ? "null" : $file);
$doc = new DOMDocument(); 
if(file_exists($file))
    $doc->load($file);

$xpath = new DOMXpath($doc);

$elements = $xpath->query('//*');
var_dump($elements->length);
$counter = 0;
$amount = 0;
foreach($elements as $element)
/*if($element->nodeName == 'EndToEndId')
	 if(isValidId($element->nodeValue, $ids)
	 ){
	 	 $counter++;

		  var_dump($element->nodeValue);
	 	 //echo $element->attributes->getNamedItem("name")->nodeValue;
	 	 //$element->attributes->getNamedItem("name")->nodeValue .= "X";
	 }
	 else
	 	 $element->parentNode->parentNode->parentNode->removeChild($element->parentNode->parentNode);
*/
if($element->nodeName == 'InstdAmt'){
	 $amount += doubleval($element->nodeValue);
	 $counter++;
}
$doc->save($file);
echo "$counter trouvés";
echo "$amount euros";
?>