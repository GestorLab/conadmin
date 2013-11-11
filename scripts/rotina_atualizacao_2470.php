<?
	include ('../files/conecta.php');	

	$sql = "SELECT
				IdCaixa,
				IdCaixaMovimentacao,
				IdLoja
			FROM
				CaixaMovimentacao";
	$res1 = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res1)){

		$sql2 = "SELECT
					DataAbertura
				FROM
					Caixa
				WHERE
					IdCaixa = $lin[IdCaixa] AND 
					IdLoja = $lin[IdLoja]";
		
		$res2 = mysql_query($sql2);
		$lin2 = mysql_fetch_array($res2);
		
		$sql3 = "UPDATE CaixaMovimentacao
					SET 
					  DataHoraCriacao = '$lin2[DataAbertura]'
					WHERE IdLoja = '$lin[IdLoja]'
						AND IdCaixa = '$lin[IdCaixa]'
						AND IdCaixaMovimentacao = '$lin[IdCaixaMovimentacao]'";
		
		$res3 = mysql_query($sql3);		
	}
?>