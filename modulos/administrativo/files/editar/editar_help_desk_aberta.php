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
		function get_help_desk_aberta(){
			global $conCNT;
			global $_GET;
			
			$local_IdTicket				=	$_GET['IdTicket'];
			$local_IdTicketHistorico	=	$_GET['IdTicketHistorico'];
			
			$sql = "select 
						HelpDeskHistorico.Aberta
					FROM 
						HelpDeskHistorico
					where 
						HelpDeskHistorico.IdTicket = $local_IdTicket and 
						HelpDeskHistorico.IdTicketHistorico = $local_IdTicketHistorico;";
			$res = mysql_query($sql,$conCNT);
			$lin = mysql_fetch_array($res);
			
			if($lin[Aberta] == 1){
				$lin[Aberta] = 2;
			} else{
				$lin[Aberta] = 1;
			}
			
			$sql = "UPDATE 
						HelpDeskHistorico 
					SET
						Aberta = $lin[Aberta]
					WHERE 
						IdTicket = $local_IdTicket and
						IdTicketHistorico = $local_IdTicketHistorico";
			if(mysql_query($sql,$conCNT) == true){
				header ("content-type: text/xml");
				$dados	 = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.= "\n<reg>";
				$dados	.= "\n<Aberta><![CDATA[$lin[Aberta]]]></Aberta>";
				$dados	.= "\n</reg>";
				
				return $dados;
			} else{
				return "false";
			}
		}
		
		echo get_help_desk_aberta();
	}
?>