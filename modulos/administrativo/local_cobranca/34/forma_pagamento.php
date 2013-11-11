<?
	$sql = "select
				IdLocalCobranca,
				IdPessoa
			from
				LocalCobranca
			where
				IdLoja = $local_IdLoja and
				IdLocalCobrancaLayout = 34 and
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
				IdLocalCobrancaLayout = 34 and
				IdLocalCobranca = $lin[IdLocalCobranca]";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
	}

	if($LocalCobrancaParametro[BandeiraELOCredito] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/elo.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "ELO Crédito";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraELOCredito];
		$iFormaPagamento++;
	}
	if($LocalCobrancaParametro[BandeiraELODebito] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/elo.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "ELO Débito";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraELODebito];
		$iFormaPagamento++;
	}
	if($LocalCobrancaParametro[BandeiraMasterCardCredito] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/mastercard.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "Master Card";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraMasterCardCredito];
		$iFormaPagamento++;
	}
	if($LocalCobrancaParametro[BandeiraMasterCardDebito] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/mastercard.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "Master Card Maestro";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraMasterCardDebito];
		$iFormaPagamento++;
	}
	if($LocalCobrancaParametro[BandeiraVisaCredito] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/visa.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "VISA";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraVisaCredito];
		$iFormaPagamento++;
	}
	if($LocalCobrancaParametro[BandeiraVisaEletron] >= 1){
		$FormaPagamento[$iFormaPagamento][img]			= "<img src='../../img/estrutura_sistema/bandeira_pgto/visaelec.jpg' alt= 'elo'/>";
		$FormaPagamento[$iFormaPagamento][descricao]	= "VISA Electron";
		$FormaPagamento[$iFormaPagamento][parcelas]		= $LocalCobrancaParametro[BandeiraVisaEletron];
		$iFormaPagamento++;
	}
?>