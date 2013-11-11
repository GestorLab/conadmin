<?
	$localModulo		=	1;
	$localOperacao		=	108;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION["IdLoja"]; 
	$IdPessoaLogin					= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_forma_cobranca			= $_POST['filtro_forma_cobranca'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_status					= $_POST['filtro_status'];
	
	$filtro_estado					= $_POST['filtro_estado'];
	$filtro_cidade					= $_POST['filtro_cidade'];
	$filtro_bairro					= url_string_xsl($_POST['filtro_bairro'],'url',false);
	$filtro_contrato_status			= $_POST['filtro_contrato_status'];	
	
	if($_GET['IdProcessoFinanceiro']!= ''){
		$filtro_processo_financeiro	= $_GET['IdProcessoFinanceiro'];
	}
	
	if($filtro_conta_receber == '' && $_GET['IdContaReceber']!=''){
		$filtro_conta_receber = $_GET['IdContaReceber'];
	}

	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_sql2 = "";
	$filtro_from = "";
	$sqlAux		 = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url	.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url	.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and ContaReceber.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceber.IdLocalCobranca = $filtro_local_cobranca";
		$sqlAux 	.= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";	
	}
		
	if($filtro_data_inicio!=""){
		$filtro_url  .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio	=	dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceber.DataVencimento >= '$filtro_data_inicio'";
		$sqlAux		.= " and ContaReceberDados.DataVencimento >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_fim!=""){
		$filtro_url  .= "&DataTermino=".$filtro_data_fim;
		$filtro_data_fim	=	dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceber.DataVencimento <= '$filtro_data_fim'";
		$sqlAux		.= " and ContaReceberDados.DataVencimento <= '$filtro_data_fim'";
	}
	
	if($filtro_forma_cobranca!=""){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		
		switch($filtro_forma_cobranca){
			case 'C':
				$filtro_sql .= " and Pessoa.Cob_FormaCorreio = 'S'";
				break;
			case 'E':
				$filtro_sql .= " and Pessoa.Cob_FormaEmail = 'S'";
				break;
			case 'O':
				$filtro_sql .= " and Pessoa.Cob_FormaOutro = 'S'";
				break;
		}
	}
	
	if($filtro_estado!=''){
		$filtro_url .= "&IdEstado=$filtro_estado";
		$filtro_sql2 .=	" and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=$filtro_cidade";
		$filtro_sql2 .=	" and PessoaEndereco.IdCidade = $filtro_cidade";
	}
	
	if($filtro_bairro!=''){
		$filtro_url .= "&Bairro=$filtro_bairro";
		$filtro_sql2 .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_contrato_status!=''){
		$filtro_url .= "&IdContratoStatus=$filtro_contrato_status";	
		
		$aux	=	explode("G_",$filtro_contrato_status);
																
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$filtro_sql .=	" and ContaReceber.IdContaReceber in (select																	
																	LancamentoFinanceiroContaReceber.IdContaReceber
															    from
															    	Contrato,
																	LancamentoFinanceiro,															
																	LancamentoFinanceiroContaReceber
																where	
																	Contrato.IdLoja = $local_IdLoja and
																	Contrato.IdLoja = LancamentoFinanceiro.IdLoja and 
																	LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																	Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
																	(Contrato.IdStatus >= 1 and Contrato.IdStatus < 100) and																														
																	LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
																)";										
					break;
				case '2':
					$filtro_sql .=	" and ContaReceber.IdContaReceber in (select																	
																	LancamentoFinanceiroContaReceber.IdContaReceber
															    from
															    	Contrato,
																	LancamentoFinanceiro,															
																	LancamentoFinanceiroContaReceber
																where	
																	Contrato.IdLoja = $local_IdLoja and
																	Contrato.IdLoja = LancamentoFinanceiro.IdLoja and 
																	LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																	Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
																	(Contrato.IdStatus >= 200 and Contrato.IdStatus < 300) and																														
																	LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
																)";						
					break;
				case '3':
					$filtro_sql .=	" and ContaReceber.IdContaReceber in (select																	
																	LancamentoFinanceiroContaReceber.IdContaReceber
															    from
															    	Contrato,
																	LancamentoFinanceiro,															
																	LancamentoFinanceiroContaReceber
																where	
																	Contrato.IdLoja = $local_IdLoja and
																	Contrato.IdLoja = LancamentoFinanceiro.IdLoja and 
																	LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																	Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
																	(Contrato.IdStatus >= 300 and Contrato.IdStatus < 400) and																														
																	LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
																)";											
					break;
			}
		}else{
			$filtro_sql .=	" and ContaReceber.IdContaReceber in (select																	
																	LancamentoFinanceiroContaReceber.IdContaReceber
															    from
															    	Contrato,
																	LancamentoFinanceiro,															
																	LancamentoFinanceiroContaReceber
																where	
																	Contrato.IdLoja = $local_IdLoja and
																	Contrato.IdLoja = LancamentoFinanceiro.IdLoja and 
																	LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																	Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
																	Contrato.IdStatus = '$filtro_contrato_status' and																														
																	LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
																)";	
		}
	}
		
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1";
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
							Carteira.IdCarteira = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
			
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
			
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit 0,".$filtro_limit;
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	header ("content-type: text/xml");

	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_reaviso_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql_ 	= 	"select
					distinct
					ContaReceber.IdPessoa,					
					ContaReceber.IdPessoaEndereco
				 from
				 	Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
				 	ContaReceber,				 
				 	LocalCobranca
				where
				 	ContaReceber.IdLoja = $local_IdLoja and
				 	ContaReceber.IdLoja = LocalCobranca.IdLoja and
				 	ContaReceber.IdPessoa = Pessoa.IdPessoa and
				 	ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and				 	
					ContaReceber.IdStatus = 1 and
					ContaReceber.DataVencimento < curdate()
				 	$filtro_sql
				order by
					ContaReceber.IdContaReceber desc
				$Limit";
	$res_	=	mysql_query($sql_,$con);
	while($lin_	=	mysql_fetch_array($res_)){			
	
		$sql	=	"select																
						Pessoa.IdPessoa, 
						Pessoa.Nome, 
						Pessoa.RazaoSocial, 	
						Pessoa.RG_IE,
						Pessoa.CPF_CNPJ, 
						Pessoa.Telefone1, 
						Pessoa.Telefone2, 
						Pessoa.Telefone3,
						Pessoa.Celular,
						Pessoa.Fax,
						Pessoa.ComplementoTelefone,
						Pessoa.Email, 	
						Pessoa.TipoPessoa, 	
						PessoaEndereco.Numero, 
						PessoaEndereco.Endereco, 
						PessoaEndereco.CEP, 
						PessoaEndereco.Bairro, 					
						Cidade.NomeCidade, 
						Estado.SiglaEstado			
					from 					
						Pessoa,
						PessoaEndereco,
						Pais,
						Estado,
						Cidade														
					where					
						Pessoa.IdPessoa = $lin_[IdPessoa] and
						PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
						PessoaEndereco.IdPessoaEndereco = $lin_[IdPessoaEndereco] and
						PessoaEndereco.IdPais = Pais.IdPais and
						PessoaEndereco.IdPais = Estado.IdPais and
						PessoaEndereco.IdPais = Cidade.IdPais and
						PessoaEndereco.IdEstado = Estado.IdEstado and
						PessoaEndereco.IdEstado = Cidade.IdEstado and
						PessoaEndereco.IdCidade = Cidade.IdCidade $filtro_sql2				
		      		order by 
						Pessoa.RazaoSocial, 
						Pessoa.Nome ";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
			if($lin[TipoPessoa] == 2){	
				$lin[Nome]		=	$lin[Nome];
				$lin[CampoNome]	=	'Nome';
			}else{
				$lin[Nome]		=	$lin[RazaoSocial];
				$lin[CampoNome]	=	'Razão Social';
			}
			$lin[Endereco]		=	$lin[Endereco].", ".$lin[Numero];
			if($lin[Complemento]!= '' )	$lin[Endereco] .=	" - ".$lin[Complemento];
			if($lin[Bairro]!= '' )	 	$lin[Endereco] .=	" - Bairro: ".$lin[Bairro];
			$lin[Endereco]	.=	" - ".$lin[NomeCidade]."-".$lin[SiglaEstado]." - Cep: ".$lin[CEP];
			
			if($lin[TipoPessoa] == 1){
				$lin[cpCNPJ]	=	'CNPJ';
				$lin[cpIE]		=	'IE';
			}
			if($lin[TipoPessoa] == 2){
				$lin[cpCNPJ]	=	'CPF';
				$lin[cpIE]		=	'RG';
			}		
				
			echo "<reg>";	
			echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
			echo 	"<CampoNome><![CDATA[$lin[CampoNome]]]></CampoNome>";
			echo 	"<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
			echo 	"<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
			echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
			echo 	"<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
			echo 	"<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
			echo 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";
			echo 	"<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
			echo 	"<Fax><![CDATA[$lin[Fax]]]></Fax>";
			echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
			echo 	"<cpCNPJ><![CDATA[$lin[cpCNPJ]]]></cpCNPJ>";
			echo 	"<cpIE><![CDATA[$lin[cpIE]]]></cpIE>";		
			
			$cont  	=   0;
			$sql2	=  "select
							 ContaReceberDados.IdContaReceber,
							 ContaReceberDados.ValorFinal,
						 	 ContaReceberDados.DataVencimento,
						 	 ContaReceberDados.NumeroDocumento,
						 	 (ContaReceberDados.ValorJurosAtualizado + ContaReceberDados.ValorMultaAtualizado) ValorMultaJurosAtualizado,						 							 	 
						 	 ContaReceberDados.ValorFinalAtualizado
						from 
							 ContaReceberDados
						where
							 ContaReceberDados.IdLoja   = $local_IdLoja and
							 ContaReceberDados.IdPessoa = $lin[IdPessoa] and
		 					 ContaReceberDados.IdStatus = 1 and
		 					 ContaReceberDados.DataVencimento < curdate()
							 $sqlAux	  						 
						order by 
							 ContaReceberDados.IdContaReceber";
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
	
				$ContaReceberTipo = "";
				
				$sql3 	=  "select
							 	 distinct
								 Demonstrativo.Tipo								 
							from 
								 Demonstrativo
						    where
						 		 Demonstrativo.IdLoja = $local_IdLoja and
								 Demonstrativo.IdContaReceber = $lin2[IdContaReceber]";
				$res3	=	mysql_query($sql3,$con);
				while($lin3	=	mysql_fetch_array($res3)){
					if($ContaReceberTipo != "") $ContaReceberTipo .= "/";				
					$ContaReceberTipo .= $lin3[Tipo];
				}							
				
				$lin2[DataVencimento] = dataConv($lin2[DataVencimento],"Y-m-d","d/m/Y");
							
				echo "	<lancamentos>";
				echo "		<IdContaReceber>$lin2[IdContaReceber]</IdContaReceber>";
				echo "		<NumeroDocumento><![CDATA[$lin2[NumeroDocumento]]]></NumeroDocumento>";
				echo "		<DataVencimento><![CDATA[$lin2[DataVencimento]]]></DataVencimento>";
				echo "		<Valor><![CDATA[$lin2[ValorFinal]]]></Valor>";
				echo "		<ValorMultaJurosAtualizado><![CDATA[$lin2[ValorMultaJurosAtualizado]]]></ValorMultaJurosAtualizado>";			
				echo "		<ValorFinalAtualizado><![CDATA[$lin2[ValorFinalAtualizado]]]></ValorFinalAtualizado>";				
				echo "		<Tipo><![CDATA[$ContaReceberTipo]]></Tipo>";
			
				echo "	</lancamentos>";
				
				$cont++;
			
			}
				
			echo "</reg>";
		}
	}
	echo "</db>";
?>
