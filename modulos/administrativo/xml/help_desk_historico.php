<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_help_desk_historico(){
		
		global $conCNT;
		global $_GET;
		
		$IdTicket				= $_GET['IdTicket'];
		$IdTicketHistorico		= $_GET['IdTicketHistorico'];
		$where			= "";
		
		if($IdTicket != ''){
			$where = " and HelpDesk.IdTicket=$IdTicket";
		}
		
		if($IdTicketHistorico != ''){
			$where = " and HelpDesk.IdTicketHistorico=$IdTicketHistorico";
		}
		
		$sql	=	"select
						HelpDesk.IdTicket,
						HelpDeskHistorico.IdTicketHistorico,
						HelpDesk.Assunto,
						HelpDeskHistorico.Obs,
						HelpDeskHistorico.IdStatusTicket,
						HelpDeskHistorico.Publica,
						HelpDeskHistorico.Marcar,
						HelpDeskHistorico.Aberta,
						HelpDeskHistorico.DataCriacao,
						HelpDeskHistorico.LoginCriacao,
						HelpDeskLocalHistorico.Cor
					from
						HelpDesk,
						HelpDeskHistorico,
						HelpDeskLocalHistorico
					where
						HelpDesk.IdTicket = HelpDeskHistorico.IdTicket and 
						HelpDeskHistorico.IdLocalHistorico = HelpDeskLocalHistorico.IdLocalHistorico $where
					order by 
						HelpDeskHistorico.IdTicketHistorico ASC;";
		$res	=	@mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[Cor]==""){
				$lin[Cor] = getParametroSistema(15,8);
			}
			if($lin[IdStatusTicket]!=""){
				$lin[Status] = getParametroSistema(128,$lin[IdStatusTicket]);
			}
			
			$conAnexo = false;
			
			$Anexo = "<div style='margin-top:10px;'><b>Anexos:</b>";
			$sql0	=	"select
							HelpDeskAnexo.IdAnexo,
							HelpDeskAnexo.DescricaoAnexo,
							HelpDeskAnexo.NomeOriginal,
							HelpDeskAnexo.MD5,
							OCTET_LENGTH(HelpDeskAnexo.FileAnexo) SizeBytes
						from
							HelpDeskAnexo
						where
							HelpDeskAnexo.IdTicket = $lin[IdTicket] and
							HelpDeskAnexo.IdTicketHistorico = $lin[IdTicketHistorico]
						order by 
							HelpDeskAnexo.IdAnexo;";
			$res0	=	@mysql_query($sql0,$conCNT);
			while($lin0	=	@mysql_fetch_array($res0)){
				if($lin0[NomeOriginal] != '' && $lin0[SizeBytes] != ''){
					$local_ExtArquivo	= endArray(explode(".",$lin0[NomeOriginal]));
					
					if($local_ExtArquivo != ''){
						$lin0[Anexo]		=  $lin0[IdAnexo].'.'.$local_ExtArquivo;
					}
					
					$Anexo .= "<div style='margin-top:4px;'><img src='".getIcone($local_ExtArquivo)."' style='margin-bottom:-3px;'> &nbsp;<a href='./download_anexo.php?Anexo=".md5($lin[IdTicket].$lin[IdTicketHistorico].$lin0[IdAnexo])."' style='color:#000;'>".$lin0[NomeOriginal]." (".ConvertBytes($lin0[SizeBytes]).") - $lin0[DescricaoAnexo]</a></div>";
					$conAnexo = true;
				}else{					
					$local_ExtArquivo	= endArray(explode(".",$lin0[NomeOriginal]));					
					if($local_ExtArquivo != ''){
						$lin0[Anexo]		=  $lin0[IdAnexo].'.'.$local_ExtArquivo;
					}
					
					$AnexoSize = filesize("../../helpdesk/anexo/$lin[IdTicket]/$lin0[MD5]");
					$Anexo .= "<div style='margin-top:4px;'><img src='".getIcone($local_ExtArquivo)."' style='margin-bottom:-3px;'> &nbsp;<a href='./download_anexo.php?Arquivo=true&Anexo=".md5($lin[IdTicket].$lin[IdTicketHistorico].$lin0[IdAnexo])."' style='color:#000;'>".$lin0[NomeOriginal]." (".ConvertBytes($AnexoSize).") - $lin0[DescricaoAnexo]</a> </div>";
					$conAnexo = true;
				}
			}
			$Anexo .= "</div>";
			
			if(!$conAnexo){
				$Anexo = '';
			}
			
			$dados	.=	"\n<IdTicketHistorico>$lin[IdTicketHistorico]</IdTicketHistorico>";
			$dados	.=	"\n<Anexo><![CDATA[$Anexo]]></Anexo>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
			$dados	.=	"\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Publica><![CDATA[$lin[Publica]]]></Publica>";
			$dados	.=	"\n<Marcar><![CDATA[$lin[Marcar]]]></Marcar>";
			$dados	.=	"\n<Aberta><![CDATA[$lin[Aberta]]]></Aberta>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_help_desk_historico();
?>