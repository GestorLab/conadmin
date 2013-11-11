<?
	$localModulo		= 1;
	$localOperacao		= 17;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_valor					= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_campo					= url_string_xsl($_POST['filtro_campo'],'url',false);
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_conta_receber_agrupador	= $_GET['IdContaReceberAgrupador'];
	$filtro_url	 					= "";
	$filtro_sql  					= "";
	
	LimitVisualizacao("listar");

	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url .= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberAgrupado.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_valor;
	
		switch($filtro_campo){
			case "DataLancamento":
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and substring(ContaReceberAgrupado.DataCriacao,1,10) = '$filtro_valor'";
				break;
			case "DataVencimento":
				if($filtro_valor != ""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberAgrupadoParcela.DataVencimento = '$filtro_valor'";
				} else{
					$filtro_sql .= " and ContaReceberAgrupadoParcela.DataVencimento is NULL";
				}
				
				$filtro_sql .= " and ContaReceberAgrupadoParcela.NumParcelaContaReceberAgrupado = '1'";
				break;
			case "MesLancamento":
				if($filtro_valor != ""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberAgrupado.DataCriacao,1,7) = '$filtro_valor'";
				} else{
					$filtro_sql .= " and ContaReceberAgrupado.DataCriacao is NULL";
				}
				break;
			case "MesVencimento":
				if($filtro_valor != ""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberAgrupadoParcela.DataVencimento,1,7) = '$filtro_valor'";
				} else{
					$filtro_sql .= " and ContaReceberAgrupadoParcela.DataVencimento is NULL";
				}
				
				$filtro_sql .= " and ContaReceberAgrupadoParcela.NumParcelaContaReceberAgrupado = '1'";
				break;
			case "IdProcessoFinanceiro":
				$filtro_sql .= " and (
					ContaReceberAgrupadoParcela.IdContaReceber in (
						select
							LancamentoFinanceiroContaReceber.IdContaReceber
						from
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber
						where
							LancamentoFinanceiro.IdLoja = '$local_IdLoja' and
							LancamentoFinanceiro.IdProcessoFinanceiro = '$filtro_valor' and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro 
					) or
					ContaReceberAgrupado.IdContaReceberAgrupador in (
						select 
							ContaReceberAgrupadoItem.IdContaReceberAgrupador
						from 
							ContaReceberAgrupadoItem,
							LancamentoFinanceiroContaReceber,
							LancamentoFinanceiro
						where
							ContaReceberAgrupadoItem.IdLoja = '$local_IdLoja' and
							ContaReceberAgrupadoItem.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
							CotnaReceberAgrupadoItem.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and 
							LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
							LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
							LancamentoFinanceiro.IdProcessoFinanceiro = '$filtro_valor' 							
					)
				)";
				break;
			case "NumeroDocumento":
				$filtro_sql .= " and (
					ContaReceberAgrupadoParcela.IdContaReceber in (
						select 
							ContaReceberDados.IdContaReceber
						from
							ContaReceberDados
						where
							ContaReceberDados.IdLoja = '$local_IdLoja' and
							ContaReceberDados.NumeroDocumento = '$filtro_valor'
					) or
					ContaReceberAgrupado.IdContaReceberAgrupador in (
						select
							ContaReceberAgrupadoItem.IdContaReceberAgrupador
						from
							ContaReceberAgrupadoItem,
							ContaReceberDados
						where 
							ContaReceberAgrupadoItem.IdLoja = '$local_IdLoja' and 
							ContaReceberAgrupadoItem.IdLoja = ContaReceberDados.IdLoja and 
							ContaReceberAgrupadoItem.IdContaReceber = ContaReceberDados.IdContaReceber and
							ContaReceberDados.NumeroDocumento = '$filtro_valor'
					)
				)";
				break;
			case "NumeroNF":
				$filtro_sql .= " and (
					ContareceberAgrupadoParcela.IdContaReceber in (
						select 
							ContaReceberDados.IdContaReceber 
						from 
							ContaReceberDados
						where
							ContaReceberDados.IdLoja = '$local_IdLoja' and
							ContaReceberDados.NumeroNF = '$filtro_valor'
					) or
					ContaReceberAgrupado.IdContaReceberAgrupador in (
						select
							ContaReceberAgrupadoItem.IdContaReceberAgrupador
						from 
							ContaReceberAgrupadoItem,
							ContaReceberDados
						where
							ContaReceberAgrupadoItem.IdLoja = '$local_IdLoja' and
							ContaReceberAgrupadoItem.IdLoja = ContaReceberDados.IdLoja and
							ContaReceberAgrupadoItem.IdContaReceber = ContaReceberDados.IdContaReceber and 
							ContaReceberDados.NumeroNF = '$filtro_valor'
					)
				)";
				break;
			case "IdContaReceber":
				$filtro_sql .= " and (
					ContaReceberAgrupadoParcela.IdContaReceber = '$filtro_valor' or
					ContaReceberAgrupado.IdContaReceberAgrupador in (
						select 
							ContaReceberAgrupadoItem.IdContaReceberAgrupador
						from
							ContaReceberAgrupadoItem
						where
							ContaReceberAgrupadoItem.IdLoja = '$local_IdLoja' and
							ContaReceberAgrupadoItem.IdContaReceber = '$filtro_valor'
					)
				)";
				break;
			case "IdLancamentoFinanceiro":
				$filtro_sql .= " and (
					ContaReceberAgrupadoParcela.IdContaReceber in (
						select
							LancamentoFinanceiroContaReceber.IdContaReceber
						from 
							LancamentoFinanceiroContaReceber
						where
							LancamentoFinanceiroContaReceber.IdLoja = '$local_IdLoja' and 
							LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = '$filtro_valor'
					) or
					ContaReceberAgrupadoParcela.IdContaReceberAgrupador in (
						select
							ContaReceberAgrupadoItem.IdContaReceberAgrupador
						from
							LancamentoFinanceiroContaReceber,
							ContaReceberAgrupadoItem
						where
							LancamentoFinanceiroContaReceber.IdLoja = '$local_IdLoja' and 
							LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = '$filtro_valor' and
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceberAgrupadoItem.IdLoja and 
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberAgrupadoItem.IdContaReceber
					)
				)";
				break;
		}
	} else{
		$filtro_valor = "";
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente = "select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente = @mysql_query($sqlAgente,$con);
		
		while($linAgente = @mysql_fetch_array($resAgente)){
			$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente = "select 
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
		$resAgente = @mysql_query($sqlAgente,$con);
		
		while($linAgente = @mysql_fetch_array($resAgente)){
			$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_conta_receber_agrupador != "") {
		$filtro_url .= "&IdContaReceberAgrupador=".$filtro_conta_receber_agrupador;
		$filtro_sql .= " and ContaReceberAgrupado.IdContaReceberAgrupador = ".$filtro_conta_receber_agrupador;
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
	
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_agrupar_conta_receber_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro != "s"){
		if($filtro_limit == ""){
			$Limit = " limit 0,".getCodigoInterno(7,5);
		} else{
			$Limit = " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select 
				ContaReceberAgrupado.IdContaReceberAgrupador,
				ContaReceberAgrupado.IdLocalCobranca,
				ContaReceberAgrupado.IdStatus,
				ContaReceberAgrupado.ValorTotal,
				ContaReceberAgrupado.DataCriacao DataLancamento,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				Pessoa.Nome
			from
				ContaReceberAgrupado,
				ContaReceberAgrupadoParcela,
				LocalCobranca,
				Pessoa 
			where
				ContaReceberAgrupado.IdLoja = '$local_IdLoja' and
				ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoParcela.IdLoja and 
				ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoParcela.IdContaReceberAgrupador and
				ContaReceberAgrupado.IdLoja = LocalCobranca.IdLoja and
				ContaReceberAgrupado.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
				ContaReceberAgrupado.IdPessoa = Pessoa.IdPessoa 
				$filtro_sql
			group by
				ContaReceberAgrupado.IdContaReceberAgrupador
			order by
				ContaReceberAgrupado.IdContaReceberAgrupador desc,
				ContaReceberAgrupadoParcela.DataVencimento asc
			$Limit";
	$res = mysql_query($sql,$con);
	
	while($lin = mysql_fetch_array($res)){
		$sql_0 = "select 
					count(*) QTDParcela 
				from
					ContaReceberAgrupadoParcela, 
					ContaReceber
				where 
					ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' and 
					ContaReceberAgrupadoParcela.IdContaREceberAgrupador = '".$lin["IdContaReceberAgrupador"]."' and 
					ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja and 
					ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber";
		$res_0 = mysql_query($sql_0, $con);
		$lin_0 = mysql_fetch_array($res_0);
		
		$sql_1 = "select 
					count(*) QTDParcelaAguardandoPagamento 
				from
					ContaReceberAgrupadoParcela, 
					ContaReceber
				where 
					ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' and 
					ContaReceberAgrupadoParcela.IdContaREceberAgrupador = '".$lin["IdContaReceberAgrupador"]."' and 
					ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja and 
					ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.IdStatus = '1'";
		$res_1 = mysql_query($sql_1, $con);
		$lin_1 = mysql_fetch_array($res_1);
		
		$sql_2 = "select 
					count(*) QTDParcelaQuitado 
				from
					ContaReceberAgrupadoParcela, 
					ContaReceber
				where 
					ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' and 
					ContaReceberAgrupadoParcela.IdContaREceberAgrupador = '".$lin["IdContaReceberAgrupador"]."' and 
					ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja and 
					ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.IdStatus = '2'";
		$res_2 = mysql_query($sql_2, $con);
		$lin_2 = mysql_fetch_array($res_2);
		
		$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
		$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
		
		if($lin[ValorTotal] == ""){				
			$lin[ValorTotal] = 0;		
		}
		
		if($lin[TipoPessoa] == "1"){
			$lin[Nome] = $lin[getCodigoInterno(3, 24)];	
		}
		
		echo "<reg>";	
		echo 	"<IdContaReceberAgrupador>$lin[IdContaReceberAgrupador]</IdContaReceberAgrupador>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
		echo 	"<ValorTotal>$lin[ValorTotal]</ValorTotal>";
		echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
		echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
		echo 	"<QTDParcela><![CDATA[$lin_0[QTDParcela]]]></QTDParcela>";
		echo 	"<QTDParcelaAguardandoPagamento><![CDATA[$lin_1[QTDParcelaAguardandoPagamento]]]></QTDParcelaAguardandoPagamento>";
		echo 	"<QTDParcelaQuitado><![CDATA[$lin_2[QTDParcelaQuitado]]]></QTDParcelaQuitado>";
		echo "</reg>";
	}
	
	echo "</db>";
?>