<?
	include('../../../../../files/conecta.php');
	include('../../../../../files/funcoes.php');
	include('../../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdProtocolo	= $_GET['IdProtocolo'];
	
	$sql = "select
				IdProtocolo,
				LocalAbertura,
				IdPessoa,
				Assunto,
				IdStatus
			from
				Protocolo
			where
				IdLoja = $local_IdLoja and
				IdProtocolo = $local_IdProtocolo
			order by
				IdProtocolo;";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	$sql1 = "select
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
				IdPessoa = 1;";
	$res1 = @mysql_query($sql1,$con);
	$lin1 = @mysql_fetch_array($res1);
	
	if($lin1[Email] != ""){
		$lin1[Email] = "<b>E-mail: </b>".$lin1[Email];
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
	
	$sql2 = "select
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
				IdPessoa = $lin[IdPessoa];";
	$res2 = @mysql_query($sql2,$con);
	$lin2 = @mysql_fetch_array($res2);
	
	if($lin2[NomeRepresentante] != ""){
		$lin2[Nome] = $lin2[Nome]." / ".$lin2[NomeRepresentante];
	}
	
	if($lin2[Email] != ""){
		$lin2[Email] = "<b>E-mail: </b>".$lin2[Email];
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
	
	$sql4 = "select 
				IdParametroSistema,
				ValorParametroSistema 
			from 
				ParametroSistema 
			where 
				IdGrupoParametroSistema = 239 and 
				IdParametroSistema = $lin[IdStatus];";
	$res4 = @mysql_query($sql4,$con);
	$lin4 = @mysql_fetch_array($res4);
	
	$temp = substr($lin4[IdParametroSistema],0,1);
	$lin4[Cor] = getCodigoInterno(49,$temp);
	
	$Impress = "
		<html>
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
							<td class='titulo'>Protocolo $lin[IdProtocolo]</td>
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
							<td style='width:50%;padding:13px 13px 4px 13px;' colspan='2'><b>Assunto: </b>$lin[Assunto]</td>
						</tr>
					</table>
					<br />
					<br />
					<div class='cabecalho'><span class='titulo'>&nbsp;Histórico</span></div>
					<div>
	";
	$sql5 = "select
				IdProtocolo,
				IdProtocoloHistorico,
				Mensagem,
				IdStatus,
				DataCriacao,
				LoginCriacao
			from
				ProtocoloHistorico
			where
				IdProtocolo = $lin[IdProtocolo]
			order by
				IdProtocoloHistorico asc;";
	$res5 = mysql_query($sql5,$con);
	
	while($lin5 = mysql_fetch_array($res5)){
		$lin5[Mensagem] = str_replace("  ","&nbsp;&nbsp;",$lin5[Mensagem]);
		$lin5[Mensagem] = str_replace("\n","<br />",$lin5[Mensagem]);
		$lin5[Mensagem] = str_replace("' class='none'","display:none;'",$lin5[Mensagem]);
		
		$Impress .= "
						<div class='quadro'>
							<div style='height: 14px; margin-bottom: 2px;'>
								<div style='float:left;'><b>Data:</b> ".dataConv($lin5[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s')."</div>
								<div style='float:right;'><b>Status:</b> ".getParametroSistema(239,$lin5[IdStatus])."</div>
							</div>
							$lin5[Mensagem]
						</div>";
	}
	
	$Impress .=	"
					</div>
				</div>
			</body>
		</html>
		<html>
			<head>
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
			</head>
			<body>
				<br /><br />
				<table class='impress'>
					<tr>
						<td class='campo' style='text-align:center'>
							<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='javascript:self.print()' />
						</td>
					</tr>
				</table>
			</body>
		</html>
	";
	echo $Impress;
?>