<?
	$localModulo		=	1;
	$localOperacao		=	22;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_local_receb					= $_POST['filtro_local_receb'];
	$filtro_id_status					= $_POST['filtro_id_status'];
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_data_inicio					= $_POST['filtro_data_inicio'];
	$filtro_data_termino				= $_POST['filtro_data_termino'];
	$filtro_nome_arquivo				= url_string_xsl($_POST['filtro_nome_arquivo'],'url',false);
	$filtro_n_sequencial				= $_POST['filtro_n_sequencial'];
	$filtro_data_sequencial_inicio		= $_POST['filtro_data_sequencial_inicio'];
	$filtro_data_sequencial_final		= $_POST['filtro_data_sequencial_final'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_arquivo_retorno				= $_GET['IdArquivoRetorno'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_local_receb!=''){
		$filtro_url .= "&IdLocalCobranca=$filtro_local_receb";
		$filtro_sql .=	" and (LocalCobranca.IdLocalCobranca = '$filtro_local_receb')";
	}
	
	if($filtro_arquivo_retorno!=""){
		$filtro_url .= "&IdArquivoRetorno=".$filtro_arquivo_retorno;
		$filtro_sql .= " and IdArquivoRetorno = $filtro_arquivo_retorno";
	}
		
	if($filtro_id_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and ArquivoRetorno.IdStatus = $filtro_id_status";
	}
	
	if($filtro_nome_arquivo!=""){
		$filtro_url .= "&NomeArquivo=".$filtro_nome_arquivo;
		$filtro_sql .= " and NomeArquivo like '%$filtro_nome_arquivo%'";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&DataInicio=$filtro_data_inicio";
		$filtro_url .= "&DataTermino=$filtro_data_termino";
		switch($filtro_campo){
			case 'DataCadastro':
				if($filtro_data_inicio != ''){
					$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (subString(ArquivoRetorno.DataCriacao,1,10) >= '$filtro_data_inicio')";
				}
				if($filtro_data_termino != ''){
					$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (subString(ArquivoRetorno.DataCriacao,1,10) <= '$filtro_data_termino')";
				}
				break;
			case 'DataRetorno':
				if($filtro_data_inicio != ''){
					$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (ArquivoRetorno.DataRetorno >= '$filtro_data_inicio')";
				}
				if($filtro_data_termino != ''){
					$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (ArquivoRetorno.DataRetorno <= '$filtro_data_termino')";
				}
				break;
			case 'DataProcessamento':
				if($filtro_data_inicio != ''){
					$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (subString(ArquivoRetorno.DataProcessamento,1,10) >= '$filtro_data_inicio')";
				}
				if($filtro_data_termino != ''){
					$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
					
					$filtro_sql .=	" and (subString(ArquivoRetorno.DataProcessamento,1,10) <= '$filtro_data_termino')";
				}
				break;
		}		
	}else{
		$filtro_data_inicio		= "";
		$filtro_data_termino	= "";
	}
	
	if($filtro_n_sequencial!=""){
		$filtro_url .= "&NSequencial=".$filtro_n_sequencial;
		$filtro_sql .= " and ArquivoRetorno.NumSeqArquivo = $filtro_n_sequencial";
	}
	
	if($filtro_data_sequencial_inicio!=""){
		$filtro_url .= "&SeqDataIncio=".$filtro_data_sequencial_inicio;
		$filtro_data_sequencial_inicio = dataConv($filtro_data_sequencial_inicio,'d/m/Y','Y-m-d');
		
		$filtro_sql .= " and substr(ArquivoRetorno.DataCriacao,1,10) >= '$filtro_data_sequencial_inicio'";
	}
	if($filtro_data_sequencial_final!=""){
		$filtro_url .= "&SeqDataFinal=".$filtro_data_sequencial_final;
		$filtro_data_sequencial_final = dataConv($filtro_data_sequencial_final,'d/m/Y','Y-m-d');
		
		$filtro_sql .= " and substr(ArquivoRetorno.DataCriacao,1,10) <= '$filtro_data_sequencial_final'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_arquivo_retorno_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql = "select
				ArquivoRetorno.IdLoja,
				ArquivoRetorno.IdArquivoRetorno,
				ArquivoRetorno.IdLocalCobranca IdLocalRecebimento,
				substr(LocalCobranca.AbreviacaoNomeLocalCobranca,1,30) AbreviacaoNomeLocalRecebimento,
				ArquivoRetorno.QtdRegistro,
				ArquivoRetorno.ValorTotal,
				ArquivoRetorno.IdStatus,
				ArquivoRetorno.DataRetorno,
				ArquivoRetorno.NomeArquivo,
				ArquivoRetorno.DataCriacao,
				ArquivoRetorno.DataProcessamento,
				ArquivoRetorno.NumSeqArquivo,
				ParametroSistema.ValorParametroSistema Status
			from
				Loja,
				ArquivoRetorno,
				LocalCobranca,
				ParametroSistema
			where
				ArquivoRetorno.IdLoja = $local_IdLoja and
				ArquivoRetorno.IdLoja = Loja.IdLoja and
				ArquivoRetorno.IdLoja = LocalCobranca.IdLoja and
				ArquivoRetorno.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				ParametroSistema.IdGrupoParametroSistema = 195 and 
				ParametroSistema.IdParametroSistema = ArquivoRetorno.IdStatus
				$filtro_sql
			order by 
				ArquivoRetorno.IdArquivoRetorno desc
				$Limit";
	$res = mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataRetornoTemp]		= dataConv($lin[DataRetorno],'Y-m-d','d/m/Y');
		$lin[DataCadastroTemp] 		= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		$lin[DataProcessamentoTemp]	= dataConv($lin[DataProcessamento],"Y-m-d","d/m/Y");
		
		$lin[DataRetorno] 			= dataConv($lin[DataRetorno],"Y-m-d","Ymd");
		$lin[DataCadastro] 			= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		$lin[DataProcessamento] 	= dataConv($lin[DataProcessamento],"Y-m-d","Ymd");
		
		if($lin[ValorTotal] == '')		$lin[ValorTotal]  = 0; 
		if($lin[QtdRegistro] == '')		$lin[QtdRegistro] = 0; 
		
		$sqlRecebimento = "SELECT	
								ContaReceberRecebimento.IdArquivoRetorno,
								SUM(ContaReceberRecebimento.ValorRecebido) ValorRecebido,
								ContaReceberRecebimento.IdLocalCobranca
							FROM
								ContaReceberRecebimento
							WHERE
								ContaReceberRecebimento.IdLoja = $local_IdLoja AND
								ContaReceberRecebimento.IdArquivoRetorno = '$lin[IdArquivoRetorno]' AND
								ContaReceberRecebimento.IdLocalCobranca  = '$lin[IdLocalRecebimento]'";
								
		$resRecebimento = mysql_query($sqlRecebimento,$con);
		$lin2 = mysql_fetch_array($resRecebimento);
		
		if($lin2[ValorRecebido] == "" || $lin2[ValorRecebido] == "NULL"){
			$lin2[ValorRecebido] = 0;
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdArquivoRetorno>$lin[IdArquivoRetorno]</IdArquivoRetorno>";	
		echo 	"<IdLocalRecebimento>$lin[IdLocalRecebimento]</IdLocalRecebimento>";
		echo 	"<QtdRegistro>$lin[QtdRegistro]</QtdRegistro>";
		echo 	"<ValorTotal>$lin[ValorTotal]</ValorTotal>";	
		echo 	"<AbreviacaoNomeLocalRecebimento><![CDATA[$lin[AbreviacaoNomeLocalRecebimento]]]></AbreviacaoNomeLocalRecebimento>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo 	"<NomeArquivo><![CDATA[$lin[NomeArquivo]]]></NomeArquivo>";	
		echo 	"<DataRetorno><![CDATA[$lin[DataRetorno]]]></DataRetorno>";
		echo 	"<DataRetornoTemp><![CDATA[$lin[DataRetornoTemp]]]></DataRetornoTemp>";
		echo 	"<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
		echo 	"<DataProcessamentoTemp><![CDATA[$lin[DataProcessamentoTemp]]]></DataProcessamentoTemp>";
		echo 	"<DataCadastro><![CDATA[$lin[DataCadastro]]]></DataCadastro>";
		echo	"<NumSeqArquivo><![CDATA[$lin[NumSeqArquivo]]]></NumSeqArquivo>";
		echo 	"<DataCadastroTemp><![CDATA[$lin[DataCadastroTemp]]]></DataCadastroTemp>";
		echo    "<ValorRecebido>$lin2[ValorRecebido]</ValorRecebido>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>