<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function listar_postes(){
		global $con;
		
		$IdTipoPoste			= $_GET['IdTipoPoste'];
		$IdPoste				= $_GET['IdPoste'];	
		$local_IdLoja			= $_SESSION["IdLoja"];
		$where = "";
		
		//Info Tipo de Poste
		$sql = "SELECT							
					IdPoste,
					IdTipoPoste,
					NomePoste,
					DescricaoPoste,
					Latitude,
					Longitude					
				FROM
					Poste 
				WHERE
					IdLoja = $local_IdLoja
					AND IdTipoPoste = $IdTipoPoste LIMIT 45";
		$res 	= mysql_query($sql,$con) or die(mysql_error());		
		$Total 	= mysql_num_rows($res);

		//Montar XML
		if(mysql_num_rows($res) > 0){	
			header ("content-type: text/xml");					
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.=	"\n<reg>";	
			while($lin	= mysql_fetch_array($res)){						
				$dados	.=	"\n<IdPoste><![CDATA[$lin[IdPoste]]]></IdPoste>";		
				$dados	.=	"\n<IdTipoPoste><![CDATA[$lin[IdTipoPoste]]]></IdTipoPoste>";		
				$dados	.=	"\n<NomePoste><![CDATA[$lin[NomePoste]]]></NomePoste>";		
				$dados	.=	"\n<DescricaoPoste><![CDATA[$lin[DescricaoPoste]]]></DescricaoPoste>";			
				$dados	.=	"\n<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";		
				$dados	.=	"\n<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";								
				$dados	.=	"\n<Total><![CDATA[$Total]]></Total>";		
			}
			
			if(mysql_num_rows($res) >=1){
				$dados	.=	"\n</reg>";					
			}
		}else{
			$dados = "false";
		}
		
		return $dados;
	}

	echo listar_postes();

?>