<?php
	include("antispam.php");
	session_start("ConAdmin_AntiSpam");
	$spam = new AntiSpam();
	$spam->rand();
	$_SESSION["AntiSpamSTR"] = $code = $spam->str;
	$spam->show();
?>