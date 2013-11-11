<?
	$Aviso[geral]			= true;
	$Aviso['aviso'.$nAviso] = true;

	$lin[Aviso] = str_replace("
","<br>",$lin[Aviso]);

	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold;color: red'>Título não Encontrado</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold'>Este título não está disponível para impressão.</p>\n";
	$Msg['aviso'.$nAviso]	.= "<p style='text-align:center;'>Acesse a central do assinante para visualizar seu histórico financeiro.</p>\n";

	$nAviso++;
	$AvisoCod .= ",$lin[IdAviso]";
?>