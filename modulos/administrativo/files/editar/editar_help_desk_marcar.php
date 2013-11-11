<?	
	$localModulo	= 1;
	$localOperacao	= 65;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/conecta_cntsistemas.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	} else{
		function get_help_desk_marcar(){
			global $conCNT;
			global $_GET;
			
			$local_IdTicket				=	$_GET['IdTicket'];
			$local_IdTicketHistorico	=	$_GET['IdTicketHistorico'];
			
			$sql = "select 
						HelpDeskHistorico.Marcar,
						HelpDeskLocalHistorico.Cor
					FROM 
						HelpDeskHistorico,
						HelpDeskLocalHistorico
					where 
						HelpDeskHistorico.IdLocalHistorico = HelpDeskLocalHistorico.IdLocalHistorico and
						HelpDeskHistorico.IdTicket = $local_IdTicket and 
						HelpDeskHistorico.IdTicketHistorico = $local_IdTicketHistorico;";
			$res = mysql_query($sql,$conCNT);
			$lin = mysql_fetch_array($res);
			
			if($lin[Marcar] == 1){
				$lin[Marcar] = 2;
			} else{
				$lin[Marcar] = 1;
			}
			
			$sql = "UPDATE 
						HelpDeskHistorico 
					SET
						Marcar = $lin[Marcar]
					WHERE 
						IdTicket = $local_IdTicket and
						IdTicketHistorico = $local_IdTicketHistorico";
			if(mysql_query($sql,$conCNT) == true){
				if($lin[Cor] == ""){
					$lin[Cor] = getParametroSistema(15,8);
				}
				
				header ("content-type: text/xml");
				$dados	 = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.= "\n<reg>";
				$dados	.= "\n<Marcar><![CDATA[$lin[Marcar]]]></Marcar>";
				$dados	.= "\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
				$dados	.= "\n</reg>";
				
				return $dados;
			} else{
				return "false";
			}
		}
		
		echo get_help_desk_marcar();
	}
?>