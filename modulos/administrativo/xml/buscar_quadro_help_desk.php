<?
	$localModulo	=	1;

	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_quadro_help_desk(){
		global $con;
		global $conCNT;
		global $_GET;
		
		$IdLoja			= $_SESSION['IdLoja'];
		$IdPessoa		= $_GET['IdPessoa'];
		$OrderBy		= $_GET['OrderBy'];
		$CampoOrderBy	= $_GET['CampoOrderBy'];

		$where = "";
		
		if($OrderBy == ''){
			$OrderBy = "DESC";
		}
 
		switch($CampoOrderBy){
			case "ImagemSeta_0":
				$CampoOrderBy = "HelpDesk.IdTicket";
				break;
			case "ImagemSeta_1":
				$CampoOrderBy = "HelpDeskTipo.DescricaoTipoHelpDesk, HelpDeskSubTipo.DescricaoSubTipoHelpDesk";
				break;
			case "ImagemSeta_2":
				$CampoOrderBy = "HelpDesk.Assunto";
				break;
			case "ImagemSeta_3":
				$CampoOrderBy = "HelpDesk.LoginCriacao";
				break;
			case "ImagemSeta_4":
				$CampoOrderBy = "HelpDesk.DataCriacao";
				break;
			case "ImagemSeta_5":
				$CampoOrderBy = "HelpDesk.PrevisaoEtapa";
				break;
			case "ImagemSeta_6":
				$CampoOrderBy = "ParametroSistema.ValorParametroSistema";
				break;
			default:
				$CampoOrderBy = "HelpDesk.IdStatus, HelpDesk.IdTicket";
				break;
		}

		if(getParametroSistema(229,1) == 1){
			$where = " and HelpDesk.IdLojaAbertura = $IdLoja"; 
		}
		
		$sql	=	"select
						HelpDesk.IdTicket,
						HelpDesk.IdPessoa,
						HelpDesk.IdMarcador,
						concat('Assunto: (',HelpDesk.Assunto,')') Assunto,
						subString(HelpDesk.Assunto,1,40) AssuntoTemp,
						HelpDesk.IdTipoHelpDesk,
						HelpDesk.IdSubTipoHelpDesk,
						HelpDesk.IdStatus,
						HelpDesk.DataCriacao,
						HelpDesk.PrevisaoEtapa,
						HelpDeskTipo.DescricaoTipoHelpDesk,
						HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
						HelpDesk.MD5,
						HelpDesk.LoginCriacao,
						concat(HelpDeskTipo.DescricaoTipoHelpDesk,'/',HelpDeskSubTipo.DescricaoSubTipoHelpDesk) TipoSubTipo,
						concat(subString(HelpDeskTipo.DescricaoTipoHelpDesk,1,8),'/',subString(HelpDeskSubTipo.DescricaoSubTipoHelpDesk,1,8)) TipoSubTipoTemp,
						ParametroSistema.ValorParametroSistema Status
					from
						HelpDesk,
						HelpDeskTipo,
						HelpDeskSubTipo,
						ParametroSistema
					where
						HelpDesk.IdLoja = 1 and					
						HelpDesk.IdPessoa = $IdPessoa and
						(
							HelpDesk.IdStatus < 400 or
							HelpDesk.IdStatus > 499 
						)and
						HelpDeskTipo.IdTipoHelpDesk = HelpDesk.IdTipoHelpDesk and
						HelpDeskSubTipo.IdSubTipoHelpDesk = HelpDesk.IdSubTipoHelpDesk and
						HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
						IdGrupoParametroSistema = 128 and
						IdParametroSistema = HelpDesk.IdStatus
						$where
					order by
						 $CampoOrderBy $OrderBy;";
		$res	=	@mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
				$lin[PrevisaoEtapa] = diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
			} else{
				$lin[PrevisaoEtapa] = '';
			}
						
			$Cor = getParametroSistema(154,$lin[IdStatus]);
						
			$lin[DataHoraTemp] 	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
			
			$sql0 = "SELECT 
						Obs,
						DataCriacao
					FROM 
						HelpDeskHistorico 
					WHERE 
						IdTicket = '$lin[IdTicket]'
					ORDER BY 
						IdTicketHistorico ASC 
					LIMIT 1;";
			$res0 = @mysql_query($sql0,$conCNT);
			$lin0 = @mysql_fetch_array($res0);
			
			if($lin0[Obs] != ''){
				$lin0[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin0[Obs]));
				$lin[Assunto] .= " <br />Data: " . dataConv($lin0[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
				$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Mensagem: (", endArray(explode("<b>Escrito por:</b> ", $lin0[Obs]))));
			}
			
			$lin[TipoSubTipo] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin[TipoSubTipo]));
			
			$dados	.=	"\n<IdTicket>$lin[IdTicket]</IdTicket>";
			$dados	.=	"\n<TipoSubTipo><![CDATA[".str_replace('"', "&quot;", $lin[TipoSubTipo])."]]></TipoSubTipo>";
			$dados	.=	"\n<TipoSubTipoTemp><![CDATA[$lin[TipoSubTipoTemp]]]></TipoSubTipoTemp>";
			$dados	.=	"\n<Assunto><![CDATA[".str_replace('"', "&quot;", $lin[Assunto])."]]></Assunto>";
			$dados	.=	"\n<AssuntoTemp><![CDATA[$lin[AssuntoTemp]]]></AssuntoTemp>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
			$dados	.=	"\n<PrevisaoEtapa><![CDATA[$lin[PrevisaoEtapa]]]></PrevisaoEtapa>";
			$dados	.=	"\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
			$dados	.=	"\n<Cor><![CDATA[$Cor]]></Cor>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			
			$cont++;
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_quadro_help_desk();
?>
