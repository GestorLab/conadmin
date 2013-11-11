<?
	$Aviso[bloqueio]	= false;
	$Msg[bloqueio]		= '';

	if($Aviso1 == 1){
		$Aviso[geral]		= true;
		$Aviso[bloqueio]	= true;
		$Aviso4				= 2;

		$Msg[bloqueio] = "<h1 style='text-align:center; font-weight: bold; color: red'>".getParametroSistema(225,1)."</h1>\n";
		$Msg[bloqueio] .= "<p style='text-align:center;'>".getParametroSistema(225,2)."</p>\n";
	}
?>
