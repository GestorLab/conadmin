<?
	$localModulo		=	1;
	$localOperacao		=	18;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	 

	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138, 1));
	
	$IdLoja						= $_SESSION['IdLoja'];
	$IdPessoaLogin				= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_erro				= $_GET['Erro'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_nome				= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_tipo				= $_POST['filtro_tipo'];
	$filtro_valor				= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_local_cobranca		= $_POST['filtro_local_cobranca'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_pessoa				= $_GET['IdPessoa'];
	$filtro_contrato			= $_GET['IdContrato'];
	$filtro_lancamentos			= $_POST['Lancamentos'];
	$filtro_processo_financeiro	= $_POST['IdProcessoFinanceiro'];
	$filtro_conta_eventual		= $_POST['IdContaEventual'];
	$filtro_ordem_servico		= $_POST['IdOrdemServico'];
	$filtro_lanc_financeiro		= $_GET['IdLancamentoFinanceiro'];
	
	if($filtro_limit == '' && $_GET['filtro_limit'] != ''){
		$filtro_limit 	= $_GET['filtro_limit'];
	}
	
	if($filtro_processo_financeiro == '' && $_GET['IdProcessoFinanceiro'] != ''){
		$filtro_processo_financeiro 	= $_GET['IdProcessoFinanceiro'];
	}
	
	if($filtro_conta_eventual == '' && $_GET['IdContaEventual'] != ''){
		$filtro_conta_eventual 	= $_GET['IdContaEventual'];
	}
	
	if($filtro_ordem_servico == '' && $_GET['IdOrdemServico'] != ''){
		$filtro_ordem_servico 	= $_GET['IdOrdemServico'];
	}
	
	if($_GET['IdContaReceber'] != ''){
		$filtro_idConta				= $_GET['IdContaReceber'];	
	}	
	if($filtro_lancamentos	== "" && $_GET['Lancamentos'] != ''){
		$filtro_lancamentos			= $_GET['Lancamentos'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_erro != "")
		$filtro_url		.= "&Erro=$filtro_erro";	
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_ordem_servico!=''){
		$filtro_url .= "&IdOrdemServico=$filtro_ordem_servico";
		$filtro_sql .=	" and LancamentoFinanceiro.IdOrdemServico = '$filtro_ordem_servico'";
		
		$filtro_pessoa	=	"";
	}
	
	if($filtro_processo_financeiro!=''){
		$filtro_url .= "&IdProcessoFinanceiro=$filtro_processo_financeiro";
		$filtro_sql .=	" and Demonstrativo.IdProcessoFinanceiro = '$filtro_processo_financeiro'";
	}
	
	if($filtro_lanc_financeiro!=''){
		$filtro_url .= "&IdLancamentoFinanceiro=$filtro_lanc_financeiro";
		$filtro_sql .=	" and Demonstrativo.IdLancamentoFinanceiro = '$filtro_lanc_financeiro'";
	}
	
	if($filtro_conta_eventual!=''){
		$filtro_url .= "&IdContaEventual=$filtro_conta_eventual";
		$filtro_sql .=	" and Demonstrativo.Codigo = '$filtro_conta_eventual'";
	}
	
	if($filtro_idConta!=''){
		$filtro_url .= "&IdContaReceber=$filtro_idConta";
		$filtro_sql .=	" and Demonstrativo.IdContaReceber = '$filtro_idConta'";
	}
		
	if($filtro_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and Demonstrativo.IdStatus = $filtro_status";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=$filtro_pessoa";
		$filtro_sql .=	" and Demonstrativo.IdPessoa = '$filtro_pessoa'";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and  Demonstrativo.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_tipo!=""){
		$filtro_url .= "&TipoLancamentoFinanceiro=".$filtro_tipo;
		$filtro_sql .= " and LancamentoFinanceiro.TipoLancamentoFinanceiro = '$filtro_tipo'";
	}
	
	if($filtro_lancamentos!=""){
		$filtro_url .= "&Lancamentos=$filtro_lancamentos";
		$filtro_sql .=	" and Demonstrativo.IdLancamentoFinanceiro in ($filtro_lancamentos)";
	}
	
	if($filtro_contrato != ""){
		$filtro_url .= "&IdContrato=$filtro_contrato";
		$filtro_sql .=	" and LancamentoFinanceiro.IdContrato = $filtro_contrato";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_valor;
		
		switch($filtro_campo){
			case 'DataReferenciaInicial':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and LancamentoFinanceiro.DataReferenciaInicial = '$filtro_valor'";
				}else{
					$filtro_sql .= " and LancamentoFinanceiro.DataReferenciaInicial is NULL";
				}
				break;
			case 'DataReferenciaFinal':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and LancamentoFinanceiro.DataReferenciaFinal = '$filtro_valor'";
				}else{
					$filtro_sql .= " and LancamentoFinanceiro.DataReferenciaFinal is NULL";
				}
				break;
			case 'DescricaoServico':
				$filtro_sql .=	" and (Descricao like '%$filtro_valor%')";
				break;
			case 'IdContaReceber':
				$filtro_sql .= " and Demonstrativo.IdContaReceber = '$filtro_valor'";
				break;
			case 'IdContrato':
				$filtro_sql .=	" and Demonstrativo.Codigo = '$filtro_valor'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .= " and Demonstrativo.IdProcessoFinanceiro = '$filtro_valor'";
				break;
		}
		
	}else{
		$filtro_valor	=	"";
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
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
							AgenteAutorizado.IdLoja = $IdLoja  and 
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
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_lancamento_financeiro_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	
	if($filtro != "s"){
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$cont	=	0;
	$sql	=	"select
				      Demonstrativo.IdLoja,
					  Demonstrativo.IdContaReceber,
				      Demonstrativo.IdLancamentoFinanceiro,
				      Demonstrativo.IdProcessoFinanceiro,
				      Demonstrativo.IdPessoa,
					  Pessoa.TipoPessoa,
				      substr(Pessoa.Nome,1,15) Nome,
				      substr(Pessoa.RazaoSocial,1,15) RazaoSocial,
				      Demonstrativo.Tipo,
				      Demonstrativo.Codigo,
				      substr(Demonstrativo.Descricao,1,15) Descricao,
				      Demonstrativo.Referencia,
				      Demonstrativo.Valor,
				      Demonstrativo.ValorDespesas,
				      Demonstrativo.ValorDescontoAConceber,
				      Demonstrativo.IdStatus
				from
				     Demonstrativo,
				     Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
				     LancamentoFinanceiro $sqlAux
				where
				     Demonstrativo.IdLoja = $IdLoja and
				     Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja and
					 Demonstrativo.IdPessoa = Pessoa.IdPessoa and
					 Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro $filtro_sql
				order by
				     Demonstrativo.IdPessoa, Demonstrativo.Tipo, Demonstrativo.Codigo, IdLancamentoFinanceiro $Limit ";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		ob_start("out_buffer");
		
		$query = 'true';
		
		if($lin[Tipo]=='CO' && $lin[Codigo]!=""){
			if($_SESSION["RestringirCarteira"] == true){
				$sqlTemp =	"select 
								AgenteAutorizadoPessoa.IdContrato 
							from 
								AgenteAutorizadoPessoa,
								Carteira 
							where 
								AgenteAutorizadoPessoa.IdLoja = $IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1 and
								AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
									AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
											AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
		
		if($query == 'true'){
			$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=51 and IdParametroSistema=$lin[IdStatus]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			if($lin[Valor] == 0){
				$lin[Valor]	=	0;
			}
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
			}
			
			switch($lin[IdStatus]){
				case '0':	//Cancelado
					$Color		=	getParametroSistema(15,2);	
					$ImgExc		= "../../img/estrutura_sistema/ico_del_c.gif";
					break;
				case '1':	//Gerado
					$Color		=	getParametroSistema(15,3);	
					$ImgExc		= "../../img/estrutura_sistema/ico_del_c.gif";
					break;
				case '2':	//Aguardando Cobrança	
					$Color		=	getParametroSistema(15,5);
					$ImgExc		= "../../img/estrutura_sistema/ico_del.gif";	
					break;
				case '3':	//Em Analise
					$Color		=	getParametroSistema(15,5);	
					$ImgExc		= "../../img/estrutura_sistema/ico_del.gif";
					break;
				default:
					$Color		=	"";
					$ImgExc		= "../../img/estrutura_sistema/ico_del_c.gif";
			}			
			if($lin[Tipo] == "CR"){
				$lin[Codigo] = $lin[IdContaReceber];
			}
			
			echo "<reg>";	
			echo 	"<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
			echo 	"<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
			echo 	"<IdProcessoFinanceiro>$lin[IdProcessoFinanceiro]</IdProcessoFinanceiro>";
			echo 	"<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			echo 	"<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
			echo	"<Codigo><![CDATA[$lin[Codigo]]]></Codigo>";
			echo	"<Referencia><![CDATA[$lin[Referencia]]]></Referencia>";			
			echo 	"<Valor><![CDATA[$lin[Valor]]]></Valor>";
			echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			echo 	"<ValorDescontoAConceber><![CDATA[$lin[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
			echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
			echo 	"<Color><![CDATA[$Color]]></Color>";
			echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
			echo "</reg>";	
			
			$cont++;
			
			if($filtro_limit!= ""){
				if($cont >= $filtro_limit){
					break;
				}
			}
		}
	}
	
	echo "</db>";
?>
