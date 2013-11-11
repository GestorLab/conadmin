<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdServico					= $_POST['IdServico'];
	$local_UrlContratoImpresso			= $_POST['UrlContratoImpresso'];
	$local_UrlRotinaCriacao				= $_POST['UrlRotinaCriacao'];
	$local_UrlRotinaCancelamento		= $_POST['UrlRotinaCancelamento'];
	$local_UrlRotinaBloqueio			= $_POST['UrlRotinaBloqueio'];
	$local_UrlRotinaDesbloqueio			= $_POST['UrlRotinaDesbloqueio'];
	$local_UrlRotinaAlteracao			= $_POST['UrlRotinaAlteracao'];
	$local_UrlRotinaAlteracao			= $_POST['UrlRotinaAlteracao'];
	$local_UrlDistratoImpresso			= $_POST['UrlDistratoImpresso'];
	$local_Email_lista_bloqueados		= $_POST['Email_lista_bloqueados'];
	$local_DadosMonitor					= $_POST['DadosMonitor'];
	$local_IdParametroServico			= $_POST['IdParametroServico'];
	$local_FiltroContratoParametro		= $_POST['FiltroContratoParametro'];
	$local_TermoCienciaCDA				= $_POST['TermoCienciaCDA'];
	
	if($_GET['IdServico']!=''){
		$local_IdServico	= $_GET['IdServico'];	
	}
	
	if($local_TermoCienciaCDA == "")
	{
		$local_TermoCienciaCDA = getCodigoInterno(3,201);
	}
	
	switch($local_Acao){
		case 'alterar':
			include('files/editar/editar_servico_rotina.php');
			break;
		default:
			$local_Acao = 'alterar';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico_rotina.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(636)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico_rotina.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ServicoRotina'>
				<input type='hidden' name='DadosMonitor' value=''>
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(435)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(436)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);busca_email_lista_bloqueados();' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:200px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>		
				</div>
				<div  id='cpUrlRotinas'>
					<div id='cp_tit'><?=dicionario(615)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(616)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlRotinaCriacao' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(617)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlRotinaAlteracao' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(618)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlRotinaCancelamento' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(619)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlRotinaBloqueio' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(620)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlRotinaDesbloqueio' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
							</td>
						</tr>
					</table>
				</div>
				<div  id='cpUrlModeloContrato'>
					<div id='cp_tit'><?=dicionario(621)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(621)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(967)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlContratoImpresso' id='UrlContratoImpresso' value='' style='width:658px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='7'>
							</td>
							<td class='find' id='LinkUrlContrato'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TermoCienciaCDA' style='width:125px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='7'>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 269
												order by
													IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_TermoCienciaCDA,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(622)?></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UrlDistratoImpresso' id='UrlDistratoImpresso' value='' style='width:795px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='8'>
							</td>
							<td class='find' id='LinkUrlDistratoImpresso'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></td>
						</tr>
					</table>
				</div>
				<div id='cpMonitor'>
					<div id='cp_tit'><?=dicionario(68)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(623)?></td>
							<td class='descCampo'><?=dicionario(624)?></td>
							<td class='descCampo'>
								<B id='titSMNP' style='margin-left:9px;'><?=dicionario(625)?></B>
								<B id='titComandoSSH' style='margin-left:9px;'><?=dicionario(626)?></B>
							</td>
							<td class='descCampo' id='td_titHistorico' style='width:50px;'><span id='titHistorico' style='margin-left:9px;'><?=dicionario(130)?></td>
							<td class='descCampo'><?=dicionario(627)?></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='padding-right:12px'>
								<select name='IdTipoMonitor' style='width:88px;' onchange="verificar_tipo_monitor(this.value);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='9'>
									<option value='' selected></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 212
												order by
													ValorParametroSistema";
										$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
									?>
								</select>
							</td>
							<td class='campo'>
								<select name='IdConsulta' style='width:88px;' onChange="verificar_consulta(this.value);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10' Onchange='verificar_consulta(this.value)'>
								<option value='' selected></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 235
												order by
													ValorParametroSistema";
										$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
									?>
								</select>
							</td>
							<td class='campo' style='padding-left:2px'>
								<select name='IdSNMP' style='width:359px; margin-left:9px;' onchange="verificar_snmp(this.value);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='11'>
									<option value='' selected>&nbsp;</option>
									<?
										$sql = "select 
													IdParametroSistema,
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema = 210 
												order by 
													ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<input type='text' name='ComandoSSH' value='' style='width:190px; margin-left:9px;' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='12'>
							</td>
							<td class='campo' id='td_cpHistorico' style='width:50px;padding-right:8px;'><input type='text' name='Historico' value='' style='width:50px; margin-left:9px;' onkeypress="mascara(this,event,'int')"  onchange="verificar_historico(this.value);" maxlength='4' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='12'>
							</td>
							<td class='campo' >
								<select name='IdFormatoResultado' style='width:88px;'  onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10'>
								<option value='' selected></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 236
												order by
													ValorParametroSistema";
										$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
									?>
								</select>
							</td>
							<td class='campo'>
							<div style='margin-left:3px;'><input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='14' onClick='adicionar_monitor();'></div></td>
						</tr>
						<tr>
							<td colspan='4'>&nbsp;</td>
							<td id='td_titEmdias' style='width:50px;'><div id='titEmdias' style='margin-left:9px;'><?=dicionario(628)?></div></td>
							<td >&nbsp;</td>
						</tr>	
					</table>
					<div id='cpParametroServico' style='display: none'>
						<table id=''>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Parâmetro Serviço</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Valor Parametro Serviço</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdParametroServico' style='width:321px;'  onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10'>
										<option value='' selected></option>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' value='' name='FiltroContratoParametro' style='width: 321px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10'>
								</td>
							</tr>
						</table>
					</div>
					<table id='tb_CodigoSNMP' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><span id='titCodigoSNMP'><?=dicionario(629)?></span></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' colspan='3'><input type='text' name='CodigoSNMP' style='width:306px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='13'>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>Ex.: $[ci(100,1):2] ou $[ps(100,1):2] ou $[cp(2):2]</td>
						</tr>
					</table>
					<!--table id="tb_ParametroContrato">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Parâmetro Serviço</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdFormatoResultado' style='width:288px;'  onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10'>
									<option value='' selected></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 236
												order by
													ValorParametroSistema";
										$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
									?>
								</select>
							</td>
						</tr>
					</table-->
					<table id='tabelaMonitor' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(623)?></td>
							<td><?=dicionario(624)?></td>
							<td><?=dicionario(625)?></td>
							<td><?=dicionario(130)?></td>
							<td><?=dicionario(627)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='8' id='totaltabelaMonitor'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cpNotificações'>
					<div id='cp_tit'><?=dicionario(630)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(631)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email_lista_bloqueados' value=''  style='width:399px' maxlength='130' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><?=dicionario(632)?></td>
						</tr>
					</table>		
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(571)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
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
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
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
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='15' onClick='cadastrar()'>
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
		<div id='quadros_fluantes'>
			<?
				include("files/busca/servico.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdServico != ""){
			echo "busca_servico($local_IdServico,false);";
		}
	?>
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>