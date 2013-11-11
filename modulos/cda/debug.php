<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= '';
	$local_EtapaAnterior	= 'listar_plano_disponivel.php';
	$local_IdPessoa			= $_GET['IdPessoa'];
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$sair = 'disabled';
	
	$local_MSGDescricao		= getParametroSistema(95,11);
	$local_IdLoja			= $_GET['IdLoja'];
	$local_IdServico		= $_GET['IdServico'];
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<div id="geral">
						<div id="main">
							<div id="coluna1">
								<div><img src="img/marca_conadmin2.png" width="260" height="50"></div>
								<div id="coluna1main">
									<? include("./files/indice.php"); ?>
								</div>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
										<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
										<td class="lrp"><img src="img/_Espaco.gif" /></td>
										<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
									</tr>
								</table>
							</div>
							<div id="coluna2">
								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									if($local_Erro != '') {
										echo "
										<table width='640' id='floatleft' border='0' cellspacing='0' cellpadding='0'>
											<tr>
												<td width='15'><img src='img/hgr1.png' width='15' height='50' /></td>
												<td id='tit' width='487'><h1><img src='img/icones/7.png' /> ".getParametroSistema(95,$local_IdParametroSistema)."</h1></td>
												<td align='right' width='123' id='titVoltar'><img src='img/ico_voltar.png' border='0' /> <a href='./'>Página Inicial</a></td>
												<td width='15'><img src='img/hgr2.png' width='15' height='50' /></td>
											</tr>
										</table>
										<table width='640' id='floatleft' border='0' cellspacing='0' cellpadding='0'>
											<tr>
												<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
													<BR>
													<p style='text-align:center'>".formTexto(getParametroSistema(99,$local_Erro))."</p>
													<BR>
												</td>
											</tr>
											<tr>
												<td colspan='4'><img src='img/coluna2_rp.png' width='640' height='15' /></td>
											</tr>
										</table>";
									} else {
										echo "
										<table width='640' id='floatleft' border='0' cellspacing='0' cellpadding='0'>
											<tr>
												<td width='15'><img src='img/hgr1.png' width='15' height='50' /></td>
												<td id='tit' width='487'><h1><img src='img/icones/9.png' /></h1></td>
												<td align='right' width='123' id='titVoltar'><img src='img/ico_voltar.png' border='0' /> <a href='./'>Página Inicial</a></td>
												<td width='15'><img src='img/hgr2.png' width='15' height='50' /></td>
											</tr>
										</table>
										<table width='640' id='floatleft' border='0' cellspacing='0' cellpadding='0'>
											<tr>
												<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
													<BR>
													<p style='text-align:center'>
														Atenção!
														<br />
														O arquivo para a operação solicitada não pode ser encontrado! Contacte o suporte.
													</p>
													<BR>
												</td>
											</tr>
											<tr>
												<td colspan='4'><img src='img/coluna2_rp.png' width='640' height='15' /></td>
											</tr>
										</table>";
									}
								?>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td height="20" align="center">
					<div id='rodape'>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="20"><img src="img/rp1.png" /></td>
								<td class="transparente">
									<?
										include("files/rodape.php");
									?>
								</td>
								<td width="20"><img src="img/rp2.png" /></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>