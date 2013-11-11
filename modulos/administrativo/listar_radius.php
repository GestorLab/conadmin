<?
	$localModulo		=	1;
	$localOperacao		=	10000;
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
	$filtro_servidor		= $_POST['filtro_servidor'];
	$filtro_grupo			= $_POST['filtro_grupo'];
	$filtro_tipo			= $_POST['filtro_tipo'];
	$filtro_atributo		= $_POST['filtro_atributo'];
	$filtro_valor			= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_id				= $_POST['id'];
	$servidor				= "";
	$where					= true;
	
	if($_GET['id']!=''){
		$filtro_id	= $_GET['id'];
	}
	if($_GET['Tipo']!=''){
		$filtro_tipo	= $_GET['Tipo'];
	}
	if($_GET['IdServidor']!=''){
		$filtro_servidor	= $_GET['IdServidor'];
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
		
	if($filtro_servidor!=""){
		$filtro_url .= "&IdServidor=".$filtro_servidor;
	}
	if($filtro_grupo != ''){
		$filtro_url .= "&IdGrupo=".$filtro_grupo;
		if($where){
			$filtro_sql .= " where GroupName = '$filtro_grupo'";
			$where  = false;
		}else{
			$filtro_sql .= " and GroupName = '$filtro_grupo'";
		}
	}
	if($filtro_tipo != ''){
		$filtro_url .= "&Tipo=".$filtro_tipo;
	}
	if($filtro_atributo!=""){
		$filtro_url	.= "&Atributo=".$filtro_atributo;
		if($where){
			$filtro_sql	.= " where Attribute ='$filtro_atributo'";
			$where	= false;
		}else{
			$filtro_sql	.= " and Attribute ='$filtro_atributo'";
		}
	}
	if($filtro_id!=""){
		$filtro_url	.= "&id=".$filtro_id;
		if($where){
			$filtro_sql	.= " where id ='$filtro_id'";
			$where = false;
		}else{
			$filtro_sql	.= " and id ='$filtro_id'";
		}
	}
	if($filtro_valor!=""){
		$filtro_url	.= "&Valor=".$filtro_valor;
		if($where){
			$filtro_sql	.= " where Value like '%$filtro_valor%'";
			$where = false;
		}else{
			$filtro_sql	.= " and Value like '%$filtro_valor%'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_radius_xsl.php$filtro_url\"?>";
	echo "<db>";

	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= $filtro_limit;
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= getCodigoInterno(7,5);
		}else{
			$Limit 	= $filtro_limit;
		}
	}
	
	
	if($filtro_servidor!=""){	$servidor	=	" and IdCodigoInterno = '$filtro_servidor'";	}
	
	$cont	=	0;
	$sql	=	"
				select 
					IdCodigoInterno,
					DescricaoCodigoInterno,
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdLoja = '$local_IdLoja' and 
					IdGrupoCodigoInterno = 10000 and 
					IdCodigoInterno < 20 $servidor
	";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		$IdServidor	=	$lin[IdCodigoInterno];	
		$Servidor	=	$lin[DescricaoCodigoInterno];	
		$aux		=	explode("\n",$lin[ValorCodigoInterno]);
				
		$bd[server]	=	trim($aux[0]); //Host
		$bd[login]	=	trim($aux[1]); //Login
		$bd[senha]	=	trim($aux[2]); //Senha
		$bd[banco]	=	trim($aux[3]); //DB
	
		$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
			
		mysql_select_db($bd[banco],$conRadius);
		
		if($filtro_tipo == 'C'){
			$sqlRadius	=	"select 
								id, 
								GroupName, 
								op, 
								Value, 
								Attribute, 
								CONCAT('Check') as DescTipo 
							from 
								radgroupcheck
								$filtro_sql
							order by
								id desc";
		}
		else if($filtro_tipo == 'R'){
			$sqlRadius	=	"select 
								id, 
								GroupName, 
								op, 
								Value, 
								Attribute, 
								CONCAT('Reply') as DescTipo 
							from 
								radgroupreply 
								$filtro_sql
							order by
								id desc";
		}
		else{
			$sqlRadius	=	"select 
								id, 
								GroupName, 
								op, 
								Value, 
								Attribute, 
								CONCAT('Check') as DescTipo 
							from 
								radgroupcheck 
								$filtro_sql 
							UNION ALL 
							select 
								id, 
								GroupName, 
								op, 
								Value, 
								Attribute, 
								CONCAT('Reply') as DescTipo 
							from 
								radgroupreply 
								$filtro_sql
							order by
								id desc";
		}

		$resRadius	=	mysql_query($sqlRadius,$conRadius);
		echo mysql_error();
		while($linRadius	=	@mysql_fetch_array($resRadius)){
			
			$cont++;
			$Tipo	=	substr($linRadius[DescTipo],0,1);
			
			if($cont <= $Limit || $Limit == ""){
				echo "<reg>";	
				echo 	"<id><![CDATA[$linRadius[id]]]></id>";	
				echo 	"<IdServidor><![CDATA[$IdServidor]]></IdServidor>";
				echo 	"<Servidor><![CDATA[$Servidor]]></Servidor>";
				echo 	"<GroupName><![CDATA[$linRadius[GroupName]]]></GroupName>";
				echo 	"<op><![CDATA[$linRadius[op]]]></op>";
				echo 	"<Attribute><![CDATA[$linRadius[Attribute]]]></Attribute>";
				echo 	"<Value><![CDATA[$linRadius[Value]]]></Value>";
				echo 	"<Tipo><![CDATA[$Tipo]]></Tipo>";
				echo 	"<DescTipo><![CDATA[$linRadius[DescTipo]]]></DescTipo>";
				echo "</reg>";	
				
			} else{
				break;
			}	
		}
		
		mysql_close($conRadius);
	}
	echo "</db>";
?>
