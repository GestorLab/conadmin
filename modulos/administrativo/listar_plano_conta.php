<?
	$localModulo		=	1;
	$localOperacao		=	21;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_tipo			= url_string_xsl($_POST['filtro_tipo'],'url',false);
	$filtro_acao			= $_POST['filtro_acao'];
	$filtro_idacesso		= $_POST['filtro_idacesso'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
		
	if($filtro_descricao!=''){
		$filtro_url .= "&DescricaoPlanoConta=$filtro_descricao";
		$filtro_sql .=	" and DescricaoPlanoConta like '%$filtro_descricao%'";
	}
		
	if($filtro_idacesso!=""){
		$filtro_url .= "&IdAcessoRapido=".$filtro_idacesso;
		$filtro_sql .= " and IdAcessoRapido like '%$filtro_idacesso%'";
	}
	
	if($filtro_tipo!=""){
		$filtro_url .= "&Tipo=".$filtro_tipo;
		$filtro_sql .= " and TipoPlanoConta = '$filtro_tipo'";
	}
	
	if($filtro_acao!=""){
		$filtro_url .= "&AcaoContabil=".$filtro_acao;
		$filtro_sql .= " and AcaoContabil = '$filtro_acao'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	if($filtro_sql != "")
		$filtro_sql = "and DescricaoPlanoConta!=''".$filtro_sql;

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_plano_conta_xsl.php$filtro_url\"?>";
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
						IdLoja,	
						IdPlanoConta,
						DescricaoPlanoConta,
						TipoPlanoConta,
						AcaoContabil,
						IdAcessoRapido
					from 
						PlanoConta 
					where 
						IdLoja = $local_IdLoja 
						$filtro_sql 
					order by
						IdPlanoConta desc
					$Limit";
	$res	=	mysql_query($sql,$con);
	
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=33 and IdParametroSistema = $lin[TipoPlanoConta]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=34 and IdParametroSistema = $lin[AcaoContabil]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdPlanoConta>$lin[IdPlanoConta]</IdPlanoConta>";
		echo 	"<DescricaoPlanoConta><![CDATA[$lin[DescricaoPlanoConta]]]></DescricaoPlanoConta>";
		echo 	"<IdAcessoRapido><![CDATA[$lin[IdAcessoRapido]]]></IdAcessoRapido>";	
		echo 	"<Tipo><![CDATA[$lin2[ValorParametroSistema]]]></Tipo>";	
		echo 	"<AcaoContabil><![CDATA[$lin3[ValorParametroSistema]]]></AcaoContabil>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
