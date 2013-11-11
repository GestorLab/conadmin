<?
	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_HelpDeskSubTipo(){
		
		global $conCNT;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdTipoTicket	 		= $_GET['IdTipoTicket'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdTipoTicket != ''){	
			$where .= " and IdTipoHelpDesk=$IdTipoTicket";	
		}
		
		$sql	=	"select
						IdSubTipoHelpDesk,
						DescricaoSubTipoHelpDesk
					from
						HelpDeskSubTipo
					where
						IdStatus=1 $where
					order by
						DescricaoSubTipoHelpDesk $Limit";
		$res	=	mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdSubTipoHelpDesk>$lin[IdSubTipoHelpDesk]</IdSubTipoHelpDesk>";
			$dados	.=	"\n<DescricaoSubTipoHelpDesk><![CDATA[$lin[DescricaoSubTipoHelpDesk]]]></DescricaoSubTipoHelpDesk>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_HelpDeskSubTipo();
?>
