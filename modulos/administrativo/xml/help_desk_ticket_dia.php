<?php
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
		
	function verifica_ticket(){
		global $conCNT;
		global $_GET;
		
		$local_IdLoja		= $_SESSION["IdLoja"];
		
		$IdGrupoUsuario 	= $_GET['IdGrupoUsuario'];
		$LoginResponsavel	= $_GET['LoginResponsavel'];
		$PrevisaoEtapa		= $_GET['Data'];
		$PrevisaoEtapa  	= dataConv($PrevisaoEtapa,"d/m/Y","Y-m-d");		
		if($LoginResponsavel != '') {
			$where = " and HelpDesk.LoginResponsavel = '$LoginResponsavel'";
		} else {
			$where = " and HelpDesk.IdGrupoUsuario = '$IdGrupoUsuario'";
		}
		
		$sql= "	select 
					 HelpDesk.IdTicket,
					 HelpDesk.Assunto,
					 HelpDeskTipo.DescricaoTipoHelpDesk,
					 HelpDeskSubTipo.DescricaoSubTipoHelpDesk 
				from
					 HelpDesk,
					 HelpDeskSubTipo,
					 HelpDeskTipo 
				where
					HelpDesk.IdLoja = $local_IdLoja and
					substring(PrevisaoEtapa, 1, 10) = '$PrevisaoEtapa' and 
					HelpDeskTipo.IdTipoHelpDesk = HelpDesk.IdTipoHelpDesk and
					HelpDeskSubTipo.IdTipoHelpDesk = HelpDesk.IdTipoHelpDesk and
					HelpDeskSubTipo.IdSubTipoHelpDesk = HelpDesk.IdSubTipoHelpDesk  
					$where";
		$res = @mysql_query($sql,$conCNT);
		$TicketDia = mysql_num_rows($res);
		if(@mysql_num_rows($res) >=1) {
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";		
			
			while($lin = @mysql_fetch_array($res)) {
				$TicketLista .= "Ticket: ".$lin['IdTicket']." - ".$lin['DescricaoTipoHelpDesk']." (".$lin['DescricaoSubTipoHelpDesk'].")"."<br>".$lin['Assunto']."<br><br>";

			}
			
			$dados	.= "<TicketLista><![CDATA[$TicketLista]]></TicketLista>";
			$dados	.=	"\n<TicketDia><![CDATA[$TicketDia]]></TicketDia>";
			$dados	.=	"\n</reg>";
			
			return $dados;	
		} else {
			return "false";
		}
	}
	echo verifica_ticket();
?>
