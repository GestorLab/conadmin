<?
	$localModulo		=	1;
	$localOperacao		=	23;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_status					= $_POST['filtro_status'];
	$filtro_restringir				= $_POST['filtro_restringir'];
	$filtro_agente					= $_GET['IdAgenteAutorizado'];
	$filtro_limit					= $_POST['filtro_limit'];
	
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
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_agente!=''){
		$filtro_url .= "&IdAgenteAutorizado=$filtro_agente";
		$filtro_sql .=	" and AgenteAutorizado.IdAgenteAutorizado=$filtro_agente";
	}
	
	if($filtro_status!=''){
		$filtro_url .= "&IdStatus=$filtro_status";
		$filtro_sql .=	" and (AgenteAutorizado.IdStatus = '$filtro_status')";
	}
	
	if($filtro_restringir!=''){
		$filtro_url .= "&Restringir=$filtro_restringir";
		$filtro_sql .=	" and (AgenteAutorizado.Restringir = '$filtro_restringir')";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
		header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_agente_autorizado_xsl.php$filtro_url\"?>";
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
					AgenteAutorizado.IdLoja,
					AgenteAutorizado.IdAgenteAutorizado,
					Pessoa.TipoPessoa, 
					Pessoa.RazaoSocial,
					Pessoa.Nome,
					AgenteAutorizado.IdStatus,
					AgenteAutorizado.Restringir
				from 
					AgenteAutorizado,
					Pessoa 
				where
					AgenteAutorizado.IdLoja = $local_IdLoja and
					AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa
					$filtro_sql
				order by
					AgenteAutorizado.IdAgenteAutorizado desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=91 and IdParametroSistema = $lin[IdStatus]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=118 and IdParametroSistema = $lin[Restringir]";
		$res3 = mysql_query($sql3,$con);
		$lin3 = mysql_fetch_array($res3);
		
		switch($lin[IdStatus]){
			case '2': 
				$Color	  =	getParametroSistema(15,2);
				break;
			case '1':
				$Color	  = getParametroSistema(15,3);		
				break;
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";	
		echo 	"<RazaoSocial><![CDATA[$lin[Nome]]]></RazaoSocial>";
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo 	"<Restringir><![CDATA[$lin3[ValorParametroSistema]]]></Restringir>";
		echo 	"<Color><![CDATA[$Color]]></Color>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
