<?
	$Aviso[geral]			= true;
	$Aviso['aviso'.$nAviso] = true;

	$lin[Aviso] = str_replace("
","<br>",$lin[Aviso]);

	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold;color: red'>T�tulo n�o Encontrado</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold'>Este t�tulo n�o est� dispon�vel para impress�o.</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center;'>Acesse a central do assinante para visualizar seu hist�rico financeiro.</p>\n";

	$nAviso++;
	$AvisoCod .= ",$lin[IdAviso]";
?>