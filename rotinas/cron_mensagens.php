<?
	// Contagem de envio por conta de e-mail
	$sql = "SELECT
				IdLoja,
				IdContaEmail,
				QtdTentativaEnvio,
				IntervaloEnvio
			FROM
				ContaEmail";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

#		echo "CONTA: $lin[IdLoja] -> $lin[IdContaEmail]\n";

		if($lin[IntervaloEnvio] <= 0){		$lin[IntervaloEnvio] = 1;		}
		if($lin[QtdTentativaEnvio] <= 0){	$lin[QtdTentativaEnvio] = 1;	}

		$QtdEnvio = 60 / ($lin[QtdTentativaEnvio] * $lin[IntervaloEnvio]);

		$sql2 = "SELECT
					TipoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					TipoMensagem LEFT JOIN 
					(SELECT
						IdLoja,
						IdTipoMensagem,
						COUNT(*) QtdEnviada
					FROM
						HistoricoMensagem
					WHERE
						DataEnvio >= CURDATE()
					GROUP BY
						IdLoja,
						IdTipoMensagem) HistoricoMensagemEnviada ON 
						(
							TipoMensagem.IdLoja = HistoricoMensagemEnviada.IdLoja AND
							TipoMensagem.IdTipoMensagem = HistoricoMensagemEnviada.IdTipoMensagem
						),
					HistoricoMensagem
				WHERE
					TipoMensagem.IdLoja = $lin[IdLoja] AND
					TipoMensagem.IdContaEmail = $lin[IdContaEmail] AND
					TipoMensagem.IdLoja = HistoricoMensagem.IdLoja AND
					TipoMensagem.IdTipoMensagem = HistoricoMensagem.IdTipoMensagem AND
					(
						HistoricoMensagem.IdStatus = 1 OR
						HistoricoMensagem.IdStatus = 5
					) AND
					(
						(
							TipoMensagem.LimiteEnvioDiario IS NULL OR 
							TipoMensagem.LimiteEnvioDiario = '' OR
							HistoricoMensagemEnviada.QtdEnviada IS NULL
						) OR 
						(TipoMensagem.LimiteEnvioDiario > HistoricoMensagemEnviada.QtdEnviada)
					)AND
					TIMESTAMP(HistoricoMensagem.DataCriacao,TipoMensagem.DelayDisparo) <= CONCAT(CURDATE(),' ',CURTIME())
				ORDER BY
					TipoMensagem.PrioridadeEnvio,
					HistoricoMensagem.QtdTentativaEnvio,
					HistoricoMensagem.IdHistoricoMensagem
				LIMIT 0,$QtdEnvio";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			enviaMensagem($lin2[IdLoja], $lin2[IdHistoricoMensagem]);
		}
	}

	// Envia os SMS independente da prioridade
	$sql = "SELECT
				HistoricoMensagem.IdLoja,
				HistoricoMensagem.IdHistoricoMensagem
			FROM
				HistoricoMensagem,
				TipoMensagem
			WHERE
				(HistoricoMensagem.IdStatus = 1 OR 
				HistoricoMensagem.IdStatus = 5) AND
				HistoricoMensagem.IdLoja = TipoMensagem.IdLoja AND
				HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem AND
				TipoMensagem.IdContaSMS >= 1 AND
				TIMESTAMP(HistoricoMensagem.DataCriacao,TipoMensagem.DelayDisparo) <= CONCAT(CURDATE(),' ',CURTIME())
			ORDER BY
				TipoMensagem.PrioridadeEnvio,
				TipoMensagem.IdLoja,
				HistoricoMensagem.IdHistoricoMensagem";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		enviaMensagem($lin[IdLoja], $lin[IdHistoricoMensagem]);
	}
?>