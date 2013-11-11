<?
	$temp = explode("Data=",$_SERVER[QUERY_STRING]);
	list($local_Data, $local_Size) = explode("&Size=",$temp[1]);
	
	if($local_Data == ''){
		$local_Data = "NULL";
	}
	
	if($local_Size == ''){
		$local_Size = 3;
	}
	
	require("../../../classes/image-qrcode/Image/QRCode.php");
	
	$options = array(
		"image_type"	=> "png",
		"output_type"	=> "display",
		"error_correct"	=> "L",
		"module_size"	=> $local_Size,
		"version"		=> 5
	);
	
	$qr = new Image_QRCode();
	$qrcode = $qr->makeCode($local_Data,$options);
?>