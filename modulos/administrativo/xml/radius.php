<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_radius(){
		
		global $con;
		global $_GET;
		
		
		$IdLoja					= $_SESSION["IdLoja"];
		$IdLicenca				= $_SESSION["IdLicenca"];
		$IdServidor				= $_GET["IdServidor"];
		$Tipo					= $_GET["Tipo"];
		$id						= $_GET["id"];
		$IdGrupo				= $_GET["IdGrupo"];
		$tabela					= "";	
		$where					= "";
		$incluirwhere			= true;
		
		if($Tipo == 'C'){			
			$table	=	"radgroupcheck";		
			$tipo	=	"Check";
		}
		if($Tipo == 'R'){			
			$table	=	"radgroupreply";		
			$tipo	=	"Reply";		
		}
		
		$sql	=	"select ValorCodigoInterno from CodigoInterno where IdLoja = '$IdLoja' and IdGrupoCodigoInterno = 10000 and IdCodigoInterno = '$IdServidor'";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		$aux	=	explode("\n",$lin[ValorCodigoInterno]);
				
		$bd[server]	=	trim($aux[0]); //Host
		$bd[login]	=	trim($aux[1]); //Login
		$bd[senha]	=	trim($aux[2]); //Senha
		$bd[banco]	=	trim($aux[3]); //DB

		$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		if($id != ""){
			if($incluirwhere){
				$where			.=	" where id='$id'";
				$incluirwhere	= false;
			}else{
				$where			.=	" and id='$id'";
			}
		}
		if($IdGrupo != ""){
			if($incluirwhere){
				$where			.=	" where GroupName='$IdGrupo'";
				$incluirwhere 	= false;
			} else{
				$where = " and GroupName='$IdGrupo'";
			}
		}
		
		mysql_select_db($bd[banco],$conRadius);
		
		if($Tipo != ''){	
			$sqlRadius	=	"select 
								id, 
								GroupName, 
								op, 
								Value, 
								Attribute, 
								CONCAT('$tipo') 
							from 
								$table 
								$where";
		}else{
			$sqlRadius	=	"(
				select 
					id, 
					GroupName, 
					op, 
					Value, 
					Attribute, 
					CONCAT('Check') as DescTipo 
				from 
					radgroupcheck 
					$where
			) UNION ALL (
				select 
					id, 
					GroupName, 
					op, 
					Value, 
					Attribute, 
					CONCAT('Reply') as DescTipo 
				from 
					radgroupreply
					$where
			)";
		}
		$resRadius	=	mysql_query($sqlRadius,$conRadius);
		if(@mysql_num_rows($resRadius) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($linRadius	=	@mysql_fetch_array($resRadius)){
			$Tipo	=	substr($linRadius[DescTipo],0,1);
		
			$dados	.=	"\n<id><![CDATA[$linRadius[id]]]></id>";
			$dados	.=	"\n<GroupName><![CDATA[$linRadius[GroupName]]]></GroupName>";
			$dados	.=	"\n<op><![CDATA[$linRadius[op]]]></op>";
			$dados	.=	"\n<Attribute><![CDATA[$linRadius[Attribute]]]></Attribute>";
			$dados	.=	"\n<Value><![CDATA[$linRadius[Value]]]></Value>";
			$dados	.=	"\n<DescTipo><![CDATA[$linRadius[DescTipo]]]></DescTipo>";
			$dados	.=	"\n<Tipo><![CDATA[$Tipo]]></Tipo>";
		}
		if(mysql_num_rows($resRadius) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
		
		mysql_close($conRadius);
	}
	echo get_radius();
?>
