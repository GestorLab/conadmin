<?
	$sql = "select
				IdLocalCobranca,
				IdPessoa
			from
				LocalCobranca
			where
				IdLoja = $local_IdLoja and
				IdLocalCobrancaLayout = 21 and
				IdStatus = 1
			limit 0,1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$sql = "select
				IdLocalCobrancaParametro,
				ValorLocalCobrancaParametro
			from
				LocalCobrancaParametro
			where
				IdLoja = $local_IdLoja and
				IdLocalCobrancaLayout = 21 and
				IdLocalCobranca = $lin[IdLocalCobranca]";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
	}

	echo "<p style='font-size:12px;'>Pague com:</p>
		  <table width=100% border=0;>
			<tr>";											

	if($LocalCobrancaParametro[TipoCobrancaBoleto] == 'Sim'){
		# Boleto
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/boleto.gif' alt= 'Boleto Bancário'/></td>";
		$iFormaPagamento++;
	}

	if($LocalCobrancaParametro[TipoCobrancaCartaoCredito] == 'Sim'){
		# Credito
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/amex.jpg' alt= 'American Express'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/visa.jpg' alt= 'VISA'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/mastercard.jpg' alt= 'MASTERCARD'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/elo.jpg' alt= 'elo'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/oipaggo.jpg' alt= 'Oi paggo.jpg'/></td>";
		$iFormaPagamento++;
	}
	
	if($LocalCobrancaParametro[TipoCobrancaCartaoDebito] == 'Sim'){
		#Débito
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/visaelec.jpg' alt= 'VISA ELECTRON'/></td>";
		$iFormaPagamento++;
	}

	if($LocalCobrancaParametro[TipoCobrancaTransferencia] == 'Sim'){
		# Tranf
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/f2b.jpg' alt= 'F2b'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/bb.jpg' alt= 'Banco do Brasil'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/bradesco.jpg' alt= 'Bradesco'/></td>";
		echo "<td style='text-align:center'><img src='../../img/estrutura_sistema/bandeira_pgto/itau.jpg' alt= 'Itaú'/></td>";
		$iFormaPagamento++;
	}
	
	echo "</tr></table>";
?>