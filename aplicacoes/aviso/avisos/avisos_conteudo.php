<?
	$Aviso[geral]			= true;

	$IdAvisos = array_keys($Avisos);

	for($i=0; $i<count($IdAvisos); $i++){
		
		$Aviso['aviso'.$nAviso] = true;

		$lin[Aviso] = str_replace("
	","<br>",$Avisos[$IdAvisos[$i]][Aviso]);

		$Msg['aviso'.$nAviso]	.= "<p style='text-align:center; font-weight: bold;'>".$Avisos[$IdAvisos[$i]][TituloAviso]."</p>\n";
		$Msg['aviso'.$nAviso]	.= "<p style='text-align:center;'><I>".$Avisos[$IdAvisos[$i]][ResumoAviso]."</I></p>\n";
		$Msg['aviso'.$nAviso]	.= "<p style='text-align:justify;'>".$Avisos[$IdAvisos[$i]][Aviso]."</p>\n";

		$nAviso++;
	}
?>