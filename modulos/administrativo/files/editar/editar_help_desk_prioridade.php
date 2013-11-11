<?
	$localModulo		=	1;
	$localOperacao		=	65;	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/conecta_cntsistemas.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	function get_prioridade(){
		global $conCNT;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdTicket		= $_GET['IdTicket'];	
		
		
		$sql	=	"select (IdPrioridade+1)IdPrioridade from HelpDesk where IdTicket = $local_IdTicket;";
		$res	=	mysql_query($sql,$conCNT);
		$lin	=	mysql_fetch_array($res);
		
		if($lin[IdPrioridade] == ''){
			$lin[IdPrioridade] = 1;
		}
		if($lin[IdPrioridade] > 4){
			$lin[IdPrioridade] = 0;
		}
		
		$local_IdPrioridade = $lin[IdPrioridade];
		
		$sql	=	"UPDATE HelpDesk SET
						IdPrioridade	= $local_IdPrioridade,
						DataAlteracao	= (concat(curdate(),' ',curtime())),
						LoginAlteracao	= '$local_Login'
					 WHERE 
					 	IdLoja 			= $local_IdLoja and
						IdTicket		= $local_IdTicket";
		if(mysql_query($sql,$conCNT) == true){
			$DescricaoPrioridade = getParametroSistema(152,$lin[IdPrioridade]);
			
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$dados	.=	"\n<IdPrioridade><![CDATA[$lin[IdPrioridade]]]></IdPrioridade>";
			$dados	.=	"\n<DescricaoPrioridade><![CDATA[$DescricaoPrioridade]]></DescricaoPrioridade>";
			$dados	.=	"\n</reg>";
			
		} else{
			$dados	=	"false";
		}
		
		return $dados;
	}
	
	echo get_prioridade();
?>
