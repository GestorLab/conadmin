<?
	include ('files/conecta.php');

	$IdLoja			= $_GET[IdLoja];
	$IdTipoEmail	= $_GET[IdTipoEmail];
	
	$sql = "select
				EstruturaEmail
			from 
				TipoEmail
			where
				IdLoja = $IdLoja and
				IdTipoEmail = $IdTipoEmail";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);

	if($lin[EstruturaEmail] == '') $lin[EstruturaEmail]=" ";

	echo $lin[EstruturaEmail];	 
?>
