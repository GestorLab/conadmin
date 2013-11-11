<?php
	include("../vars.php");
	include("../vetor.php");
	 
	// include the php-excel class
	require ("../../../../classes/php-excel/class-excel-xml.inc.php");

	// generate excel file
	$xls = new Excel_XML;
	$xls->addArray ($vetor);
	$xls->generateXML ("etiqueta");
?>
