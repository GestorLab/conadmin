<?	
error_reporting(0);
//ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
	//set_time_limit(0);

	include ('files/conecta.php');
	include ('files/funcoes.php');

	if(getParametroSistema(252,1) == 1){
		include ('files/conecta_cntsistemas.php');
		if(!$conCNT){

			mysql_close($con);
			include ('files/conecta.php');

			// Desativa a próxima tentativa de conexão.
			$sql = "UPDATE ParametroSistema SET ValorParametroSistema='0' WHERE IdGrupoParametroSistema='252' AND IdParametroSistema='1'";
			mysql_query($sql,$con);
		}
	}
	
	$local_Erro = $_GET['Erro'];
	
	session_start("ConAdmin_session");
	/*if(file_exists("atualizacao") && $local_Erro == ''){
		header("Location: atualizacao.php");
	}*/

	############ Verifica Licença
	$Vars = Vars();
 	$Vars[DataLicenca] = dataConv($Vars[DataLicenca], 'Ymd', 'Y-m-d');
	$Vars[DataHoje] = date("Y-m-d");

	if($Vars[DataLicenca] != $Vars[DataHoje]){
		$nDiasIntervalo = nDiasIntervalo($Vars[DataLicenca],$Vars[DataHoje]);
		$nDiasIntervalo--;
		if($nDiasIntervalo < 0){
			$nDiasIntervalo = $nDiasIntervalo * (-1);
		}
	 	$Vars[DiasLicenca] -= $nDiasIntervalo;
		AtualizaConfig($Vars[IdLicenca], $Vars[TipoLicenca], $Vars[DiasLicenca]);

		if($Vars[DiasLicenca] <= 7){
			$KeyCode	= KeyCode($Vars[IdLicenca],1);

			$File		= @file("http://intranet.cntsistemas.com.br/licenca/licenca.php?KeyCode=$KeyCode");
			$KeyLicenca = endArray($File);
			KeyProcess($KeyCode, $KeyLicenca);
			$Vars = Vars();
		}
	}
	
	
############ Verifica Licença
	$Versao 	  = versao();
	$user_browser = getDataBrowser();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ANSI" />
		
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/index.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico" />
		<?
			if($Vars[DiasLicenca] == 0){
				echo "<meta http-equiv='refresh' content='".getParametroSistema(108,1)."; url=rotinas/licenca.php' />";
			}else{
				echo "<meta http-equiv='refresh' content='".getParametroSistema(108,1)."' />";
			}
		?>
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<form action='rotinas/autentica.php' method='post' name='formulario' onSubmit='return validar()'>
				<input type='hidden' name='Local' value='index' />
				<input type='hidden' name='Acao' value='login' />	
				<input type='hidden' name='Browser' value='' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />	
				<input type='hidden' name='autorizaAntispam' />	
				<table>
					<tr>
						<td class='descCampo' colspan='2'><B><?=dicionario(1)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='IdLoja' value='' style='width:70px' maxlength='2' onChange='busca_loja(this.value)' onkeypress="mascara(this,event,'int')" tabindex='1' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" />
						</td>
						<td class='campo'>
							<input type='text' name='DescricaoLoja' value='' style='width: 268px' readOnly="readOnly" />
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'><B><?=dicionario(2)?></B></td>
						<td class='descCampo'><B><?=dicionario(3)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' value='' style='width: 169px' maxlength='20' tabindex=2 onFocus="Foco(this,'in')" onchange="busca_usuario(this.value, false)"  onBlur="Foco(this,'out')" />
						</td>
						<td class='campo'>
							<input type='password' name='Senha' value='' style='width: 169px' tabindex=3  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="cadastrar(event);" />
						</td>
					</tr>
					<tr>
						<td class='campo' colspan="2">
							<table cellspacing="0" cellpadding="0" border="0" id="AntiSpam" style="margin-top:3px; display: none;">
								<tr>
									<td class='descCampo'><B>Digite os caracteres ao lado</B></td>
									<td rowspan="2" style="vertical-align: bottom;" class='descCampo'>
										<div style=" margin-top:2px; cursor:pointer; padding-left: 7px;">
											<img src="classes/antispam/img_antispam.php" onclick="atualizarAntispam(this)" width="175px" height="35px" />
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<input type="text"  name="AntiSpamSTR"  style="width:165px;" tabindex='4'onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" />
									</td>						
								</tr>
							</table>
						</td>
					</tr>									
					<tr>
						<td class='descInfo'><a href='alterar_senha.php'><?=dicionario(4)?>?</a>&nbsp;&nbsp;<a href='alterar_senha.php'><img src='img/estrutura_sistema/ico_chave.gif' alt='Alterar senha?'></a></td>
						<td style='text-align:right'><input name="bt_logar" type='submit' value='<?=dicionario(9)?>' class='botao' tabindex='4' disabled /></td>
					</tr>
					<tr>
						<td class='descInfo' colspan=2><a href='http://www.cntsistemas.com.br' target='_blank'><?=dicionario(5)?></a> | <a href='helpdesk.php'><?=dicionario(6)?></a> | <a href='atualizar_licenca.php'><?=dicionario(7)?></a> | <a href='modulos/cda/index.php'><?=dicionario(8)?></a></td>
					</tr>
				</table>
				<BR />
				<table width='355px'>
					<tr>
						<?
							foreach($versao_browser as $abbreviation){
								echo "<td style='padding:2px 0 0 2px'>";
								
								foreach($abbreviation as $id => $value){
									if($id == 0){
										echo "<img src='$value'";
									} elseif($id == 1){
										echo " onmousemove=\"quadro_alt(event, this, '".$value;
									} else{
										echo "<br />".$value;
									}
								}
								
								echo "');\" /></td>";
							}
						?>
						<td valign='bottom' style='padding:2px 0 0 2px; text-align:right; width:210px'>v. <?=$Versao[DescricaoVersao]?></td>
					</tr>
				</table>
				<table>
					<tr>
						<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
				<?
					if($Vars[DiasLicenca] <= 7 && $Vars[DiasLicenca] > 0){
						echo "<table>
								<tr>
									<td><h1 style='color: #C10000'>A licença do ConAdmin irá expirar em $Vars[DiasLicenca] dia(s).</h1></td>
								</tr>
							</table>";
					}
					
					if($Vars[DiasLicenca] == 0){
						echo "<table>
								<tr>
									<td><h1 style='color: #C10000'>A licença do ConAdmin expirou.</h1></td>
								</tr>
							</table>";
					}
				?>
			</form>
		</div>
		<?
			if(!browserCompativel($user_browser) && $_POST['VerificarBrowser'] == ''){
				echo "<div id='incompativel'>
						<p id='p1'>
							A versão deste navegador não é compatível com o sistema ConAdmin.
							<br />
						</p>
					</div>";
			}
		?>
	</body>
</html>
<script>
	function atualizarAntispam(campo){
		campo.src = "classes/antispam/img_antispam.php?alt="+Math.random();
	}
	verificaErro();
	inicia();
	//enterAsTab(document.forms.formulario);
</script>