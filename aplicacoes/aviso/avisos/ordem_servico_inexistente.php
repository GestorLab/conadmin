<?
	$Aviso[geral]			= true;
	$Aviso['aviso'.$nAviso] = true;

	$lin[Aviso] = str_replace("
","<br>",$lin[Aviso]);

	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold;color: red'>Ordem de servi�o n�o Encontrado</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold'>Esta ordem de servi�o n�o est� dispon�vel para impress�o.</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center;'>Acesse a central do assinante para visualizar suas ordens de servi�o.</p>\n";

	$nAviso++;
	$AvisoCod .= ",$lin[IdAviso]";
?>