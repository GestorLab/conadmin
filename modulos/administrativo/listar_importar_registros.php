<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja			= $_SESSION['IdLoja']; 
	$local_Login			= $_SESSION['Login'];
	$local_IdLicenca		= $_SESSION["IdLicenca"];	
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro1				= url_string_xsl($_POST['filtro_campo1'],'url',false);
	$filtro2				= url_string_xsl($_POST['filtro_campo2'],'url',false);
	$filtro3				= url_string_xsl($_POST['filtro_campo3'],'url',false);
	$filtro4				= url_string_xsl($_POST['filtro_campo4'],'url',false);
	$filtro5				= url_string_xsl($_POST['filtro_campo5'],'url',false);
	$filtro_limit			= $_POST['filtro_limit'];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro1 != ''){	$filtro_url .= "&Campo1=".$filtro1;	}
	if($filtro2 != ''){	$filtro_url .= "&Campo2=".$filtro2;	}
	if($filtro3 != ''){	$filtro_url .= "&Campo3=".$filtro3;	}
	if($filtro4 != ''){	$filtro_url .= "&Campo4=".$filtro4;	}
	if($filtro5 != ''){	$filtro_url .= "&Campo5=".$filtro5;	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_importar_registros_xsl.php$filtro_url\"?>";
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
	
	
	$sql	=	"select IdParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 111 and IdParametroSistema = 2";
	$res	=	mysql_query($sql,$con);
	$lin	=	mysql_fetch_array($res);
		
	$aux		=	explode("\n",$lin[ValorParametroSistema]);
			
	$bd[server]	=	trim($aux[0]); //Host
	$bd[login]	=	trim($aux[1]); //Login
	$bd[senha]	=	trim($aux[2]); //Senha
	$bd[banco]	=	trim($aux[3]); //DB
	
	$conBanco	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
			
	mysql_select_db($bd[banco],$conBanco);
	
	$sqlBanco	=	getParametroSistema(111,8)." ".$Limit;
	$sqlBanco	=	str_replace('$filtro1',$filtro1,$sqlBanco);
	$sqlBanco	=	str_replace('$filtro2',$filtro2,$sqlBanco);
	$sqlBanco	=	str_replace('$filtro3',$filtro3,$sqlBanco);
	$sqlBanco	=	str_replace('$filtro4',$filtro4,$sqlBanco);
	$sqlBanco	=	str_replace('$filtro5',$filtro5,$sqlBanco);
	$resBanco	=	mysql_query($sqlBanco,$conBanco);
	
	$coluna		=	explode("\n",getParametroSistema(111,9));
	$qtd_col	=	count($coluna);
	
	for($i=0;$i<$qtd_col;$i++){
		$campo[$i]		=	mysql_field_name($resBanco, $i);
	}
	
	$link		=	getParametroSistema(111,10);	
	
	while($linBanco	=	@mysql_fetch_array($resBanco)){
		echo "<reg>";	
		
		for($i=0;$i<$qtd_col;$i++){
			$nameCol	=	'Campo'.($i+1);
			$valCol		=	$linBanco[$campo[$i]];
			
			echo 	"<$nameCol><![CDATA[$valCol]]></$nameCol>";	
		}
		
		echo "</reg>";	
			
	}
		
	mysql_close($conBanco);

	echo "</db>";
?>
