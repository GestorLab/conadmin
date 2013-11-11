<?php
	$invitee = $_GET['invitee'];
	$lines = file("http://messenger.services.live.com/users/$invitee@apps.messenger.live.com/presence?dt=&mkt=pt-br&cb=Microsoft_Live_Messenger_PresenceButton_onPresence");
	
	$tag = "";
	foreach ($lines as $line_num => $line) {
	    $tag .= htmlspecialchars($line);
	}
	
	$tagArray = explode("(", $tag);
	$tagArray = explode(")", $tagArray[1]);
	$tagArray[0] = str_replace('{', '', $tagArray[0]);
	$tagArray[0] = str_replace('}', '', $tagArray[0]);
	
	print $tagArray[0];
?>
