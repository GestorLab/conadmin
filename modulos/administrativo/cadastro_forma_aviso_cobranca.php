<?
	$localModulo		=	1;
	$localOperacao		=	135;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdFormaAvisoCobranca				= formatText($_POST['IdFormaAvisoCobranca'],NULL);
	$local_DescricaoFormaAvisoCobranca		= formatText($_POST['DescricaoFormaAvisoCobranca'],NULL);
	$local_IdGrupoUsuarioMonitor			= formatText($_POST['IdGrupoUsuarioMonitor'],NULL);
	$local_ViaEmail							= formatText($_POST['ViaEmail'],NULL);
	$local_ViaImpressa						= formatText($_POST['ViaImpressa'],NULL);
	$local_MarcadorEstrela					= formatText($_POST['MarcadorEstrela'],NULL);	
	$local_MarcadorCirculo					= formatText($_POST['MarcadorCirculo'],NULL);	
	$local_MarcadorQuadrado					= formatText($_POST['MarcadorQuadrado'],NULL);	
	$local_MarcadorPositivo					= formatText($_POST['MarcadorPositivo'],NULL);	
	
	if($_GET['IdFormaAvisoCobranca']!=''){
		$local_IdFormaAvisoCobranca	=	$_GET['IdFormaAvisoCobranca'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_forma_aviso_cobranca.php');
			break;		
		case 'alterar':
			include('files/editar/editar_forma_aviso_cobranca.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
		
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_url.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/forma_aviso_cobranca.js'></script>
		<script type = 'text/javascript' src = 'js/forma_aviso_cobranca_default.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_usuario_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(118)?>')">
		<?include('filtro_forma_aviso_cobranca.php');?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_forma_aviso_cobranca.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='FormaAvisoCobranca'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(192)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdFormaAvisoCobranca' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_forma_aviso_cobranca(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(877)?></div>
					<table>
						<tr>
							<td valign='top'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(125)?></B></td>
									
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DescricaoFormaAvisoCobranca' value='' autocomplete="off" style='width:538px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
										</td>
										
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(878)?></td>									
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>												
										<td class='campo'>
											<select name='IdGrupoUsuarioMonitor' tabindex='6' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:543px'>
												<option value='' selected></option>
													<?
														$sql = "select IdGrupoUsuario, DescricaoGrupoUsuario from GrupoUsuario order by DescricaoGrupoUsuario";
														$res = @mysql_query($sql,$con);
														while($lin = @mysql_fetch_array($res)){
															echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
														}
													?>
											</select>
										</td>							
									</tr>
								</table>			
							</td>
							<td valign='top'>
								<table>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(879)?></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(430)?></td>
									</tr>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td valign='top'>							
											<table style='border:1px #A4A4A4 solid; height:21px; width:120px; margin-bottom:1px'>
												<tr>
													<td style='width:15px'><input type='checkbox' class='checkbox' name='ViaEmail' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'></td>
													<td style='padding-right:10px'><?=dicionario(176)?></td>
												</tr>
												<tr>
													<td style='width:15px'><input type='checkbox' class='checkbox' name='ViaImpressa' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'></td>
													<td style='padding-right:10px'><?=dicionario(880)?></td>										
												</tr>
											</table>
										</td>
										<td class='separador'>&nbsp;</td>
										<td valign='top'>							
											<table style='border:1px #A4A4A4 solid; height:21px; width:128px; margin-bottom:1px'>
												<tr>								
													<td style='width:15px'><input type='checkbox'  class='checkbox' name='MarcadorEstrela' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'></td>										
													<td style='width:15px'><?=dicionario(881)?></td>
													<td class='campo' style='padding:3px 0 0 10px'><div style='border:0px #a4a4a4 solid; width:12px'><img src='../../img/estrutura_sistema/ico_estrela.jpg' /></div></td>									
												</tr>
												<tr>								
													<td style='width:15px'><input type='checkbox'  class='checkbox' name='MarcadorCirculo' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'></td>										
													<td style='width:15px'><?=dicionario(882)?></td>
													<td class='campo' style='padding:3px 0 0 10px'><div style='border:0px #a4a4a4 solid; width:12px'><img src='../../img/estrutura_sistema/ico_circulo.jpg' /></div></td>									
												</tr>
												<tr>								
													<td style='width:15px'><input type='checkbox'  class='checkbox' name='MarcadorQuadrado' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'></td>										
													<td style='width:15px'><?=dicionario(883)?></td>
													<td class='campo' style='padding:3px 0 0 10px'><div style='border:0px #a4a4a4 solid; width:12px'><img src='../../img/estrutura_sistema/ico_quadrado.jpg' /></div></td>									
												</tr>
												<tr>								
													<td style='width:15px'><input type='checkbox'  class='checkbox' name='MarcadorPositivo' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'></td>										
													<td style='width:15px'><?=dicionario(884)?></td>
													<td class='campo' style='padding:3px 0 0 10px'><div style='border:0px #a4a4a4 solid; width:12px'><img src='../../img/estrutura_sistema/ico_positivo.jpg' /></div></td>									
												</tr>
											</table>
										</td>
									</tr>
								</table>							
							</td>
						</tr>
					</table>					
				</div>			
				<div>
					<div id='cp_tit'>Log</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(134)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='8' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='9' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='10' onClick="excluir(document.formulario.IdFormaAvisoCobranca.value)">
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
	</body>
</html>
<script type='text/javascript'> 
<?
	if($local_IdFormaAvisoCobranca!=''){
		echo "busca_forma_aviso_cobranca($local_IdFormaAvisoCobranca,false,document.formulario.Local.value);";
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>