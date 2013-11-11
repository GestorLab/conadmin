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
		function get_help_desk_publica(){
			global $con;
			global $conCNT;
			global $_SESSION;
			global $_GET;
			
			$local_IdLoja				= 	$_SESSION['IdLoja'];
			$local_Login				=	$_SESSION['Login'];
			
			$local_IdTicket				=	$_GET['IdTicket'];
			$local_IdTicketHistorico	=	$_GET['IdTicketHistorico'];
			
			$sql	=	"select Publica, Obs from HelpDeskHistorico where IdTicket = $local_IdTicket and IdTicketHistorico = $local_IdTicketHistorico;";
			$res	=	mysql_query($sql,$conCNT);
			$lin	=	mysql_fetch_array($res);
			
			if($lin[Publica] == 1){
				$local_Publica = 2;
			}else{
				$local_Publica = 1;
			}		
			
		//	$lin[Obs] .= "<br />".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Pública [".getParametroSistema(171,$lin[Publica])." > ".getParametroSistema(171,$local_Publica)."]";		
						
			$sql	=	"UPDATE HelpDeskHistorico SET
							Publica			= $local_Publica														
						 WHERE 
							IdTicket		  = $local_IdTicket and
							IdTicketHistorico = $local_IdTicketHistorico";
			if(mysql_query($sql,$conCNT) == true){		
				header ("content-type: text/xml");
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.=	"\n<reg>";
				$dados	.=	"\n<Publica><![CDATA[$local_Publica]]></Publica>";
			}else{
				return "false";
			}					
		
			if(mysql_num_rows($res) >=1){
				$dados	.=	"\n</reg>";
				return $dados;
			}
			
			return $dados;
		}
		
		echo get_help_desk_publica();
	}
?>
