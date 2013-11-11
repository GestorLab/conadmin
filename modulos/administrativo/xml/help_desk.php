<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_help_desk(){
		global $conCNT;
		global $_GET;
		
		$IdTicket		= $_GET['IdTicket'];
		$local_IdLoja	= $_SESSION["IdLoja"];
		$IdLicenca		= $_SESSION["IdLicenca"];
		
		$where  = "";
		$where2 = "";
		
		if($IdTicket != ''){
			$where .= " and HelpDesk.IdTicket = $IdTicket";
			$where2 .= " and HelpDeskHistorico.IdTicket = $IdTicket";
		}

		if($IdLicenca != '2007A000' && $IdLicenca != '2007A001'){
			//$where .= " and HelpDesk.IdPessoa = ".getParametroSistema(4,7);
		}
		
		$sql	=	"select
						HelpDesk.IdTicket,
						HelpDesk.IdLojaAbertura,
						Historico.IdTicketHistorico,
						HistoricoMensagem.IdHistoricoMensagem,
						HelpDesk.IdLocalAbertura,
						HelpDesk.IdPessoa,
						HelpDesk.IdPrioridade,
						HelpDesk.IdMarcador,
						HelpDesk.Assunto,
						HelpDesk.ChangeLog,
						Historico.Obs,
						HelpDesk.IdTipoHelpDesk,
						HelpDesk.IdSubTipoHelpDesk,
						HelpDesk.IdGrupoUsuario,
						HelpDesk.LoginResponsavel,
						subString(HelpDesk.PrevisaoEtapa,1,16) DataHora,
						HelpDesk.IdStatus,
						HelpDesk.IdLoja IdLojaHelpDesk,
						HelpDesk.LoginCriacao,
						HelpDesk.DataCriacao,
						HelpDesk.LoginAlteracao,
						HelpDesk.DataAlteracao,
						PessoaEndereco.IdPais,
						PessoaEndereco.IdEstado,
						PessoaEndereco.IdCidade,
						Cidade.NomeCidade,
						Pais.NomePais,
						Estado.NomeEstado
					from
						HelpDesk,
						HistoricoMensagem,
						PessoaEndereco,
						Cidade, 
						Estado, 
						Pais,						
						(select 
							IdTicket,
							IdTicketHistorico,
							Obs
						from
							HelpDeskHistorico
						where
							 1 $where2
						order by
							IdTicketHistorico DESC) Historico
					where
						HelpDesk.IdLoja = $local_IdLoja and
						HelpDesk.IdTicket=Historico.IdTicket and 
						HelpDesk.IdPessoa = PessoaEndereco.IdPessoa and
						Pais.IdPais = PessoaEndereco.IdPais and
						Estado.IdPais = PessoaEndereco.IdPais and
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdPais = PessoaEndereco.IdPais and
						Cidade.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdCidade = PessoaEndereco.IdCidade 
						$where
					group by
						Historico.IdTicket;";
		$res	= @mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	= @mysql_fetch_array($res)){
			if($lin[LoginResponsavel] != '') {
				$where = " AND LoginResponsavel = '$lin[LoginResponsavel]'";
			} else {
				$where = " AND ( LoginResponsavel IS NULL OR LoginResponsavel = '' OR LoginResponsavel != '') AND 
						 IdGrupoUsuario = '$lin[IdGrupoUsuario]'";
			}
			
			$sql1 = "SELECT 
						COUNT(*) TicketDia
					FROM
						HelpDesk 
					WHERE 
						SUBSTRING(PrevisaoEtapa,1,10) = SUBSTRING('$lin[DataHora]',1,10)
						$where;";
			$res1 = @mysql_query($sql1,$conCNT);
			$lin1 = @mysql_fetch_array($res1);
			
			if($lin1[TicketDia] != '' && $lin[IdGrupoUsuario] != '' && $lin[DataHora] != '') {
				$lin[TicketDia] = $lin1[TicketDia];
			} else{
				$lin[TicketDia] = 0;
			}
			
			if($lin[DataHora] != ""){
				$DataHora = explode(' ', $lin[DataHora]);
			}
			
			if($lin[IdPrioridade] == ""){
				$lin[IdPrioridade] = 0;
			}
		
			$DescricaoPrioridade		= getParametroSistema(152,$lin[IdPrioridade]);
			$LocalAbertura 				= getParametroSistema(129,$lin[IdLocalAbertura]);
			$localVisualizarHistorico	= getCodigoInterno(3,111);
			
			switch($lin[IdMarcador]){
				case 1:
					$CorMarcador1 = getParametroSistema(155, 1);
					$CorMarcador2 = getParametroSistema(156, 2);
					$CorMarcador3 = getParametroSistema(156, 3);
					break;
				case 2:
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
			
			$dados	.=	"\n<IdTicket>$lin[IdTicket]</IdTicket>";
			$dados	.=	"\n<TicketDia>$lin[TicketDia]</TicketDia>";
			$dados	.=	"\n<IdTicketHistorico>$lin[IdTicketHistorico]</IdTicketHistorico>";
			$dados	.=	"\n<IdHistoricoMensagem>$lin[IdHistoricoMensagem]</IdHistoricoMensagem>";
			$dados	.=	"\n<LocalAbertura><![CDATA[$LocalAbertura]]></LocalAbertura>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdPrioridade><![CDATA[$lin[IdPrioridade]]]></IdPrioridade>";
			$dados	.=	"\n<DescricaoPrioridade><![CDATA[$DescricaoPrioridade]]></DescricaoPrioridade>";
			$dados	.=	"\n<IdMarcador><![CDATA[$lin[IdMarcador]]]></IdMarcador>";
			$dados	.=	"\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
			$dados	.=	"\n<ChangeLog><![CDATA[$lin[ChangeLog]]]></ChangeLog>";
			$dados	.=	"\n<Mensagem><![CDATA[$lin[Obs]]]></Mensagem>";
			$dados	.=	"\n<IdTipoHelpDesk><![CDATA[$lin[IdTipoHelpDesk]]]></IdTipoHelpDesk>";
			$dados	.=	"\n<IdSubTipoHelpDesk><![CDATA[$lin[IdSubTipoHelpDesk]]]></IdSubTipoHelpDesk>";
			$dados	.=	"\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
			$dados	.=	"\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
			$dados	.=	"\n<Data><![CDATA[$DataHora[0]]]></Data>";
			$dados	.=	"\n<Hora><![CDATA[$DataHora[1]]]></Hora>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<IdLojaHelpDesk><![CDATA[$lin[IdLojaAbertura]]]></IdLojaHelpDesk>";
			$dados	.=	"\n<VisualizarHistorico><![CDATA[$localVisualizarHistorico]]></VisualizarHistorico>";
			$dados	.=	"\n<CorMarcador1><![CDATA[$CorMarcador1]]></CorMarcador1>";
			$dados	.=	"\n<CorMarcador2><![CDATA[$CorMarcador2]]></CorMarcador2>";
			$dados	.=	"\n<CorMarcador3><![CDATA[$CorMarcador3]]></CorMarcador3>";
			$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
			$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
			$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";
			$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";
			$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_help_desk();
?>