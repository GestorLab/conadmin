<?
	include ('../../../../../files/conecta.php');
	include ('../../../../../files/conecta_cntsistemas.php');
	include ('../../../../../files/funcoes.php');
	
	$local				= $_GET['Local'];
	$local_IdTicket		= $_GET['IdTicket'];
	
	if($local==1){
		include ('../../../../helpdesk/rotinas/verifica.php');
		$where = " and Publica = 1";
	} else{
		include ('../../../../../rotinas/verifica.php');
		$where = "";
	}
	
	$sql = "select
				IdTicket,
				IdLocalAbertura,
				IdPessoa,
				Assunto,
				IdTipoHelpDesk,
				IdSubTipoHelpDesk,
				IdStatus
			from
				HelpDesk
			where
				IdTicket=$local_IdTicket
			order by
				IdTicket;";
	$res = mysql_query($sql,$conCNT);
	$lin = mysql_fetch_array($res);
	
	$sql1	=	"select
					Nome,
					NomeRepresentante,
					Telefone1,
					Telefone2,
					Telefone3,
					Celular,
					Email
				from
					Pessoa
				where
					IdPessoa=1;";
	$res1	=	@mysql_query($sql1,$conCNT);
	$lin1	=	@mysql_fetch_array($res1);
	if($lin1[Email] != ""){
		$lin1[Email]		= "<b>E-mail: </b>".$lin1[Email];
	}
	
	if($lin1[Telefone1] != ""){
		$lin1[Telefone]	= "<b>Fone Residencial: </b>".$lin1[Telefone1];
	}
	if($lin1[Telefone2] != ""){
		$lin1[Telefone] .= "<br /><b>Fone Comercial: </b>".$lin1[Telefone2];
	}
	if($lin1[Telefone3] != ""){
		$lin1[Telefone] .= "&nbsp;/&nbsp;".$lin1[Telefone3];
	}
	if($lin1[Celular] != ""){
		$lin1[Telefone] .= "<br /><b>Celular: </b>".$lin1[Celular];
	}
	
	$sql2	=	"select
					Nome,
					NomeRepresentante,
					Telefone1,
					Telefone2,
					Telefone3,
					Celular,
					Email
				from
					Pessoa
				where
					IdPessoa=$lin[IdPessoa];";
	$res2	=	@mysql_query($sql2,$conCNT);
	$lin2	=	@mysql_fetch_array($res2);
	if($lin2[NomeRepresentante] != ""){
		$lin2[Nome]		= $lin2[Nome]." / ".$lin2[NomeRepresentante];
	}
	
	if($lin2[Email] != ""){
		$lin2[Email]		= "<b>E-mail: </b>".$lin2[Email];
	}
	
	if($lin2[Telefone1] != ""){
		$lin2[Telefone]	= "<b>Fone Residencial: </b>".$lin2[Telefone1];
	}
	if($lin2[Telefone2] != ""){
		$lin2[Telefone] .= "<br /><b>Fone Comercial: </b>".$lin2[Telefone2];
	}
	if($lin2[Telefone3] != ""){
		$lin2[Telefone] .= " / ".$lin2[Telefone3];
	}
	if($lin2[Celular] != ""){
		$lin2[Telefone] .= "<br /><b>Celular: </b>".$lin2[Celular];
	}
	
	$sql3	=	"
			select
				HelpDeskTipo.DescricaoTipoHelpDesk,
				HelpDeskSubTipo.DescricaoSubTipoHelpDesk
			from 
				HelpDeskTipo,
				HelpDeskSubTipo
			where 
				HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
				HelpDeskTipo.IdTipoHelpDesk=$lin[IdTipoHelpDesk] and
				HelpDeskSubTipo.IdSubTipoHelpDesk=$lin[IdSubTipoHelpDesk]
			order by 
				HelpDeskTipo.DescricaoTipoHelpDesk, 
				HelpDeskSubTipo.DescricaoSubTipoHelpDesk;";
	$res3	=	@mysql_query($sql3,$conCNT);
	$lin3	=	@mysql_fetch_array($res3);
	
	$sql4	=	"select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 128 and IdParametroSistema = $lin[IdStatus]"; 
	$res4	=	@mysql_query($sql4,$con);
	$lin4	=	@mysql_fetch_array($res4);
	
	$temp	=	substr($lin4[IdParametroSistema],0,1);
	$lin4[Cor]	=	getParametroSistema(147,$temp);
	
	$Impress	=	"
		<HTML>
			<head>
				<title>".getParametroSistema(4,1)."</title>
				<link REL='SHORTCUT ICON' HREF='../../../../../img/estrutura_sistema/favicon.ico'>
				<style type='text/css'>
					#conteiner{font: normal 10px Verdana, Arial, Times; margin: auto;}
					#conteiner .titulo{font-size:13px; font-weight: bold;}
					#conteiner .sub_titulo{font-size:12px;font-weight:bold;padding:0 2px 4px 13px;}
					#conteiner .cabecalho{width:100%; border-bottom:1px solid #AAA; margin-bottom:13px;}
					#conteiner .quadro{border:1px solid #000; padding:5px 13px 5px 13px; margin-top:13px; text-align: justify;}
				</style>
			</head>
			<body>
				<div id='conteiner'>
					<table class='cabecalho' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='titulo'>Ticket $lin[IdTicket]</td>
							<td style='width:130px;font-size:9px;'>".date('d/m/Y H:i:s')."</td>
							<td class='titulo' style='width:252px;text-align:right;'>Status: <span style='color:$lin4[Cor];'>$lin4[ValorParametroSistema]</span></td>
						</tr>
					</table>
					<table style='width:100%;border:1px solid #000;' cellpadding='0' cellspacing='0'>
						<tr>
							<td style='padding:13px 13px 4px 13px;' width='60%'>Cliente</td>
							<td style='padding:13px 13px 4px 13px;' width='40%'>Prestadora</td>
						</tr>
						<tr>
							<td class='sub_titulo' style='width: 60%; vertical-align: top;'>$lin2[Nome]</td>
							<td class='sub_titulo' style='width: 40%; vertical-align: top;'>$lin1[Nome]</td>
						</tr>
						<tr>
							<td style='padding:0 2px 4px 13px;'>$lin2[Telefone]</td>
							<td style='padding:0 13px 4px 13px;'>$lin1[Telefone]</td>
						</tr>
						<tr>
							<td style='padding:0 2px 4px 13px;'>$lin2[Email]</td>
							<td style='padding:0 13px 4px 13px;'>$lin1[Email]</td>
						</tr>
						<tr>
							<td style='width:50%;padding:13px 13px 4px 13px;' colspan='2'><b>Tipo: </b>$lin3[DescricaoTipoHelpDesk]</td>
						</tr>
						<tr>
							<td style='width:50%;padding:0 13px 4px 13px;' colspan='2'><b>SubTipo: </b>$lin3[DescricaoSubTipoHelpDesk]</td>
						</tr>
						<tr><td style='padding:0 13px 13px 13px;' colspan='2'><b>Assunto: </b>$lin[Assunto]</td></tr>
					</table>
					<br />
					<br />
					<div class='cabecalho'><span class='titulo'>&nbsp;Histórico</span></div>
					<div>
	";
	$sql5	=	"select
				IdTicket,
				IdTicketHistorico,
				Obs,
				IdStatusTicket,
				DataCriacao,
				LoginCriacao
			from
				HelpDeskHistorico
			where
				IdTicket=$lin[IdTicket] $where
			order by
				IdTicketHistorico ASC;";
	$res5 = mysql_query($sql5,$conCNT);
	while($lin5 = mysql_fetch_array($res5)){
		$lin5[Obs] = str_replace("  ","&nbsp;&nbsp;",$lin5[Obs]);
		$lin5[Obs] = str_replace("\n","<br />",$lin5[Obs]);
		$lin5[Obs] = str_replace("' class='none'","display:none;'",$lin5[Obs]);
		
		$Impress .= "
						<div class='quadro'>
							<div style='height: 14px; margin-bottom: 2px;'>
								<div style='float:left;'><b>Data:</b> ".dataConv($lin5[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s')."</div>
								<div style='float:right;'><b>Status:</b> ".getParametroSistema(128,$lin5[IdStatusTicket])."</div>
							</div>
							$lin5[Obs]
						</div>";
	}
	$Impress	.=	"
					</div>
				</div>
			</body>
		</HTML>
		<HTML>
			<HEAD>
				<style type='text/css'>
					body{	margin:0; padding:5px;	font: normal 10px Verdana, Arial, Times;	}
					p{ 	margin:0 0 1px 0; }
					table{ width:99%;	}	
					table .lateral{	width: 15px; padding: 0; border-right:1px #000 solid; text-align:center; }
					table tr td{	font: normal 10px Verdana, Arial, Times; margin:0; padding-left:5px;	}
					h1{	font-size: 20px; font-weight:bold}
					.boatao{ padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid;	height: 20px; background-color: #FFF; }
				</style>
				<link rel = 'stylesheet' type = 'text/css' href = '../../../../../css/impress.css' media='print' />
			</HEAD>
			<BODY>
				<BR><BR>
				<table class='impress'>
					<tr>
						<td class='campo' style='text-align:center'>
							<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='javascript:self.print()'>
						</td>
					</tr>
				</table>
			</BODY>
		</HTML>
	";
	echo $Impress;
?>
