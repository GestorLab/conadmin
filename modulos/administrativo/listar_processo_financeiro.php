<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');


	$local_IdLoja				= $_SESSION['IdLoja'];	
	$local_IdPessoaLogin		= $_SESSION["IdPessoa"];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_mes_referencia		= $_POST['filtro_mes_referencia'];
	$filtro_idstatus			= $_POST['filtro_idstatus'];
	$filtro_menor_vencimento	= $_POST['filtro_menor_vencimento'];
	$filtro_local_cobranca		= $_POST['filtro_local_cobranca'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_pessoa				= $_POST['IdPessoa'];
	$filtro_processo			= $_POST['IdProcessoFinanceiro'];
	$filtro_contrato			= $_POST['IdContrato'];
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_valor_campo			= $_POST['filtro_valor_campo'];
	$filtro_usuario_cadastro	= $_POST['filtro_usuario_cadastro'];
	$filtro_usuario_alteracao	= $_POST['filtro_usuario_alteracao'];
	$filtro_usuario_confirmacao	= $_POST['filtro_usuario_confirmacao'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro_processo	=='' && $_GET['IdProcessoFinanceiro']!=''){
		$filtro_processo	= $_GET['IdProcessoFinanceiro'];
	}
	
	if($filtro_pessoa	=='' && $_GET['IdPessoa']!=''){
		$filtro_pessoa	= $_GET['IdPessoa'];
	}
	
	if($filtro_contrato	=='' && $_GET['IdContrato']!=''){
		$filtro_contrato	= $_GET['IdContrato'];
	}
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_mes_referencia!=''){
		$filtro_url .= "&MesReferencia=$filtro_mes_referencia";
		$filtro_sql .=	" and ProcessoFinanceiro.MesReferencia = '$filtro_mes_referencia'";
	}
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&ValorCampo=$filtro_valor_campo";
		
		switch($filtro_campo){
			case 'DataCadastro':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = dataConv($filtro_valor_campo,'d/m/Y','Y-m-d');
					$filtro_sql			.=	" and subString(ProcessoFinanceiro.DataCriacao,1,10) = '$filtro_valor_campo'";
				}
				break;
			case 'DataAlteracao':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = dataConv($filtro_valor_campo,'d/m/Y','Y-m-d');
					$filtro_sql 		.=	" and subString(ProcessoFinanceiro.DataAlteracao,1,10) = '$filtro_valor_campo'";
				}				
				break;
			case 'DataConfirmacao':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = dataConv($filtro_valor_campo,'d/m/Y','Y-m-d');	
					$filtro_sql 		.=	" and subString(ProcessoFinanceiro.DataConfirmacao,1,10) = '$filtro_valor_campo'";
				}
				break;
			case 'MesCadastro':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = 	dataConv($filtro_valor_campo,'m/Y','Y-m');
					$filtro_sql 		.=	"and subString(ProcessoFinanceiro.DataCriacao,1,7) = '$filtro_valor_campo'";
				}
				break;	
			case 'MesAlteracao':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = 	dataConv($filtro_valor_campo,'m/Y','Y-m');
					$filtro_sql 		.=	"and subString(ProcessoFinanceiro.DataAlteracao,1,7) = '$filtro_valor_campo'";
				}
				break;
			case 'MesConfirmacao':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = 	dataConv($filtro_valor_campo,'m/Y','Y-m');
					$filtro_sql			.=	" and subString(ProcessoFinanceiro.DataConfirmacao,1,7) = '$filtro_valor_campo'";
				}
				break;
			case 'MesVencimento':
				if($filtro_valor_campo != ''){
					$filtro_sql 		.=	" and ProcessoFinanceiro.MesVencimento = '$filtro_valor_campo'";
				}
				break;
			case 'DataNotaFiscal':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = 	dataConv($filtro_valor_campo,'d/m/Y','Y-m-d');
					$filtro_sql 		.=	" and ProcessoFinanceiro.DataNotaFiscal = '$filtro_valor_campo'";
				}
				break;	
			case 'MesNotaFiscal':
				if($filtro_valor_campo != ''){
					$filtro_valor_campo  = 	dataConv($filtro_valor_campo,'m/Y','Y-m');
					$filtro_sql			.=	" and subString(ProcessoFinanceiro.DataNotaFiscal,1,7) = '$filtro_valor_campo'";
				}
				break;
		}		
	}
	
	if($filtro_local_cobranca!=''){
		$filtro_url .= "&IdLocalCobranca=$filtro_local_cobranca";
		$filtro_sql .=	" and ProcessoFinanceiro.Filtro_IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_usuario_cadastro!=''){
		$filtro_url .= "&UsuarioCadastro=$filtro_usuario_cadastro";
		$filtro_sql .=	" and ProcessoFinanceiro.LoginCriacao = '$filtro_usuario_cadastro'";
	}
	
	if($filtro_usuario_alteracao!=''){
		$filtro_url .= "&UsuarioAlteracao=$filtro_usuario_alteracao";
		$filtro_sql .=	" and ProcessoFinanceiro.LoginAlteracao = '$filtro_usuario_alteracao'";
	}
	
	if($filtro_usuario_confirmacao !=''){
		$filtro_url .= "&UsuarioConfirmacao=$filtro_usuario_confirmacao";
		$filtro_sql .=	" and ProcessoFinanceiro.LoginConfirmacao = '$filtro_usuario_confirmacao'";
	}
	
	if($filtro_contrato!=''){
		$filtro_url .= "&IdContrato=$filtro_contrato";
		$filtro_sql .=	" and (ProcessoFinanceiro.Filtro_IdContrato = '$filtro_contrato')";
	}
		
	if($filtro_idstatus!=""){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and ProcessoFinanceiro.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_menor_vencimento!=""){
		$filtro_url .= "&MenorVencimento=".$filtro_menor_vencimento;
		$filtro_sql .= " and ProcessoFinanceiro.MenorVencimento = $filtro_menor_vencimento";
	}
	
	if($filtro_pessoa!=""){
		$filtro_url .= "&IdPessoa".$filtro_pessoa;
		/*$sql = "SELECT
					Filtro_IdPessoa
				FROM
					ProcessoFinanceiro
				WHERE
					IdProcessoFinanceiro = $filtro_processo";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		if($lin[Filtro_IdPessoa] != "")*///Leonardo 16-01-13/query bugada >> se achar funcionalidade para tal favor informar!
			$filtro_sql .= " and ProcessoFinanceiro.Filtro_IdPessoa in (".$filtro_pessoa.")";
	}
	
	if($filtro_processo!=""){
		$filtro_url .= "&IdProcessoFinanceiro=".$filtro_processo;
		$filtro_sql .= " and ProcessoFinanceiro.IdProcessoFinanceiro = '$filtro_processo'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
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
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_processo_financeiro_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql	=	"select
				    ProcessoFinanceiro.IdLoja,
				    ProcessoFinanceiro.IdProcessoFinanceiro,
				    MesReferencia,
				    MenorVencimento,
				    MesVencimento,
				    ProcessoFinanceiro.IdStatus,
				    LocalCobranca.AbreviacaoNomeLocalCobranca,
				    Filtro_IdPessoa,
				    Filtro_IdServico,
				    Filtro_IdContrato,
				    Filtro_IdStatusContrato,
				    Filtro_FormaAvisoCobranca,
				    count(LancamentoFinanceiro.IdLancamentoFinanceiro) QuantLancamentosFinanceiros
				from
			      	ProcessoFinanceiro left join LancamentoFinanceiro on (
						ProcessoFinanceiro.IdLoja = LancamentoFinanceiro.IdLoja and 
						ProcessoFinanceiro.IdProcessoFinanceiro = LancamentoFinanceiro.IdProcessoFinanceiro
					) left join LocalCobranca on (
						ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and 
						ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca
					)
			    where
			      	ProcessoFinanceiro.IdLoja = $local_IdLoja 
					$filtro_sql
	            group by
	              	ProcessoFinanceiro.IdProcessoFinanceiro desc 
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=29 and IdParametroSistema=$lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);

		$sql3 = "select 
					count(*) Qtd
				from
					(select 
					LancamentoFinanceiro.IdLoja,
					LancamentoFinanceiro.IdProcessoFinanceiro
				from 
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where 
					LancamentoFinanceiro.IdLoja = $lin[IdLoja] and 
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiro.IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]
				group by
					LancamentoFinanceiro.IdLoja,
					LancamentoFinanceiroContaReceber.IdContaReceber) ContaReceberQtd";
		$res3 = mysql_query($sql3,$con);
		$lin3 = mysql_fetch_array($res3);

		$lin[QuantContasReceber] = $lin3[Qtd];
		
		$lin[MesReferenciaTemp]	=	$lin[MesReferencia];
		$lin[MesVencimentoTemp]	=	$lin[MesVencimento];
		
		$lin[MesReferencia]	=	dataConv($lin[MesReferencia],'m/Y','Ym');
		$lin[MesVencimento]	=	dataConv($lin[MesVencimento],'m/Y','Ym');
		
		if($lin[Filtro_IdPessoa] != '' || $lin[IdPais] != '' || $lin[IdEstado] != '' || $lin[IdCidade] != '' || $lin[Filtro_IdServico] != '' || $lin[Filtro_IdContrato]!='' || $lin[Filtro_IdStatusContrato]!='' || $lin[Filtro_FormaAvisoCobranca]!='')	 $lin[Filtros] = 'Sim';
		else							 $lin[Filtros] = 'Não';
		
		switch($lin[IdStatus]){
			case '1': //Cadastrado
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";
				$Color	  = "";	
				$lin4[ValorTotal] = "";
				break;
			case '2': //Em analise
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$Color	  = "";	
				
				$sql4	=	"
					select 
						sum(Valor) ValorTotal
					from 
						LancamentoFinanceiro
					where 
						LancamentoFinanceiro.IdLoja = $lin[IdLoja] and 
						LancamentoFinanceiro.IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
				$res4	=	mysql_query($sql4,$con);
				$lin4	=	mysql_fetch_array($res4);
				
				break;
			case '3': //Confirmado
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$Color	  = getParametroSistema(15,3);	
				
				
				$sql4	=	"
					select 
						sum(Valor) ValorTotal
					from 
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber
					where 
						LancamentoFinanceiro.IdLoja = $lin[IdLoja] and 
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
						LancamentoFinanceiro.IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
				$res4	=	mysql_query($sql4,$con);
				$lin4	=	mysql_fetch_array($res4);
				break;
			case '4': //Confirmado
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$Color	  = "";
				break;			
		}
		
		if($lin4[ValorTotal] == "") $lin4[ValorTotal] = 0;
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdProcessoFinanceiro>$lin[IdProcessoFinanceiro]</IdProcessoFinanceiro>";	
		echo 	"<MesReferencia><![CDATA[$lin[MesReferencia]]]></MesReferencia>";
		echo 	"<MesReferenciaTemp><![CDATA[$lin[MesReferenciaTemp]]]></MesReferenciaTemp>";
		echo 	"<MesVencimento><![CDATA[$lin[MesVencimento]]]></MesVencimento>";
		echo 	"<MesVencimentoTemp><![CDATA[$lin[MesVencimentoTemp]]]></MesVencimentoTemp>";
		echo 	"<MenorVencimento><![CDATA[$lin[MenorVencimento]]]></MenorVencimento>";
		echo 	"<DescricaoStatus><![CDATA[$lin2[ValorParametroSistema]]]></DescricaoStatus>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<Filtros><![CDATA[$lin[Filtros]]]></Filtros>";	
		echo 	"<QuantLancamentosFinanceiros><![CDATA[$lin[QuantLancamentosFinanceiros]]]></QuantLancamentosFinanceiros>";	
		echo 	"<QuantContasReceber><![CDATA[$lin[QuantContasReceber]]]></QuantContasReceber>";	
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";	
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ValorTotal><![CDATA[$lin4[ValorTotal]]]></ValorTotal>";
		echo "</reg>";		
	}
	echo "</db>";
?>