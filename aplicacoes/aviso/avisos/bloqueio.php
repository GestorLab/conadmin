<?
	$Aviso[bloqueio]	= false;
	$Msg[bloqueio]		= '';

	if($Aviso1 == 1){
		$sql = "select
					*
				from
					Contrato,
					Servico
				where
					Contrato.IdPessoa = $IdPessoa and
					Contrato.IdStatus >= 300 and
					Contrato.IdStatus <= 399 and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico
				order by
					Servico.DescricaoServico";
		$res = mysql_query($sql,$con);
		if($lin = mysql_fetch_array($res)){
			$local_IdLoja 		= $lin[IdLoja];
			$Aviso[geral]		= true;
			$Aviso[bloqueio]	= true;
			$Aviso4				= 2;

			$Msg[bloqueio] = "<h1 style='text-align:center; font-weight: bold; color: red'>".getParametroSistema(225,1)."</h1>\n";
			$Msg[bloqueio] .= "<p style='text-align:center;'>".getParametroSistema(225,2)."</p>\n";
		}
	}
?>