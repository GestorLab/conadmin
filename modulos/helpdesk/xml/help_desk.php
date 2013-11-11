<?
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../rotinas/verifica.php');
	
	function get_ticket(){
		global $conCNT;
		global $_GET;
	
		$local_IdLoja	= $_SESSION['IdLojaHD'];
		$IdTicket		= $_GET['IdTicket'];
	//	$IdPessoa		= $_GET['IdPessoa'];
		$where			= "";
		
		if($IdTicket != ''){
			$where .= " and HelpDesk.IdTicket=$IdTicket";	
		}
		if($IdPessoa != ''){	
			$where .= " and HelpDesk.IdPessoa=$IdPessoa";	
		}	
		
		$sql	=	"select
						ValorParametroSistema
					from
						ParametroSistema
					where
						IdGrupoParametroSistema = 229 and
						IdParametroSistema = 1";
		$res	= @mysql_query($sql,$conCNT);
		$lin	= @mysql_fetch_array($res);

		if($lin[ValorParametroSistema] == 1){
			$where .= " and HelpDesk.IdLojaAbertura = $local_IdLoja"; 
		}

		$sql	=	"select
						HelpDesk.IdTicket,
						HelpDesk.IdLocalAbertura,
						HelpDesk.IdPessoa,
						HelpDesk.Assunto,
						HelpDesk.IdTipoHelpDesk,
						HelpDesk.IdSubTipoHelpDesk,
						HelpDesk.IdGrupoUsuario,
						HelpDesk.LoginResponsavel,
						subString(HelpDesk.PrevisaoEtapa,1,16) DataHora,
						Historico.Obs,
						HelpDesk.IdStatus,
						HelpDesk.LoginCriacao,
						HelpDesk.DataCriacao
					from
						HelpDesk,
						(select
							IdTicket,
							IdTicketHistorico,
							Obs
						from
							HelpDeskHistorico
						order by 
							IdTicketHistorico DESC) Historico
					where
						HelpDesk.IdLoja = 1 and
						HelpDesk.IdTicket = Historico.IdTicket $where
					group by
						Historico.IdTicket
					order by
						HelpDesk.IdTicket";
		$res	= @mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	= @mysql_fetch_array($res)){
			if($lin[DataHora]!=""){
				$DataHora = explode(' ', $lin[DataHora]);
			}
			
			$dados	.=	"\n<IdTicket>$lin[IdTicket]</IdTicket>";
			$dados	.=	"\n<LocalAbertura><![CDATA[$IdLocalAbertura]]></LocalAbertura>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
			$dados	.=	"\n<IdTipoHelpDesk><![CDATA[$lin[IdTipoHelpDesk]]]></IdTipoHelpDesk>";
			$dados	.=	"\n<IdSubTipoHelpDesk><![CDATA[$lin[IdSubTipoHelpDesk]]]></IdSubTipoHelpDesk>";
			$dados	.=	"\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
			$dados	.=	"\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
			$dados	.=	"\n<Data><![CDATA[$DataHora[0]]]></Data>";
			$dados	.=	"\n<Hora><![CDATA[$DataHora[1]]]></Hora>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ticket();
?>