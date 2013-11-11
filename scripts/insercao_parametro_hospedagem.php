<?
	include ('../files/conecta.php');

	$sql = "select 
				Contrato.IdLoja,
				Contrato.IdContrato,
				Contrato.IdServico,
				ServicoParametro.ValorDefault
			from
				Contrato,
				Servico,
				ServicoParametro
			where
				Contrato.IdLoja = 1 and
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdLoja = ServicoParametro.IdLoja and
				Contrato.IdServico in (1,5,9,23,28) and
				Contrato.IdServico = Servico.IdServico and
				Contrato.IdServico = ServicoParametro.IdServico and
				ServicoParametro.IdParametroServico = 2";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlInsert = "insert into ContratoParametro (IdLoja, IdContrato, IdParametroServico, IdServico, Valor) values ($lin[IdLoja], $lin[IdContrato], '2', $lin[IdServico], '$lin[ValorDefault]' )";
		mysql_query($sqlInsert,$con);	
	}
?>