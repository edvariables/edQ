<?php /*voir là : http://www.w3schools.com/xpath/xpath_syntax.asp */
/* 
Evalue les prélèvements en double.
Si on modifie le fichier Xml, il faut modifier deux fois le nombre de prlvnts et le cumul des montants en tête de fichier XML.

Copier le contenu XML dans le noeud Xml
*/

$file = dirname(__FILE__) . "/Xml.php";
echo "file = " . ($file === null ? "null" : $file);
$doc = new DOMDocument(); 
if(file_exists($file))
    $doc->load($file);

$xpath = new DOMXpath($doc);

$elements = $xpath->query('//*');
var_dump($elements->length);
$counter = 0;
$amount = 0;
$reffiches = array();
foreach($elements as $element){
	if($element->nodeName == 'MndtId'){//EndToEndId
		$reffiche = preg_replace('/^.*RSDN-(\d+)-.*$/', '$1', $element->nodeValue);
		$reffiche = $element->nodeValue;
		//echo '<br>' . $element->nodeValue;
		if(array_key_exists($reffiche, $reffiches)){
			$reffiches[$reffiche] .= ', ' . $element->nodeValue;
			echo '<br>';
			echo $reffiches[$reffiche];
			//var_dump($reffiche);
		}
		else
			$reffiches[$reffiche] = $element->nodeValue;
	}
	else if($element->nodeName == 'InstdAmt'){
		 $amount += doubleval($element->nodeValue);
		 $counter++;
	}
}
//$doc->save($file);
echo "<br>$counter trouvés";
echo "<br>$amount euros";
?>