<?
	$localModulo		= 1;
	$localOperacao		= 167;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET['Erro'];	

	$local_IdTipoMensagem		= $_POST['IdTipoMensagem'];
	$local_IdTemplate			= $_POST['IdTemplate'];
	$local_IdContaEmail			= $_POST['IdContaEmail'];
	$local_LimiteEnvioDiario	= formatText($_POST['LimiteEnvioDiario'],NULL);
	$local_IdStatus				= $_POST['IdStatus'];
	$local_Delay				= $_POST['Delay'];
	$local_Titulo				= formatText($_POST['Titulo'],NULL);
	$local_Assunto				= formatText($_POST['Assunto'],NULL);
	$local_Conteudo				= $_POST['Conteudo'];
	$local_Assinatura			= formatText($_POST['Assinatura'],NULL);

	if($local_IdTipoMensagem == '') {
		$local_IdTipoMensagem = $_GET['IdTipoMensagem'];
	}
	
	switch($local_Acao){
		case 'alterar':
			include('files/editar/editar_tipo_mensagem.php');
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
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />

		<script type='text/javascript' src='../../classes/calendar/calendar.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-setup.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-br.js'></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/tipo_mensagem.js'></script>
		<script type='text/javascript' src='js/tipo_mensagem_default.js'></script>

		<style type='text/css'>
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
		</style>
	</head>	
	<body onLoad="ativaNome('Tipo Mensagem')">
		<? 
			include('filtro_tipo_mensagem.php'); 
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_tipo_mensagem.php' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='TipoMensagem' />
					<input type='hidden' name='IdStatusDefault' value='<?=getCodigoInterno(3,172);?>' />
					<input type='hidden' name='camposObrigatoriedade' />

					<div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Tipo</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdTipoMensagem' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_tipo_mensagem(this.value,true,document.formulario.Local.value);atualizar_formulario_IdContaEmail(document.formulario.IdTemplate.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Dados do Tipo</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Template</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><div id='Conta'>Conta E-mail/SMS</div></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Limite Diário</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Delay Disparo</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>Status</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdTemplate' style='width:182px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="atualizar_formulario_IdContaEmail(this.value);displayCampo(document.formulario.IdTemplate.value)" tabindex='2'>
										<option value='' selected></option>
										<?
											$sql = "select IdTemplate, DescricaoTemplate from TemplateMensagem where IdLoja = $local_IdLoja order by DescricaoTemplate";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdTemplate]'>$lin[DescricaoTemplate]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdContaEmail' style='width:306px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3'>
										<option value='' selected ></option>
										
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LimiteEnvioDiario' value='' style='width:89px' maxlength='100' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Delay' value='' style='width:89px' maxlength='100' onkeypress="mascara(this,event,'horaMinSeg')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatus' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 227 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td/>
								<td/>
								<td/>
								<td/>
								<td/>
								<td/>
								<td/>
								<td>
									<p style='margin:0; float:left'>00:00:00</p>
								</td>
								<td/>
								<td/>
							</tr>
						</table>
						<div id='cp_tit'><?=dicionario(796)?></div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Título</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo' id='TitAssunto' ><b>Assunto</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Titulo' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Assunto' id='Assunto' value='' style='width:400px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='7' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Conteúdo</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Conteudo' style='width:816px; height:141px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8'></textarea>
								</td>
						</table>
						<table id='Assinatura'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Assinatura</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Assinatura'  style='width:816px; height:141px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='9'></textarea>
								</td>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Log</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Usuário Alteração</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Alteração</td>
								<td class='separador'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly />
								</td>							
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' style='width:57px;' name='bt_alterar' value='Alterar' class='botao' tabindex='11' onClick="cadastrar('alterar')" disabled />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText' name='helpText'>&nbsp;</h1><b style='color:#C10000;'></b></td>
							</tr>
						</table>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
<script type='text/javascript'>
<!-- 
<?
	if($local_IdTipoMensagem != '') {
		echo "busca_tipo_mensagem($local_IdTipoMensagem,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}		
?>

	function status_inicial(){
		document.formulario.IdStatus.value = document.formulario.IdStatusDefault.value;
	}
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
	-->
</script>