<?
	$localModulo		=	1;
	$localOperacao		=	69;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdPessoaLogin				= $_SESSION['IdPessoa'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_login						= $_POST['filtro_login'];
	$filtro_cidade						= url_string_xsl($_POST['filtro_cidade'],"URL",false);
	$filtro_local_cobranca				= $_POST['filtro_local_cobranca'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_local_recebimento			= $_POST['filtro_local_recebimento'];
	$filtro_data_inicio					= $_POST['filtro_data_inicio'];
	$filtro_data_fim					= $_POST['filtro_data_fim'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_grupo_pessoa				= $_POST['filtro_grupo_pessoa'];
	$filtro_pais						= getCodigoInterno(3,1);
	$filtro_estado						= $_POST['filtro_estado'];
	$filtro_idPessoa					= $_GET['IdPessoa'];
	$filtro_conta_receber				= $_POST['IdContaReceber'];
	$filtro_carne						= $_GET['IdCarne'];
	$filtro_numero_documento			= $_GET['NumeroDocumento'];
	$filtro_data						= $_POST['filtro_data'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_IdServico					= $_GET['IdServico'];
	$filtro_descricao_servico			= url_string_xsl($_POST['filtro_descricao_servico'],"URL",false);
	$filtro_nota_fiscal					= $_SESSION["filtro_nota_fiscal"];
	$filtro_conta_receber_nota_fiscal 	= $_SESSION["filtro_conta_receber_nota_fiscal"];
	$filtro_endereco				 	= url_string_xsl($_POST["filtro_endereco"],"URL",false);
	$filtro_bairro					 	= url_string_xsl($_POST["filtro_bairro"],"URL",false);
	$filtro_tipo_impressao				= url_string_xsl($_POST["filtro_impressao"],"URL",false);
	$filtro_cpf_cnpj					= $_SESSION["filtro_cpf_cnpj"];
	$filtro_oculta_cidade_uf			= $_SESSION["filtro_oculta_cidade_uf"];
	$filtro_cancelado					= $_SESSION["filtro_cancelado"];
	
	if($filtro_IdServico == ''){
		$filtro_IdServico	= $_POST['IdServico'];
	}
	
	if($_GET['IdServico'] != ''){
		$filtro_servico					= $_GET['IdServico'];
	}
	if($_POST['IdServico'] != ''){
		$filtro_servico					= $_POST['IdServico'];
	}
		
	if($filtro_conta_receber == '' && $_GET['IdContaReceber']!=''){
		$filtro_conta_receber = $_GET['IdContaReceber'];
	}
		
	$codigo_de_barras			= $_POST['codigo_de_barras'];
	
	if($codigo_de_barras !=''){
		switch(strlen($codigo_de_barras)){
			case '44':
				$filtro_nosso_numero = (int)(substr($codigo_de_barras,32,10));
				break;
		}
	}
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");	
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_login!=''){
		$filtro_url .= "&LoginCriacao=$filtro_login";
		$filtro_sql .=	" and ContaReceberRecebimento.LoginCriacao = '$filtro_login'";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
		
	if($filtro_idPessoa!=""){
		$filtro_url  .= "&IdPessoa=".$filtro_idPessoa;
		$filtro_sql .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}
	
	if($filtro_estado!=""){
		$filtro_url  .= "&IdEstado=".$filtro_estado;
		$filtro_sql .= " and PessoaEndereco.IdPais = $filtro_pais and PessoaEndereco.IdEstado = $filtro_estado";
	}

	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceberRecebimento.IdContaReceber = $filtro_conta_receber";
	}
				
	if($filtro_local_recebimento!=""){
		$filtro_url  .= "&IdLocalCobrancaRecebimento=".$filtro_local_recebimento;
		$filtro_sql .= " and ContaReceberRecebimento.IdLocalCobranca = $filtro_local_recebimento";
	}
	
	if($filtro_cidade!=""){
		$filtro_url  .= "&NomeCidade=".$filtro_cidade;
		$filtro_sql .= " and Cidade.NomeCidade like '%$filtro_cidade%'";		
	}

	if($filtro_endereco != ""){
		$filtro_url	.= "&Endereco=$filtro_endereco";
		$filtro_sql .=	" and PessoaEndereco.Endereco like '%$filtro_endereco%'";
	}

	if($filtro_bairro != ""){
		$filtro_url	.= "&Bairro=$filtro_bairro";
		$filtro_sql .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_status != ""){
		$filtro_url  .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and ContaReceberRecebimento.IdStatus = $filtro_status";
	}
	
	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}
	//echo ">>".$filtro_cancelado."<<";
	if($filtro_cancelado!=""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_cancelado == 2 && $filtro_status == ""){
			$filtro_sql  .= " and ContaReceberRecebimento.IdStatus != 0";
		}
	}
	
	if($filtro_data!=''){
		$filtro_url .= "&Data=$filtro_data";
		switch($filtro_data){
			case 'Movimentacao':
				if($filtro_data_inicio!=""){
					$filtro_url 		.= "&DataInicio=".$filtro_data_inicio;
					$filtro_data_inicio	 =	dataConv($filtro_data_inicio, "d/m/Y","Y-m-d");
					$filtro_sql 		.= " and SUBSTR(ContaReceberRecebimento.DataCriacao,1,10) >= '$filtro_data_inicio'";
				}
				
				if($filtro_data_fim!=""){
					$filtro_url  		.= "&DataFim=".$filtro_data_fim;
					$filtro_data_fim	 =	dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
					$filtro_sql 		.= " and SUBSTR(ContaReceberRecebimento.DataCriacao,1,10) <= '$filtro_data_fim'";
				}
				break;
			case 'Vencimento':
				if($filtro_data_inicio!=""){
					$filtro_url 		.= "&DataInicio=".$filtro_data_inicio;
					$filtro_data_inicio	 =	dataConv($filtro_data_inicio, "d/m/Y","Y-m-d");
					$filtro_sql 		.= " and SUBSTR(ContaReceberDados.DataVencimento,1,10) >= '$filtro_data_inicio'";
				}
				
				if($filtro_data_fim!=""){
					$filtro_url  		.= "&DataFim=".$filtro_data_fim;
					$filtro_data_fim	 =	dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
					$filtro_sql 		.= " and SUBSTR(ContaReceberDados.DataVencimento,1,10) <= '$filtro_data_fim'";
				}
				break;
		}
	}
	if($filtro_descricao_servico!=''){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and (
							Contrato.IdServico = Servico.IdServico OR 
							OrdemServico.IdServico = Servico.IdServico
						) and
						Servico.DescricaoServico like '%$filtro_descricao_servico%'";
		
		if($filtro_id_servico!=""){
			$filtro_url .= "&IdServico=".$filtro_id_servico;
			$filtro_sql .= " and
							Servico.IdServico = $filtro_id_servico ";
		}
	} elseif($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= "and
							Servico.IdServico = $filtro_id_servico ";
	}
	if($filtro_nota_fiscal!=""){
		$filtro_url .= "&NotaFiscal=".$filtro_nota_fiscal;
	}
	
	if($filtro_conta_receber_nota_fiscal!=""){
		if($filtro_conta_receber_nota_fiscal == 1){	
			$filtro_sql .=	" and (ContaReceberDados.NumeroNF != '' and ContaReceberDados.NumeroNF is not null)";
		}
	}	
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdServico=".$filtro_IdServico;
		$filtro_sql .= " and Servico.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_grupo_pessoa != ""){
		$filtro_url .= "&IdGrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and PessoaGrupoPessoa.IdGrupoPessoa = '$filtro_grupo_pessoa'";
	}


	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	if($filtro_tipo_impressao == 1){
		$limitenome		= '20';
		$limitecidade	= '15';
		header ("content-type: text/xml");	
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_movimentacao_recebimento_xsl.php$filtro_url\"?>";
		echo "<db>";
	}else{
		include "../../classes/fpdf/class.fpdf.php";
		$limitenome		= '50';
		$limitecidade	= '45';
		$pdf = new FPDF('L','cm','A4');
		$pdf->SetMargins(0.3, 1.0, 1.64);
		$pdf->AddPage();
		$pdf->setFont('Arial','',11);
		$pdf->Ln();
		$pdf->Cell(11.1, 1.2, 'Relatório Conta Receber Movimentação Diária(Recebimentos)', 0,0, 'L',0);
		$pdf->setFont('Arial','',9);
		$pdf->Cell(6.0, 1.2, 'Relatório Emitido em: '.date("d/m/Y").' ás '.date("H:i:s"), 0,0, 'L',0);
		$pdf->Ln();
		$pdf->setFont('Arial','B',7);
		$pdf->Cell(0.9, 0.5, 'Id', 0,0, 'L',0);
		$pdf->Cell(1.0, 0.5, 'Receb.', 0,0, 'L',0);
		$pdf->Cell(5.8, 0.5, 'Nome Pessoa', 0,0, 'L',0);
		
		if($filtro_cpf_cnpj == 2){
			$pdf->Cell(2.4, 0.5, 'CPF/CNPJ', 0,0, 'L',0);
		}
		if($filtro_nota_fiscal == 2){
			$pdf->Cell(2.4, 0.5, 'N° NF', 0,0, 'L',0);
		}
		
		$pdf->Cell(1.7, 0.5, 'N° Doc', 0,0, 'L',0);
		
		if($filtro_oculta_cidade_uf == 2){
			$pdf->Cell(4.4, 0.5, 'Cidade - UF', 0,0, 'L',0);
		}
		$pdf->Cell(2.0, 0.5, "Valor(".getParametroSistema(5,1).")", 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, 'Vencimento', 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, "Receb(".getParametroSistema(5,1).")", 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, 'Pagamento', 0,0, 'L',0);
		$pdf->Cell(2.5, 0.5, 'Local Recebimento', 0,0, 'L',0);
		$pdf->Cell(2.5, 0.5, 'Status', 0,0, 'L',0);
		$pdf->Ln();
		$pdf->Cell(29.4, 0.2,'','T',0, 'L',0);
	}
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$cont	=	0;
	$sql	=	"select
					distinct
					IdContaReceberRecebimento,
					DataRecebimento, 
					LocalCobranca.AbreviacaoNomeLocalCobranca AbreviacaoNomeLocalCobranca, 
					ValorRecebido, 
					ContaReceberRecebimento.IdContaReceber, 
					ContaReceberDados.ValorDesconto, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.NumeroNF, 
					(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor, 
					ContaReceberDados.ValorFinal,
					substr(Pessoa.Nome,1,20) Nome, 
					Pessoa.CPF_CNPJ, 
					ContaReceberRecebimento.IdStatus, 
					substr(Cidade.NomeCidade,1,15) NomeCidade,
					Estado.SiglaEstado,
					ContaReceberDados.DataLancamento,
					ContaReceberDados.NumeroDocumento,
					ContaReceberRecebimento.IdCaixa,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento,
					substring(ContaReceberRecebimento.DataCriacao,1,10) DataCriacao
				from
					ContaReceberDados
						left join ContaReceberRecebimento on (ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber),
					
					LancamentoFinanceiroContaReceber,
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					PessoaEndereco,
					Pais,
			    	Estado,
			    	Cidade,
					LocalCobranca,
					LancamentoFinanceiro 
						left join Contrato on 
											(LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
											LancamentoFinanceiro.IdContrato = Contrato.IdContrato)
						left join OrdemServico on 
											(LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
											LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico)
						left join Servico on
											(Servico.IdLoja = Contrato.IdLoja and
											(Servico.IdServico = Contrato.IdServico or Servico.IdServico = OrdemServico.IdServico))
				where
					ContaReceberDados.IdLoja = $local_IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
					ContaReceberDados.IdStatus = 2 and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pais.IdPais = PessoaEndereco.IdPais and
					Estado.IdEstado = PessoaEndereco.IdEstado and
					Cidade.IdCidade = PessoaEndereco.IdCidade and
					Pais.IdPais = Estado.IdPais and
		       	 	Pais.IdPais = Cidade.IdPais and
		       	 	Estado.IdEstado = Cidade.IdEstado
					$filtro_sql 
				order by
					ContaReceberRecebimento.DataCriacao desc
				$Limit";	
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$query = 'true';
		
		if($lin[IdContrato]!=''){
			if($_SESSION["RestringirCarteira"] == true){
				$sqlTemp =	"select 
								AgenteAutorizadoPessoa.IdContrato 
							from 
								AgenteAutorizadoPessoa,
								Carteira 
							where 
								AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $local_IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1 and
								AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
				$resTemp	=	@mysql_query($sqlTemp,$con);
				if(@mysql_num_rows($resTemp) == 0){
					$query = 'false';
				}
			}else{
				if($_SESSION["RestringirAgenteAutorizado"] == true){
					$sqlTemp =	"select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
				if($_SESSION["RestringirAgenteCarteira"] == true){
					$sqlTemp		=	"select 
											AgenteAutorizadoPessoa.IdContrato
										from 
											AgenteAutorizadoPessoa,
											AgenteAutorizado,
											Carteira
										where 
											AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
											AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
											AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
											Carteira.IdCarteira = $local_IdPessoaLogin and 
											AgenteAutorizado.Restringir = 1 and 
											AgenteAutorizado.IdStatus = 1 and
											AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
		
		if($query == 'true'){
		
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				$lin[Valor]			=	0;		}
			
			if($lin[ValorDesconto]!='')	$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
		
			switch($lin[IdStatus]){
				case '0': 
					$Color	  	=	getParametroSistema(15,2);
					$ImgExc	  	= "../../img/estrutura_sistema/ico_del_c.gif";
					$lin[Link]	= "";	
					break;
				case '1': #Recebido
					$Color	  	= getParametroSistema(15,3);
					$ImgExc	  	= "../../img/estrutura_sistema/ico_del.gif";		
					$lin[Link]	= "cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber=$lin[IdContaReceber]&IdContaReceberRecebimento=$lin[IdContaReceberRecebimento]";
					break;
				case '3': #Estorno
					$Color	  	= "";		
					$lin[Link]	= "";		
					$ImgExc	  	= "../../img/estrutura_sistema/ico_del_c.gif";
					break;
			}
			
			if($lin[ValorRecebido] != ''){
				$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
			}else{
				$lin[ValorRecebidoTemp]		=	'';
				$lin[ValorRecebido]			=	0;
			}
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			$lin[NomeCidade]	=	$lin[NomeCidade]."-".$lin[SiglaEstado];
			
			if($lin[IdStatus]!=""){
				$lin[DescricaoStatus]	=	getParametroSistema(85,$lin[IdStatus]);
			}
			
			if($lin[IdCaixa] != ''){
				$lin3 = array("AbreviacaoNomeLocalCobranca" => "Caixa ".$lin[IdCaixa]);
			} else {
				$sql3	=	"select 
								AbreviacaoNomeLocalCobranca 
						from 
								LocalCobranca 
						where 
								LocalCobranca.IdLoja= $local_IdLoja and 
								LocalCobranca.IdLocalCobranca = $lin[IdLocalCobrancaRecebimento]";
				$res3	=	@mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
			}
			if($filtro_tipo_impressao == 1){
				echo "<reg>";	
				echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
				echo 	"<IdContaReceberRecebimento>$lin[IdContaReceberRecebimento]</IdContaReceberRecebimento>";
				echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
				echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
				echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";	
				echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
				echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
				
				echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
				
				echo 	"<Valor>$lin[Valor]</Valor>";
				echo 	"<ValorRecebido>$lin[ValorRecebido]</ValorRecebido>";
				echo 	"<ValorRecebidoTemp><![CDATA[$lin[ValorRecebidoTemp]]]></ValorRecebidoTemp>";
				
				echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
				
				echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
				echo 	"<Color><![CDATA[$Color]]></Color>";
				echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
				
				echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
				echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
				echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
				echo 	"<DescricaoLocalRecebimento><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></DescricaoLocalRecebimento>";
				echo 	"<DataCriacao><![CDATA[".dataConv($lin[DataCriacao],'Y-m-d','d/m/Y')."]]></DataCriacao>";
				echo 	"<DescricaoStatus><![CDATA[$lin[DescricaoStatus]]]></DescricaoStatus>";
				
				echo "</reg>";	
				
				$cont++;
				
				if($filtro_limit!= ""){
					if($cont >= $filtro_limit){
						break;
					}
				}
			} else{
				$tamanho = 1;
				$lancamento = "";
				$valorlancamento = "";
				if($cont % 2 == 0){
					$pdf->SetFillColor(205, 201, 201);
				}else{
					$pdf->SetFillColor(238, 233, 233);
				}
				
				$totalvalor   += $lin[Valor];
				$totalreceber += $lin[ValorRecebido];
				$pdf->SetFont('Arial','',6.5);
				$pdf->Ln();
				$pdf->Cell(0.9,0.5, $lin[IdContaReceber], 0,0, 'L',true);
				$pdf->Cell(1.0,0.5, $lin[IdContaReceberRecebimento], 0,0, 'L',true);
				$pdf->Cell(5.8,0.5,$lin[Nome], 0,0, 'L',true);			
				
				$tamanho1 = 10.25;
				
				if($filtro_cpf_cnpj == 2){
					$pdf->Cell(2.3, 0.5, $lin[CPF_CNPJ], 0,0, 'L',true);
					$tamanho1 = $tamanho1+2.4;
				}
				
				if($filtro_nota_fiscal == 2){
					$pdf->Cell(2.3,0.5,$lin[NumeroNF]. " ".$lin[ModeloNF], 0,0, 'L',true);
					$tamanho1 = $tamanho1+2.4;
				}
				
				$pdf->Cell(1.9,0.5,$lin[NumeroDocumento], 0,0, 'L',true);
				
				if($filtro_oculta_cidade_uf == 2){
					$pdf->Cell(4.3,0.5,$lin[NomeCidade], 0,0, 'L',true);
				}
		
				$pdf->Cell(1.35,0.5,number_format($lin[Valor],2,',',''),0,0, 'R',true);
				$pdf->Cell(2.15,0.5,$lin[DataVencimentoTemp], 0,0, 'R',true);
				$pdf->Cell(1.95,0.5,$lin[ValorRecebidoTemp], 0,0, 'R',true);
				$pdf->Cell(2.6,0.5,$lin[DataRecebimentoTemp], 0,0, 'C',true);
				$pdf->Cell(2.5,0.5,$lin[AbreviacaoNomeLocalCobranca], 0,0, 'L',true);
				$pdf->Cell(2.5,0.5,$lin[DescricaoStatus], 0,0, 'L',true);
				
				$cont++;
			}
		}
	}
	if($filtro_tipo_impressao == 1){
		echo "</db>";
	} else{
		$pdf->Cell(2.5,0.7,$lin[DescricaoStatus], 0,0, 'L',0);
		$pdf->Ln();
		$pdf->setFont('Arial','B',7);
		$pdf->Cell(29.4, 0.05,'','T',0, 'L',0);
		$pdf->Ln();
		$pdf->Cell(2.5,0.5,'Total: '.$cont, 0,0, 'L',0);
		$pdf->Cell($tamanho1,0.5,number_format($totalvalor,2,',',''), 0,0, 'R',0);
		$pdf->Cell(4.1,0.5,number_format($totalreceber,2,',',''), 0,0, 'R',0);
		$pdf->Ln();
		$pdf->Cell(29.1,0.5,html_codes(getParametroSistema(4,1),'decode'), 0,0, 'C',0);
		$pdf->Output("Relatório Contas Receber Movimentação Diária.pdf","D");
	}
?>