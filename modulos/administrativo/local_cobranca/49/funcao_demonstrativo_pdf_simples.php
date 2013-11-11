<?
	global $Background;

	$height 	= 0;
	$cont		= 0;
	$contador	= 0;
	$altura		= 35;
	
	// Default
	$this->margin_left = 10;
	$this->height_cell = 3;
	$this->SetLineWidth(0.3);

	$i					= 0;
	$valorTotal			= 0;
	$DadosLancamento	= null;
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
	$sql = "select
				Tipo,
				Codigo,
				Descricao,
				if(ExibirReferencia != 2, Referencia, '-') Referencia,
				Valor,
				ValorDespesas,
				IdTerceiro
			from
				Demonstrativo
			where
				IdLoja = $IdLoja and
				IdContaReceber = $IdContaReceber
			order by
				IdTerceiro,
				Tipo,
				Codigo,
				IdLancamentoFinanceiro";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$valorTotal += $lin[Valor];

		$DadosLancamento[$lin[IdTerceiro]][$i][Tipo]		= $lin[Tipo];
		$DadosLancamento[$lin[IdTerceiro]][$i][Cod]			= $lin[Codigo];
		$DadosLancamento[$lin[IdTerceiro]][$i][Descricao]	= $lin[Descricao];
		$DadosLancamento[$lin[IdTerceiro]][$i][Referencia]	= $lin[Referencia];
		$DadosLancamento[$lin[IdTerceiro]][$i][Valor]		= $lin[Valor];
		$DadosLancamento[$lin[IdTerceiro]][Valor]			+= $lin[Valor];

		$i++;
	}

	$IdTerceiro = array_keys($DadosLancamento);

	for($i = 0; $i <count($DadosLancamento); $i++){
	
		$Lancamentos = array_keys($DadosLancamento[$IdTerceiro[$i]]);

		if($IdTerceiro[$i] == ''){
			$DescricaoGrupo = 'Demonstrativo';
		}else{				
			$sqlTerceiro = "select
								Nome,
								RazaoSocial,
								CPF_CNPJ
							from
								Pessoa
							where
								IdPessoa = $IdTerceiro[$i]";
			$resTerceiro = mysql_query($sqlTerceiro,$con);
			$linTerceiro = mysql_fetch_array($resTerceiro);

			$DescricaoGrupo = "Serviços de Terceiros - $linTerceiro[Nome] ($linTerceiro[RazaoSocial]) - $linTerceiro[CPF_CNPJ]";
			$this->ln(4);
			$cont++;	
		}
		$this->SetFont('Arial','',9);
		
		$this->Image("imagens/navex.JPG",25,31,60.2,7.0,"JPG");
		

?>
