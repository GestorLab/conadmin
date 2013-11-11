<?
	set_time_limit(0);	
	include ('../files/conecta.php');

	$TotalRecibo	 = 0;
	
	$sql = "select
				IdLoja
			from
				Loja";
	$resLoja = mysql_query($sql,$con);
	while($linLoja = mysql_fetch_array($resLoja)){	

		$sql = "select
					*
				from
					(select
					IdRecibo,
					count(*) qtd
				from
					ContaReceberRecebimento
				where
					IdLoja = $linLoja[IdLoja]
				group by
					 IdRecibo) qtd
				where
					qtd > 1";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			
			$sql = "select
						IdContaReceber,
						IdContaReceberRecebimento
					from
						ContaReceberRecebimento
					where
						IdLoja = $linLoja[IdLoja] and
						IdRecibo = $lin[IdRecibo]
					order by
						LoginCriacao desc";
			$resCRR = mysql_query($sql,$con);
			$linCRR = mysql_fetch_array($resCRR);

			$sql = "update ContaReceberRecebimento set
						IdRecibo = Recibo($linLoja[IdLoja])
					where
						IdLoja = $linLoja[IdLoja] and					
						IdContaReceber = $linCRR[IdContaReceber] and
						IdContaReceberRecebimento = $linCRR[IdContaReceberRecebimento]";
			mysql_query($sql,$con);			
		}
	}
?>