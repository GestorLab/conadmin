<?
	// Apaga a posiчуo de cobranчa do contas a receber que foi baixado pelo o banco.
	$sql = "delete from 
				ContaReceberPosicaoCobranca 
			where
				IdLoja = $linContaReceber[IdLoja] and				
				IdContaReceber = $linContaReceber[IdContaReceber] and
				IdPosicaoCobranca = 4 and
				DataRemessa = '0000-00-00'";
	#$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	#$tr_i++;	
?>