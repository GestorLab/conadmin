<?
	$localModulo		=	1;
	$localOperacao		=	134;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja				= $_SESSION['IdLoja']; 
	$local_Login				= $_SESSION['Login']; 
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_servico				= $_POST['filtro_servico'];
	$filtro_nome				= $_POST['filtro_nome'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_valor				= $_POST['filtro_valor'];
	$filtro_pessoa				= $_GET['IdPessoa'];
	$filtro_lancamento			= $_GET['IdLancamentoFinanceiro'];
	$filtro_contrato			= $_GET['IdContrato'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_forma_cobranca		= $_POST['filtro_forma_cobranca'];
	$filtro_terceiro			= $_POST['filtro_terceiro'];
	
	$filtro_percentual_repasse_terceiro 		= $_POST["filtro_percentual_repasse_terceiro"];
	$filtro_percentual_repasse_terceiro_outros	= $_POST["filtro_percentual_repasse_terceiro_outros"];

	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_servico%'";
	}
		
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_pessoa;
		$filtro_sql .= " and OrdemServico.IdPessoa = $filtro_pessoa";
	}
	
	if($filtro_contrato!=''){
		$filtro_url .= "&IdConrato=".$filtro_contrato;
		$filtro_sql .= " and OrdemServico.IdContrato = $filtro_contrato";
		
		$filtro_os = "";
	}
	
	if($filtro_os!=''){
		$filtro_url .= "&IdOrdemServico=$filtro_os";
		$filtro_sql .=	" and OrdemServico.IdOrdemServico = '$filtro_os'";
	}
	
	if($filtro_forma_cobranca!=''){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		$filtro_sql .= " and OrdemServico.FormaCobranca = $filtro_forma_cobranca";	
	} 
	
	if($filtro_terceiro!=''){
		$filtro_url .= "&Terceiro=".$filtro_terceiro;
		$filtro_sql .= " and OrdemServico.IdTerceiro = $filtro_terceiro";	
	}
	
	if($filtro_percentual_repasse_terceiro!=''){
		$filtro_url .= "&PercentualRepasseTerceiro=$filtro_percentual_repasse_terceiro";
	}
	
	if($filtro_percentual_repasse_terceiro_outros!=''){
		$filtro_url .= "&PercentualRepasseTerceiroOutros=$filtro_percentual_repasse_terceiro_outros";	
	}

	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		switch($filtro_campo){		
			case 'MesFatura':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql  .= " and substring(OrdemServico.DataAlteracao,1,7) = '$filtro_valor'";
				}											
				break;			
			case 'MesVencimento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');					
					$filtro_sql    .=	" and substring(LancamentoFinanceiro.DataVencimento,1,7) like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and LancamentoFinanceiro.DataVencimento is NULL";
				}		
				break;
			case 'MesRecebimento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql  .= " and substring(ContaReceberRecebimento.DataRecebimento,1,7) = '$filtro_valor'";
				}											
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
	
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_ordem_servico_lote_repasse_terceiro_parcela_quitada_xsl.php$filtro_url\"?>";
	echo "<db>";
	
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
					LancamentoFinanceiroDados.IdContaReceber,
					LancamentoFinanceiroDados.IdLancamentoFinanceiro,
					LancamentoFinanceiroDados.IdOrdemServico,
					LancamentoFinanceiroDados.ValorDespesas,	
					LancamentoFinanceiroDados.Valor,
					LancamentoFinanceiroDados.ValorRepasseTerceiro,
					LancamentoFinanceiroDados.DataVencimento,
					OrdemServico.IdTerceiro,
					OrdemServico.IdStatus,
					OrdemServico.FormaCobranca,
					substr(Servico.DescricaoServico,1,12) DescricaoServico,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,12) Nome,
					substr(Pessoa.RazaoSocial,1,12) RazaoSocial,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.ValorRecebido,
					DemonstrativoReferencia.Referencia
				from
					LancamentoFinanceiroDados,
					ContaReceberRecebimento,
					DemonstrativoReferencia,
					OrdemServico,
					Servico left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					Pessoa	
				where
					LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
					LancamentoFinanceiroDados.IdLoja = OrdemServico.IdLoja and
					LancamentoFinanceiroDados.IdLoja = Servico.IdLoja and
					LancamentoFinanceiroDados.IdLoja = ContaReceberRecebimento.IdLoja and
					LancamentoFinanceiroDados.IdLoja = DemonstrativoReferencia.IdLoja and
					LancamentoFinanceiroDados.IdLancamentoFinanceiro = DemonstrativoReferencia.IdLancamentoFinanceiro and	
					LancamentoFinanceiroDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and	
					LancamentoFinanceiroDados.IdOrdemServico = OrdemServico.IdOrdemServico and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					OrdemServico.IdServico = Servico.IdServico and
					LancamentoFinanceiroDados.IdContaReceber and
					LancamentoFinanceiroDados.Tipo = 'OS' and
					LancamentoFinanceiroDados.IdStatusContaReceber = 2 and
					ContaReceberRecebimento.IdStatus = 1 and
					LancamentoFinanceiroDados.ValorRepasseTerceiro > 0 
					$filtro_sql 
				order by
					LancamentoFinanceiroDados.IdOrdemServico desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
				
		$sql2	=	"select 
						Nome NomeTerceiro
					from 
						Pessoa 
					where 
						IdPessoa = $lin[IdTerceiro]"; 
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		#$Color	  =	getParametroSistema(15,3);
		$Color = getOrdemServicoCor($lin[IdOrdemServico]);			
		$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
							
		$lin[FormaCobranca] 	 = getParametroSistema(50, $lin[FormaCobranca]); 	
		
		$sql4	=	"select 		
						ValorRepasseTerceiro,					
						PercentualRepasseTerceiro,
						PercentualRepasseTerceiroOutros
					from 
						ServicoValor 
					where 
						IdLoja = $local_IdLoja and
						DataInicio <= curdate() and 
						(DataTermino is Null  or DataTermino >= curdate()) and 						 
						IdServico = $lin[IdServico] 
					order BY 
						DataInicio DESC 
					LIMIT 0,1"; 
		$res4	=	@mysql_query($sql4,$con);
		$lin4	=	@mysql_fetch_array($res4);
							
		$lin4[ValorRepasseTerceiro] 		= ($lin4[PercentualRepasseTerceiro] * $lin[Valor]) / 100;
		$lin4[ValorRepasseTerceiroOutros] 	= ($lin4[PercentualRepasseTerceiroOutros] * $lin[ValorOutros]) / 100;		
		$lin4[ValorTotalRepasseTerceiro] 	= $lin4[ValorRepasseTerceiro] + $lin4[ValorRepasseTerceiroOutros];
		$lin[ValorTotal] 					= $lin[Valor] + $lin[ValorOutros];		
		
		if($lin[IdStatus] == 100){
			$lin3[ValorParametroSistema] .= " (Faturado)";
		}
		
		$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
		$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","YmdHis");
		
		$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
		$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","YmdHis");
		
		echo "<reg>";	
		echo 	"<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
		echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
		echo 	"<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<NomeTerceiro><![CDATA[$lin2[NomeTerceiro]]]></NomeTerceiro>";
		echo 	"<Parcela><![CDATA[$lin[Referencia]]]></Parcela>";
		echo 	"<Valor><![CDATA[$lin[Valor]]]></Valor>";
		echo 	"<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
		echo 	"<ValorOutros><![CDATA[$lin[ValorOutros]]]></ValorOutros>";
		echo 	"<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
		echo 	"<DataFaturamentoTemp><![CDATA[$lin[DataFaturamentoTemp]]]></DataFaturamentoTemp>";
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<FormaCobranca><![CDATA[$lin[FormaCobranca]]]></FormaCobranca>";
		echo 	"<PercentualRepasseTerceiro><![CDATA[$lin4[PercentualRepasseTerceiro]]]></PercentualRepasseTerceiro>";
		echo 	"<PercentualRepasseTerceiroOutros><![CDATA[$lin4[PercentualRepasseTerceiroOutros]]]></PercentualRepasseTerceiroOutros>";
		echo 	"<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
		echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
		echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
		echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
		echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";	
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
