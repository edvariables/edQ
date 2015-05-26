<?php
/*

ods-php a library to read and write ods files from php.

This library has been forked from eyeOS project and licended under the LGPL3
terms available at: http://www.gnu.org/licenses/lgpl-3.0.txt (relicenced
with permission of the copyright holders)

Copyright: Juan Lao Tebar (juanlao@eyeos.org) and Jose Carlos Norte (jose@eyeos.org) - 2008 

https://sourceforge.net/projects/ods-php/

*/

$node = node($node, __FILE__);
require_once(node('..', $node, 'file')); //include the class and wrappers
/*$object = newOds(); //create a new ods file
$object->addCell(0,0,0,1,'float'); //add a cell to sheet 0, row 0, cell 0, with value 1 and type float
$object->addCell(0,0,1,2,'float'); //add a cell to sheet 0, row 0, cell 1, with value 1 and type float
$object->addCell(0,1,0,1,'float'); //add a cell to sheet 0, row 1, cell 0, with value 1 and type float
$object->addCell(0,1,1,2,'float'); //add a cell to sheet 0, row 1, cell 1, with value 1 and type float
*///saveOds($object,'c:\temp\150525 - TEST new.ods'); //save the object to a ods file

//$object=parseOds('c:\temp\Suivi budgetaire - copie.ods', array(0)); //load the ods file//Suivi budgetaire - copie //150525-TEST
//$object=parseOds('c:\temp\150525-TEST.ods', array(0)); //load the ods file//Suivi budgetaire - copie //
//echo('<pre>'.print_r($object->columns, true).'</pre>');
//$object->parseToHtml();
	

if(!isset($arguments))
	$arguments = $_REQUEST;
if(isset($arguments['sheet']))
	$sheet = $arguments['sheet'];
else
	$sheet = 0;
node('getSheets', $node, 'call', array('file'=>'c:\temp\Suivi budgetaire - copie.ods', 'sheet' => $sheet));
//node('getSheet', $node, 'call', array('file'=>'c:\temp\Suivi budgetaire - copie.ods', 'sheet' => $sheet));

//$object->editCell(0,0,0,25); //change the value for the cell in sheet 0, row 0, cell 0, to 25
//saveOds($object,'c:\temp\150525 - TEST saved.ods'); //save with other name


?>