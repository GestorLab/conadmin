<?
	
	$localModulo		=	1;
	$localOperacao		=	65;	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/conecta_cntsistemas.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
		function get_marcador(){
			global $con;
			global $conCNT;
			global $_SESSION;
			global $_GET;
			
			$local_IdLoja		= 	$_SESSION['IdLoja'];
			$local_Login		=	$_SESSION['Login'];
			$local_IdTicket		=	$_GET['IdTicket'];
			$local_IdMarcador	=	$_GET['IdMarcador'];
			
			$sql	=	"select IdMarcador from HelpDesk where IdTicket = $local_IdTicket;";
			$res	=	mysql_query($sql,$conCNT);
			$lin	=	mysql_fetch_array($res);
			
			if($lin[IdMarcador] == $local_IdMarcador){
				$local_IdMarcador = 'NULL';
			}
			
			
			$sql	=	"UPDATE HelpDesk SET
							IdMarcador		= $local_IdMarcador,
							DataAlteracao	= (concat(curdate(),' ',curtime())),
							LoginAlteracao	= '$local_Login'
						 WHERE 
						 	IdLoja 			= $local_IdLoja and
							IdTicket		= $local_IdTicket";
			if(mysql_query($sql,$conCNT) == true){
				if($local_IdMarcador!="NULL"){
					if($local_IdMarcador == 2){
						$local_IdMarcador = 22;
					}
				}else{
					$local_IdMarcador = 0;
				}
			}else{
				$local_IdMarcador = 0;
			}
			
			if($local_IdTicket != ''){
				header ("content-type: text/xml");
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				
				switch($local_IdMarcador){
					case 1:
						$CorMarcador1 = getParametroSistema(155, 1);
						$CorMarcador2 = getParametroSistema(156, 2);
						$CorMarcador3 = getParametroSistema(156, 3);
						break;
					case 22:
						$CorMarcador1 = getParametroSistema(156, 1);
						$CorMarcador2 = getParametroSistema(155, 2);
						$CorMarcador3 = getParametroSistema(156, 3);
						break;
					case 3:
						$CorMarcador1 = getParametroSistema(156, 1);
						$CorMarcador2 = getParametroSistema(156, 2);
						$CorMarcador3 = getParametroSistema(155, 3);
						break;
					default:
						$CorMarcador1 = getParametroSistema(156, 1);
						$CorMarcador2 = getParametroSistema(156, 2);
						$CorMarcador3 = getParametroSistema(156, 3);
						break;
				}
				
				$dados	.=	"\n<reg>";
				$dados	.=	"\n<CorMarcador1><![CDATA[$CorMarcador1]]></CorMarcador1>";
				$dados	.=	"\n<CorMarcador2><![CDATA[$CorMarcador2]]></CorMarcador2>";
				$dados	.=	"\n<CorMarcador3><![CDATA[$CorMarcador3]]></CorMarcador3>";
				$dados	.=	"\n<IdMarcadorAux><![CDATA[$local_IdMarcador]]></IdMarcadorAux>";
				$dados	.=	"\n</reg>";
			} else{
				$dados	= "false";
			}
			
			return $dados;
		}
		
		echo get_marcador();
	}
?>
