<?
	$localModulo		= 1;
	$localOperacao		= 151;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdMonitor		= formatText($_POST['IdMonitor'],NULL);
	$local_DescricaoMonitor	= formatText($_POST['DescricaoMonitor'],NULL);
	$local_IdStatus			= formatText($_POST['IdStatus'],NULL);
	$local_HostAddress		= formatText($_POST['HostAddress'],NULL);
	$local_Porta			= formatText($_POST['Porta'],NULL);
	$local_Timeout			= formatText($_POST['Timeout'],NULL);
	$local_Latitude			= formatText($_POST['Latitude'],NULL);
	$local_Longitude		= formatText($_POST['Longitude'],NULL);
	
	if($_GET['IdMonitor']!=''){
		$local_IdMonitor = $_GET['IdMonitor'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_monitor.php');
			break;		
		case 'alterar':
			include('files/editar/editar_monitor.php');
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
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/monitor.js'></script>
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
	<body onLoad="ativaNome('Monitor')">
		<? 
			include('filtro_monitor.php'); 
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_monitor.php' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='Monitor' />
					<div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Monitor</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdMonitor' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_monitor(this.value,true,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Dados do Monitor</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Descrição do Monitor</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>Host</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>Status</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescricaoMonitor' value='' style='width:350px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='HostAddress' value='' style='width:333px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatus' style='width:105px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4'>
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
								<td class='descCampo'>Porta</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Timeout</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Latitude</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Longitude</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>ON/OFF line</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Porta' value='' style='width:80px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Timeout' value='' style='width:80px' maxlength='15' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Latitude' value='' style='width:243px' maxlength='15' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Longitude' value='' style='width:243px' maxlength='15' onkeypress="" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='7' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Atualmente' value='' style='width: 102px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8' disabled/>
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Log</div>	
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Usuário Cadastro</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Cadastro</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Usuário Alteração</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Alteração</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao' value='' style='width:202px' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>	
								<td class='campo'>
									<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly />
								</td>								
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='9' onClick="cadastrar('inserir')" />
									<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='10' onClick="cadastrar('alterar')" />
									<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='11' onClick="excluir(document.formulario.IdMonitor.value)" />
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
					<div id='cp_monitor_alarme'>
						<div id='cp_tit' style='margin-bottom:0'>Alarme Cadastrados</div>
						<table id='tabelaMonitorAlarme' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'>Notificar quando</td>
								<td>QTD. de Tentativas</td>
								<td>Intervalo das Tentavidas</td>
								<td>E-mail para Notificação</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='10' id='tabelaMonitorAlarmeTotal'>Total: 0</td>
							</tr>
						</table>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
<script type='text/javascript'> 
<?
	if($local_IdMonitor != ''){
		echo "busca_monitor($local_IdMonitor,false,document.formulario.Local.value);";
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>