<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja						= $_SESSION['IdLoja'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_descricao_parametro_servico	= $_POST['filtro_descricao_parametro_servico'];
	$filtro_descricao_servico			= $_POST['filtro_descricao_servico'];
	$filtro_valor_default				= $_POST['filtro_valor_default'];
	$filtro_idstatus					= $_POST['filtro_idstatus'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_obrigatorio					= $_POST['filtro_obrigatorio'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	
	if($_GET['IdServico'] != ''){
		$filtro_servico					= $_GET['IdServico'];
	}
	if($_POST['IdServico'] != ''){
		$filtro_servico					= $_POST['IdServico'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	//esta comparação impedia a pesquisa ao clicar no menu horizontal Parâmetro[+]
	/*if($filtro_servico == ''){ 
		$filtro_sql .=	" and Servico.IdServico = '0'";
	}*/
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_servico!=''){
		$filtro_url .= "&IdServico=$filtro_servico";
		$filtro_sql .=	" and Servico.IdServico = $filtro_servico";
	}
	
	if($filtro_id_servico!=''){
		$filtro_url .= "&IdServico=$filtro_id_servico";
		$filtro_sql .=	" and Servico.IdServico = $filtro_id_servico";
	}
	
	if($filtro_obrigatorio!=''){
		$filtro_url .= "&Obrigatorio=$filtro_obrigatorio";
		$filtro_sql .=	" and ServicoParametro.Obrigatorio = '$filtro_obrigatorio'";
	}
		
	if($filtro_descricao_parametro_servico!=''){
		$filtro_url .= "&DescricaoParametroServico=$filtro_descricao_parametro_servico";
		$filtro_sql .=	" and ServicoParametro.DescricaoParametroServico like '%$filtro_descricao_parametro_servico%'";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_valor_default!=''){
		$filtro_url .= "&ValorDefault=".$filtro_valor_default;
		$filtro_sql .= " and ServicoParametro.ValorDefault like '%$filtro_valor_default%'";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and ServicoParametro.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_parametro_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select
					Servico.IdLoja,
				    Servico.IdServico,
					substr(Servico.DescricaoServico,1,30) DescricaoServico,
					ServicoParametro.IdParametroServico,
				    ServicoParametro.DescricaoParametroServico,
					substr(ServicoParametro.ValorDefault,1,30) ValorDefault,
					ServicoParametro.Obrigatorio,
					ServicoParametro.Editavel,
					ServicoParametro.Calculavel,
					ServicoParametro.IdStatus,
					ServicoParametro.ParametroDemonstrativo,
					ServicoParametro.CalculavelOpcoes
				from
					Servico,
					ServicoParametro
				where
				    Servico.IdLoja = $local_IdLoja and
				    Servico.IdLoja = ServicoParametro.IdLoja and
				    Servico.IdServico = ServicoParametro.IdServico
					$filtro_sql
				order by
					ServicoParametro.IdParametroServico desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql2 = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=5 and IdCodigoInterno = $lin[Obrigatorio]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 and IdParametroSistema=$lin[Editavel]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=79 and IdParametroSistema=$lin[IdStatus]";
		$res4 = @mysql_query($sql4,$con);
		$lin4 = @mysql_fetch_array($res4);
		
		$sql5 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=45 and IdParametroSistema=$lin[Calculavel]";
		$res5 = @mysql_query($sql5,$con);
		$lin5 = @mysql_fetch_array($res5);
		
		$sql6 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=54 and IdParametroSistema=$lin[ParametroDemonstrativo]";
		$res6 = @mysql_query($sql6,$con);
		$lin6 = @mysql_fetch_array($res6);
		
		$sql7 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=90 and IdParametroSistema=$lin[CalculavelOpcoes]";
		$res7 = @mysql_query($sql7,$con);
		$lin7 = @mysql_fetch_array($res7);
		
		if($lin[Valor] != ""){
			$lin[ValorTemp] = str_replace(".", ",", $lin[Valor]);
		}else{
			$lin[Valor] = 0;
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<IdParametroServico>$lin[IdParametroServico]</IdParametroServico>";
		echo 	"<DescricaoParametroServico><![CDATA[$lin[DescricaoParametroServico]]]></DescricaoParametroServico>";
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<Obrigatorio><![CDATA[$lin2[ValorCodigoInterno]]]></Obrigatorio>";
		echo 	"<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
		echo 	"<Editavel><![CDATA[$lin3[ValorParametroSistema]]]></Editavel>";
		echo 	"<Calculavel><![CDATA[$lin5[ValorParametroSistema]]]></Calculavel>";
		echo 	"<Status><![CDATA[$lin4[ValorParametroSistema]]]></Status>";
		echo 	"<ParametroDemonstrativo><![CDATA[$lin6[ValorParametroSistema]]]></ParametroDemonstrativo>";
		echo 	"<CalculavelOpcoes><![CDATA[$lin7[ValorParametroSistema]]]></CalculavelOpcoes>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
