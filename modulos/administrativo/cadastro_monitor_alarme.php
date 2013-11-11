<?
	$localModulo		= 1;
	$localOperacao		= 176;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdMonitor			= formatText($_POST['IdMonitor'],NULL);
	$local_DescricaoMonitor		= formatText($_POST['DescricaoMonitor'],NULL);
	$local_IdStatusMonitor		= formatText($_POST['IdStatusMonitor'],NULL);
	$local_HostAddress			= formatText($_POST['HostAddress'],NULL);
	$local_Porta				= formatText($_POST['Porta'],NULL);
	$local_QtdTentativas		= formatText($_POST['QtdTentativas'],NULL);
	$local_IntervaloTentativa	= formatText($_POST['IntervaloTentativa'],NULL);
	$local_IdStatus				= formatText($_POST['IdStatus'],NULL);
	$local_DestinatarioMensagem	= formatText($_POST['DestinatarioMensagem'],NULL);
	$local_Mensagem				= formatText($_POST['Mensagem'],NULL);
	
	if($_GET['IdMonitor']!=''){
		$local_IdMonitor = $_GET['IdMonitor'];
	}
	
	if($_GET['IdStatus']!=''){
		$local_IdStatus = $_GET['IdStatus'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_monitor_alarme.php');
			break;		
		case 'alterar':
			include('files/editar/editar_monitor_alarme.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_url.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/monitor_alarme.js'></script>
		<script type='text/javascript' src='js/monitor_alarme_default.js'></script>
		<script type='text/javascript' src='js/monitor_default.js'></script>
	</head>
	<style type="text/css">
		input[type=text]:readOnly  		{ background-color: #FFF; }
		input[type=datetime]:readOnly  	{ background-color: #FFF; }
		input[type=date]:readOnly  		{ background-color: #FFF; }
		textarea:readOnly  				{ background-color: #FFF; }
		select:disabled  { background-color: #FFF; }
		select:disabled  { color: #000; }
	</style>
	<body onLoad="ativaNome('Monitor Alarme')">
		<? 
			include('filtro_monitor_alarme.php'); 
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_monitor_alarme.php' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='MonitorAlarme' />
					<input type='hidden' name='QtdTentativasDefault' value='<?=getCodigoInterno(3,177)?>' />
					<input type='hidden' name='IntervaloTentativaDefault' value='<?=getCodigoInterno(3,178)?>' />
					<div>
						<div id='cp_tit' style='margin-top:0;'>Dados do Monitor</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Monitor</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Descrição do Monitor</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Status</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaMonitor', true, event);"></td>
								<td class='campo'>
									<input type='text' name='IdMonitor' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_monitor(this.value,true,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescricaoMonitor' value='' style='width:613px' maxlength='100' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatusMonitor' style='width:105px' disabled>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 232 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Host</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Porta</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='HostAddress' value='' style='width:700px' maxlength='100' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Porta' value='' style='width:99px' maxlength='11' readOnly />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Dados do Alarme</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Notificar quando:</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>QTD. de Tentativas</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>Intervalo das Tentavidas</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='vertical-align:top;'>
									<select name='IdStatus' style='width:254px' onchange="busca_monitor_alarme(document.formulario.IdMonitor.value,this.value,true)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 234 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='vertical-align:top;'>
									<input type='text' name='QtdTentativas' value='' style='width:267px' maxlength='100' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								</td>
								<td class='separador' style='vertical-align:top;'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IntervaloTentativa' value='' style='width:267px' maxlength='100' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' />
									<br />
									Em segundos.
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Lista de E-mail para Notificação</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='DestinatarioMensagem' style='width: 816px;' rows='5' tabindex='6' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
									<br />
									Separadores: '|', ',', ':', ';' ou '\n' (Enter). 
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Mensagem de Aviso</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Mensagem' style='width: 816px;' rows='5' tabindex='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
								</td>
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='8' onClick="cadastrar('inserir')" />
									<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='9' onClick="cadastrar('alterar')" />
									<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='10' onClick="excluir(document.formulario.IdMonitor.value)" />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
							</tr>
						</table>
					</div>
				</form>
			</div>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/monitor.php");
			?>
		</div>
	</body>
</html>
<script type='text/javascript'> 
<?
	if($local_IdMonitor != ''){
		echo "busca_monitor($local_IdMonitor, false, document.formulario.Local.value);";
		
		if($local_IdStatus != ''){
			echo "busca_monitor_alarme($local_IdMonitor, $local_IdStatus, false, document.formulario.Local.value);";
		}
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>