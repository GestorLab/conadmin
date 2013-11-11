<?
	$iTermo = 0;

	$sqlTipoMensagem = "SELECT
							IdLoja
						FROM
							TipoMensagem
						WHERE
							IdTipoMensagem = 34 AND
							IdStatus = 1";
	$resTipoMensagem = mysql_query($sqlTipoMensagem,$con);
	while($linTipoMensagem = mysql_fetch_array($resTipoMensagem)){

		$sql = "SELECT
					ContaReceber.IdLoja,
					ContaReceber.IdPessoa,
					COUNT(*) Qtd
				FROM
					(SELECT
						ContaReceberVencimento.IdLoja,
						ContaReceberVencimento.IdContaReceber,
						MIN(ContaReceberVencimento.DataVencimento) DataVencimentoOriginal
					FROM
						ContaReceberVencimento
					GROUP BY
						ContaReceberVencimento.IdLoja,
						ContaReceberVencimento.IdContaReceber) ContaReceberVencimentoOriginal,
					ContaReceber
				WHERE
					ContaReceberVencimentoOriginal.DataVencimentoOriginal >= CONCAT(SUBSTRING(CURDATE(),1,4)-1,'-01-01') AND
					ContaReceberVencimentoOriginal.DataVencimentoOriginal <= CONCAT(SUBSTRING(CURDATE(),1,4)-1,'-12-31') AND
					ContaReceberVencimentoOriginal.IdLoja = ContaReceber.IdLoja AND
					ContaReceberVencimentoOriginal.IdContaReceber = ContaReceber.IdContaReceber AND
					ContaReceber.IdStatus != 0 and
					ContaReceber.IdStatus != 7 and
					ContaReceber.IdLoja = $linTipoMensagem[IdLoja]
				GROUP BY
					ContaReceber.IdPessoa";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			if($iTermo >= 200){
				break;
			}

			$sql2 = "SELECT
						IdPessoa
					FROM
						HistoricoMensagem
					WHERE
						IdTipoMensagem = 34 AND
						IdLoja = $lin[IdLoja] AND
						IdPessoa = $lin[IdPessoa] AND
						IdStatus != 3 AND
						IdStatus != 6 AND
						DataCriacao >= '".date("Y")."-05-01'";
			$res2 = mysql_query($sql2,$con);
			if(mysql_num_rows($res2) == 0){

				$sql3 = "SELECT
							COUNT(*) Qtd
						FROM
							(SELECT
								ContaReceberVencimento.IdLoja,
								ContaReceberVencimento.IdContaReceber,
								MIN(ContaReceberVencimento.DataVencimento) DataVencimentoOriginal
							FROM
								ContaReceberVencimento
							GROUP BY
								ContaReceberVencimento.IdLoja,
								ContaReceberVencimento.IdContaReceber) ContaReceberVencimentoOriginal,
							ContaReceber
						WHERE
							ContaReceberVencimentoOriginal.DataVencimentoOriginal >= CONCAT(SUBSTRING(CURDATE(),1,4)-1,'-01-01') AND
							ContaReceberVencimentoOriginal.DataVencimentoOriginal <= CONCAT(SUBSTRING(CURDATE(),1,4)-1,'-12-31') AND
							ContaReceberVencimentoOriginal.IdLoja = ContaReceber.IdLoja AND
							ContaReceberVencimentoOriginal.IdContaReceber = ContaReceber.IdContaReceber AND
							ContaReceber.IdStatus = 2 and
							ContaReceber.IdLoja = $lin[IdLoja] AND
							ContaReceber.IdPessoa = $lin[IdPessoa]";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);

				if($lin[Qtd] == $lin3[Qtd]){
					$iTermo++;
					enviarTermoQuitacaoAnual($lin[IdLoja], $lin[IdPessoa],date("Y")-1);
				}
			}
		}
	}
?>