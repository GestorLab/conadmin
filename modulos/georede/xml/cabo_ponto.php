<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Coordenadas	= formataCoordenadas($_GET['Coordenadas'],'');
	
	if($Coordenadas != ""){
		$Coordenadas		= explode(",",$Coordenadas);
		$sqlPoste = "SELECT
						IdPoste,
						IdTipoPoste
					FROM 
						Poste
					WHERE 
						Latitude LIKE '%".$Coordenadas[0]."%'";	
		$resPoste = mysql_query($sqlPoste,$con) or die(mysql_error());
		
		if($linPoste 	= mysql_fetch_array($resPoste)){		
			$IdCabo 	= verificaPontoCabo($linPoste[IdPoste]);
			if($IdCabo != ""){
				header ("content-type: text/xml");		
				echo	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				echo	"\n<reg>";					
				echo	"\n<CaboAtual><![CDATA[$IdCabo]]></CaboAtual>";		
				echo	"\n</reg>";			
			}else{
				echo 'false';
			}
		}		
	}

?>