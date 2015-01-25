<?php /*é*/
$file = dirname(__FILE__) . ".php";
echo "source = " . ($file === null ? "null" : $file);
$doc = new DOMDocument(); 
if($file && file_exists($file))
	 $doc->load($file);
//else
	 //$doc->
$doc->save($file);
?><pre><?=$doc->saveHTML()?></pre>