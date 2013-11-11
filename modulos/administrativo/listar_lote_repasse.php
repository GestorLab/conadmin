<?
	$localModulo		=	1;
	$localOperacao		=	42;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION['Login'];
	$local_IdLoja			= $_SESSION['IdLoja'];
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_nome			= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_status			= $_POST['filtro_status'];
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_pessoa			= $_GET['IdPessoa'];
	$filtro_repasse			= $_GET['IdLoteRepasse'];
	
	if($filtro_pessoa == ''){
		$filtro_pessoa		= $_POST['IdPessoa'];
	}
	
	if($filtro_repasse == ''){
		$filtro_repasse		= $_POST['IdLoteRepasse'];
	}
	
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
		
	if($filtro_nome!=""){
		$filtro_url .= "&Nome=".$filtro_nome;
		$filtro_sql .= " and (Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	if($filtro_repasse!=""){
		$filtro_url	.= "&IdLoteRepasse=".$filtro_repasse;
		$filtro_sql	.= " and IdLoteRepasse =".$filtro_repasse;
	}
	if($filtro_pessoa!=""){
		$filtro_url	.= "&IdPessoa=".$filtro_pessoa;
		$filtro_sql	.= " and LoteRepasseTerceiro.IdTerceiro =".$filtro_pessoa;
	}
	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and LoteRepasseTerceiro.IdStatus =".$filtro_status;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_lote_repasse_xsl.php$filtro_url\"?>";
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
	
	echo $sql	=	"select
				      	IdLoteRepasse, 
						Pessoa.TipoPessoa,
						substr(Pessoa.Nome,1,30) as Nome,
						substr(Pessoa.RazaoSocial,1,30) as RazaoSocial,
						ValorTotalItens,
						ValorTotalRepasse,
						IdStatus,
						DataConfirmacao,
						Filtro_MesReferencia
					from 
						LoteRepasseTerceiro,
						Pessoa
					where
						LoteRepasseTerceiro.IdLoja = $local_IdLoja and
						LoteRepasseTerceiro.IdTerceiro = Pessoa.IdPessoa
						$filtro_sql 
					order by
						IdLoteRepasse desc 
					$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=57 and IdParametroSistema = $lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		if($lin[ValorTotalItens]==''){		$lin[ValorTotalItens]   = 0;	}
		if($lin[ValorTotalRepasse]==''){	$lin[ValorTotalRepasse] = 0;	}

		if($lin[IdStatus]==3){
			$Img	=	'../../img/estrutura_sistema/ico_del_c.gif';
		}else{
			$Img	=	'../../img/estrutura_sistema/ico_del.gif';
		}

		switch($lin[IdStatus]){
			case '2':	
				$Color	  = getParametroSistema(15,2);
				break;
			case '3':
				$Color	  = getParametroSistema(15,3);
				break;
			default:
				$Color	  = "";
				break;
		}

		$lin[DataConfirmacaoTemp]	=	dataConv($lin[DataConfirmacao],'Y-m-d','d/m/Y');
		$lin[DataConfirmacao]		=	dataConv($lin[DataConfirmacao],'Y-m-d','Ymd');
		
		
		$lin[Filtro_MesReferenciaTemp]	=	$lin[Filtro_MesReferencia];
		
		$mesref = explode("/",$lin[Filtro_MesReferencia]);
		$lin[Filtro_MesReferencia]		=	$mesref[1].$mesref[0];//AnoMes
		
		$lin[IdLoteRepasseTemp]	=	(int)$lin[IdLoteRepasse];
		
		//$lin[ValorTotalItens] = str_replace(".","",$lin[ValorTotalItens]);
		//$lin[ValorTotalItens] = formata_double($lin[ValorTotalItens],2);
		//echo gettype($lin[ValorTotalItens])."<br/>";
		
		echo "<reg>";			
		echo 	"<IdLoteRepasse>$lin[IdLoteRepasse]</IdLoteRepasse>";
		echo 	"<IdLoteRepasseTemp>$lin[IdLoteRepasseTemp]</IdLoteRepasseTemp>";
		echo 	"<ValorTotalItens><![CDATA[$lin[ValorTotalItens]]]></ValorTotalItens>";
		echo 	"<ValorTotalRepasse><![CDATA[$lin[ValorTotalRepasse]]]></ValorTotalRepasse>";
		echo	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo	"<DataConfirmacaoTemp><![CDATA[$lin[DataConfirmacaoTemp]]]></DataConfirmacaoTemp>";
		echo	"<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";
		echo	"<Filtro_MesReferenciaTemp><![CDATA[$lin[Filtro_MesReferenciaTemp]]]></Filtro_MesReferenciaTemp>";
		echo	"<Filtro_MesReferencia><![CDATA[$lin[Filtro_MesReferencia]]]></Filtro_MesReferencia>";
		echo	"<Img><![CDATA[$Img]]></Img>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>