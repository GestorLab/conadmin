<?
	$Aviso[acessar_internet]	= false;
	$Msg[acessar_internet]		= '';

	if($Aviso4 == 1 && $Aviso1 != 2){
		
		$Aviso[acessar_internet]	= true;
		$Msg[acessar_internet]		= "<p style='text-align:center;'><input type='button' value='Acessar a Internet' onClick=\"window.location='$UrlRedirecionamento'\" /></p>\n";
	}
?>