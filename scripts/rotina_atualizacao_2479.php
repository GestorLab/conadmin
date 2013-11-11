<?
	include("../files/conecta.php");

	$sql = "SELECT
				Demonstrativo.IdLoja,
				Demonstrativo.IdLancamentoFinanceiro,
				DemonstrativoDescricao.ValorParametroDemonstrativo
			FROM
				Demonstrativo,
				DemonstrativoDescricao
			WHERE
				Demonstrativo.Tipo = 'CO' AND
				Demonstrativo.IdLoja = DemonstrativoDescricao.IdLoja AND
				Demonstrativo.IdLancamentoFinanceiro = DemonstrativoDescricao.IdLancamentoFinanceiro";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "UPDATE LancamentoFinanceiro SET 
					ParametroDemonstrativo = '$lin[ValorParametroDemonstrativo]' 
				WHERE 
					IdLoja = $lin[IdLoja] AND 
					IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro]";
		mysql_query($sql,$con);
	}
?>