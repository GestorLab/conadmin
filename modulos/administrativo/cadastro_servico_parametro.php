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
	
	$local_IdServico						= $_POST['IdServico'];
	$local_IdParametroServico				= $_POST['IdParametroServico'];
	$local_IdTipoParametro					= $_POST['IdTipoParametro'];
	$local_DescricaoParametroServico		= trim($_POST['DescricaoParametroServico']);
	$local_OpcaoValor						= trim($_POST['OpcaoValor']);
	$local_ValorDefaultInput				= trim($_POST['ValorDefaultInput']);
	$local_ValorDefaultSelect				= trim($_POST['ValorDefaultSelect']);
	$local_IdMascaraCampo					= $_POST['IdMascaraCampo'];
	$local_IdStatusParametro				= $_POST['IdStatusParametro'];
	$local_Editavel_Texto					= $_POST['Editavel_Texto'];
	$local_Obrigatorio_Texto				= $_POST['Obrigatorio_Texto'];
	$local_ObrigatorioStatus_Texto			= $_POST['ObrigatorioStatus_Texto'];
	$local_Calculavel_Texto					= $_POST['Calculavel_Texto'];
	$local_Visivel_Texto					= trim($_POST['Visivel_Texto']);
	$local_VisivelOS_Texto					= trim($_POST['VisivelOS_Texto']);
	$local_VisivelCDA_Texto					= trim($_POST['VisivelCDA_Texto']);
	$local_AcessoCDA_Texto					= trim($_POST['AcessoCDA_Texto']);
	$local_Unico_Texto						= trim($_POST['Unico_Texto']);
	$local_BotaoAuxiliar_Texto				= trim($_POST['BotaoAuxiliar_Texto']);
	$local_ParametroDemonstrativo_Texto		= trim($_POST['ParametroDemonstrativo_Texto']);
	$local_Editavel_Selecao					= $_POST['Editavel_Selecao'];
	$local_Obrigatorio_Selecao				= $_POST['Obrigatorio_Selecao'];
	$local_ObrigatorioStatus_Selecao		= $_POST['ObrigatorioStatus_Selecao'];
	$local_Calculavel_Selecao				= $_POST['Calculavel_Selecao'];
	$local_Visivel_Selecao					= trim($_POST['Visivel_Selecao']);
	$local_VisivelOS_Selecao				= trim($_POST['VisivelOS_Selecao']);
	$local_VisivelCDA_Selecao				= trim($_POST['VisivelCDA_Selecao']);
	$local_AcessoCDA_Selecao				= trim($_POST['AcessoCDA_Selecao']);
	$local_Unico_Selecao					= trim($_POST['Unico_Selecao']);
	$local_ParametroDemonstrativo_Selecao	= trim($_POST['ParametroDemonstrativo_Selecao']);
	$local_CalculavelOpcoes					= $_POST['CalculavelOpcoes'];
	$local_RotinaCalculo					= trim($_POST['RotinaCalculo']);
	$local_RotinaOpcoes						= trim($_POST['RotinaOpcoes']);
	$local_RotinaOpcoesContrato				= trim($_POST['RotinaOpcoesContrato']);
	$local_TamMinimo						= trim($_POST['TamMinimo']);
	$local_TamMaximo						= trim($_POST['TamMaximo']);
	$local_DescricaoParametroServicoCDA		= trim($_POST['DescricaoParametroServicoCDA']);
	$local_IdTipoTexto						= trim($_POST['IdTipoTexto']);
	$local_Obs								= trim($_POST['Obs']);
	$local_IdGruposUsuario					= trim($_POST['IdGruposUsuario']);
	$local_IdTipoAcesso						= trim($_POST['IdTipoAcesso']);
	$local_SalvarHistorico					= trim($_POST['SalvarHistorico']);
	
	if($_GET['IdServico']!=''){
		$local_IdServico	= $_GET['IdServico'];	
	}
	if($_GET['IdParametroServico']!=''){
		$local_IdParametroServico	= $_GET['IdParametroServico'];	
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_servico_parametro.php');
			break;		
		case 'alterar':
			include('files/editar/editar_servico_parametro.php');
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
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico_parametro.js'></script>
		<script type = 'text/javascript' src = 'js/servico_parametro_default.js'></script>
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
	<body onLoad="ativaNome('<?=dicionario(588)?>')">
		<? include('filtro_servico_parametro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico_parametro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='ServicoParametro'>
				<input type='hidden' name='IdParametroServicoTemp' value='<?=$local_IdParametroServico?>'>
				<input type='hidden' name='IdGruposUsuario' value=''>
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
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100' readOnly>
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
				<div id='cpDadosParametros'>
					<div id='cp_tit'><?=dicionario(589)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(275)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdParametroServico' value='' autocomplete="off" style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="busca_servico_parametro(document.formulario.IdServico.value,'false',document.formulario.Local.value,document.formulario.IdParametroServico.value)" tabindex='2'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(590)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(935)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(941)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(591)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(140)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoParametroServico' value='' style='width:312px' maxlength='100' onkeypress="mascara(this,event,'charVal');" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoAcesso' style='width:118px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=263 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdTipoAcesso,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='SalvarHistorico' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=264 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdTipoAcesso,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoParametro' style='width:138px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' onChange="verificaTipoParametro(this.value)">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=74 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusParametro' style='width:104px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=79 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<div id='tableTexto'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(592)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(593)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?php echo dicionario(972); ?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(594)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(595)?>.</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='Editavel_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' onChange="verificaEditavel(this)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='hidden' name='ObrigatorioStatus_Texto' value=''>
									<select name='Obrigatorio_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9' OnChange="document.formulario.ObrigatorioStatus_Texto.value=document.formulario.Obrigatorio_Texto.value;">
										<option value='' selected></option>
										<?
											$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=5 order by ValorCodigoInterno";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='BotaoAuxiliar_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=270 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='ParametroDemonstrativo_Texto' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=54 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Calculavel_Texto' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12' onChange="ativarRotina(this.value)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=45 order by ValorParametroSistema";
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
								<td class='descCampo'><B><?=dicionario(596)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(597)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(598)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(599)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(600)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='Visivel_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=77 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='VisivelOS_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=55 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='VisivelCDA_Texto' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15' onChange="verificaTipoParametroCDA(this.value)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=121 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='AcessoCDA_Texto' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='16'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=222 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Unico_Texto' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='17'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=196 order by ValorParametroSistema";
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
					<div id='tableSelecao' style='display:none'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(592)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(593)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(594)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(595)?>.</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(601)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='Editavel_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18' onChange="verificaEditavel(this)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='hidden' name='ObrigatorioStatus_Selecao' value=''>
									<select name='Obrigatorio_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19' OnChange="document.formulario.ObrigatorioStatus_Selecao.value=document.formulario.Obrigatorio_Selecao.value;">
										<option value='' selected></option>
										<?
											$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=5 order by ValorCodigoInterno";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='ParametroDemonstrativo_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=54 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Calculavel_Selecao' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21' onChange="ativarRotina(this.value)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=45 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='CalculavelOpcoes' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22' onChange="ativarRotinaOpcoes(this.value)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=90 order by ValorParametroSistema";
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
								<td class='descCampo'><B><?=dicionario(596)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(597)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(598)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(599)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(600)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='Visivel_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='23'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=77 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='VisivelOS_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='24'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=55 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='VisivelCDA_Selecao' style='width:156px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='25' onChange="verificaTipoParametroCDA(this.value)">
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=121 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='AcessoCDA_Selecao' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='26'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=222 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Unico_Selecao' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='27'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=196 order by ValorParametroSistema";
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
					<table id='tableCampoTexto' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(602)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(603)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(604)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; display:none' id='titMascara'><?=dicionario(605)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoTexto' style='width:238px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='28' onChange="atualizaTipoTexto(this.value)">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=122 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>	
								<input type='text' name='TamMinimo' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='29'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>	
								<input type='text' name='TamMaximo' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='30'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdMascaraCampo' id='cpMascara' style='width:239px; display:none' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='31' onChange="atualizaCampo(this)">
									<option value='' selected></option>
								</select>
							</td>
						</tr>
					</table>
					<table id='cpDescricaoCDA'  style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(606)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoParametroServicoCDA' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='32'>
							</td>
						</tr>
					</table>
					<table id='cpOpcaoValor' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(607)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='OpcaoValor' style='width: 816px' rows=5 onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='33' onChange="busca_opcao_valor(this.value,'')"></textarea>
							</td>
						</tr>
					</table>
					<table id='cpRotinaOpcoes' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(608)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RotinaOpcoes' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='34' OnChange="busca_rotina_opcoes(this.value,'')">
							</td>
						</tr>
                        <tr>
							<td class='find'>&nbsp;</td>
							<td>Ex: URL ou $[ci(10000,1):2] ou $[ps(10000,1):2]</td>
						</tr>
					</table>
					<table id='cpValorInput'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(609)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDefaultInput' value='' style='width:816px' maxlength='255' onkeypress="chama_mascara(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='35'>
							</td>
						</tr>
					</table>
					<table id='cpValorSelect' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(609)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='ValorDefaultSelect' style='width:822px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='36'>
									<option value='' selected></option>
								</select>
							</td>
						</tr>
					</table>
					<table id='cpRotinaOpcoesContrato' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(610)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RotinaOpcoesContrato' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='37'>
							</td>
						</tr>
                        <tr>
							<td class='find'>&nbsp;</td>
							<td>Ex: URL ou $[ci(10000,1):2] ou $[ps(10000,1):2]</td>
						</tr>
					</table>
					<table id='cpRotinaCalculo' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(611)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RotinaCalculo' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='38'>
							</td>
						</tr>
                        <tr>
							<td class='find'>&nbsp;</td>
							<td>Ex: URL ou $[ci(10000,1):2] ou $[ps(10000,1):2]</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(612)?></div>
					<table id='cpValorInput'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(613)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoUsuario', true, event, null, 94);"></td>
							<td class='campo'>
								<input type='text' name='IdGrupoUsuarioInput' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_grupo_usuario(this.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='39'><input class='agrupador' type='text' name='DescricaoGrupoUsuarioInput' value='' style='width:641px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add_status' value='Adicionar' class='botao' style='width:84px;' tabindex='40' onClick="add_grupo_usuario(document.formulario.IdGrupoUsuarioInput.value,'')">
							</td>
						</tr>
					</table>
					<table id='cpValorSelect' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(613)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoUsuario', true, event, null, 94);"></td>
							<td class='campo'>
								<input type='text' name='IdGrupoUsuarioSelect' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_grupo_usuario(this.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='41'><input class='agrupador' type='text' name='DescricaoGrupoUsuarioSelect' value='' style='width:736px' maxlength='36' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add_status' value='Adicionar' class='botao' style='width:84px;' tabindex='42' onClick="add_grupo_usuario(document.formulario.IdGrupoUsuarioSelect.value,'')">
							</td>
						</tr>
					</table>
					<table id='tabelaGrupoUsuario' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:60px'><?=dicionario(141)?></td>
							<td><?=dicionario(75)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='totaltabelaGrupoUsuario'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(159)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width: 816px' rows=5 onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='43' /></textarea>
							</td>
						</tr>
					</table>
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
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='44' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='45' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='46' onClick="excluir(document.formulario.IdServico.value,document.formulario.IdParametroServico.value)">
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
				<div id='cpParametrosCadastrados'  style='margin-top:10px'>
					<div id='cp_tit' style='margin:0'><?=dicionario(614)?></div>	
					<table class='tableListarCad' Id='tabelaParametro' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 100px'><?=dicionario(275)?></td>
							<td><?=dicionario(125)?></td>
							<td><?=dicionario(609)?></td>
							<td><?=dicionario(592)?></td>
							<td><?=dicionario(593)?></td>
							<td><?=dicionario(140)?></td>
							<td style='width: 20px'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaParametroTotal'><?=dicionario(128)?>: 0</td>
							<td colspan ='5' >&nbsp;</td>
						</tr>
					</table>
				</div>
				<table style='display:none' id='tabelahelpText2'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
					</tr>
				</table>	
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/servico.php");
				include("files/busca/grupo_usuario.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdServico!=""){
			echo "busca_servico($local_IdServico,false);";	
		}
	?>
	function status_inicial(){
		if(document.formulario.SalvarHistorico.value == '' || document.formulario.SalvarHistorico.value == 0){
			document.formulario.SalvarHistorico.value = '<?=getCodigoInterno(3,191)?>';
		}
		if(document.formulario.IdStatusParametro.value == '' || document.formulario.IdStatusParametro.value == 0){
			document.formulario.IdStatusParametro.value = '<?=getCodigoInterno(3,4)?>';
		}
		if(document.formulario.Editavel_Texto.value == '' || document.formulario.Editavel_Texto.value == 0){
			document.formulario.Editavel_Texto.value = '<?=getCodigoInterno(3,19)?>';
		}
		if(document.formulario.Editavel_Selecao.value == '' || document.formulario.Editavel_Selecao.value == 0){
			document.formulario.Editavel_Selecao.value = '<?=getCodigoInterno(3,19)?>';
		}
		if(document.formulario.Obrigatorio_Texto.value == '' || document.formulario.Obrigatorio_Texto.value == 0){
			document.formulario.Obrigatorio_Texto.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.Obrigatorio_Selecao.value == '' || document.formulario.Obrigatorio_Selecao.value == 0){
			document.formulario.Obrigatorio_Selecao.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.ObrigatorioStatus_Texto.value == '' || document.formulario.ObrigatorioStatus_Texto.value == 0){
			document.formulario.ObrigatorioStatus_Texto.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.ObrigatorioStatus_Selecao.value == '' || document.formulario.ObrigatorioStatus_Selecao.value == 0){
			document.formulario.ObrigatorioStatus_Selecao.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.ParametroDemonstrativo_Texto.value == '' || document.formulario.ParametroDemonstrativo_Texto.value == 0){
			document.formulario.ParametroDemonstrativo_Texto.value = '<?=getCodigoInterno(3,37)?>';
		}
		if(document.formulario.ParametroDemonstrativo_Selecao.value == '' || document.formulario.ParametroDemonstrativo_Selecao.value == 0){
			document.formulario.ParametroDemonstrativo_Selecao.value = '<?=getCodigoInterno(3,37)?>';
		}
		if(document.formulario.Calculavel_Texto.value == '' || document.formulario.Calculavel_Texto.value == 0){
			document.formulario.Calculavel_Texto.value = '<?=getCodigoInterno(3,29)?>';
		}
		if(document.formulario.Calculavel_Selecao.value == '' || document.formulario.Calculavel_Selecao.value == 0){
			document.formulario.Calculavel_Selecao.value = '<?=getCodigoInterno(3,29)?>';
		}
		if(document.formulario.CalculavelOpcoes.value == '' || document.formulario.CalculavelOpcoes.value == 0){
			document.formulario.CalculavelOpcoes.value = '<?=getCodigoInterno(3,58)?>';
		}
		if(document.formulario.Visivel_Texto.value == '' || document.formulario.Visivel_Texto.value == 0){
			document.formulario.Visivel_Texto.value = '<?=getCodigoInterno(3,55)?>';
		}
		if(document.formulario.Visivel_Selecao.value == '' || document.formulario.Visivel_Selecao.value == 0){
			document.formulario.Visivel_Selecao.value = '<?=getCodigoInterno(3,55)?>';
		}
		if(document.formulario.VisivelOS_Texto.value == '' || document.formulario.VisivelOS_Texto.value == 0){
			document.formulario.VisivelOS_Texto.value = '<?=getCodigoInterno(3,38)?>';
		}
		if(document.formulario.VisivelOS_Selecao.value == '' || document.formulario.VisivelOS_Selecao.value == 0){
			document.formulario.VisivelOS_Selecao.value = '<?=getCodigoInterno(3,38)?>';
		}
		if(document.formulario.VisivelCDA_Texto.value == '' || document.formulario.VisivelCDA_Texto.value == 0){
			document.formulario.VisivelCDA_Texto.value = '<?=getCodigoInterno(3,105)?>';
			
			verificaTipoParametroCDA(document.formulario.VisivelCDA_Texto.value);
		}
		if(document.formulario.VisivelCDA_Selecao.value == '' || document.formulario.VisivelCDA_Selecao.value == 0){
			document.formulario.VisivelCDA_Selecao.value = '<?=getCodigoInterno(3,105)?>';
			
			verificaTipoParametroCDA(document.formulario.VisivelCDA_Selecao.value);
		}	
		if(document.formulario.Unico_Texto.value == '' || document.formulario.Unico_Texto.value == 0){
			document.formulario.Unico_Texto.value = '<?=getCodigoInterno(3,147)?>';
		}
		if(document.formulario.Unico_Selecao.value == '' || document.formulario.Unico_Selecao.value == 0){
			document.formulario.Unico_Selecao.value = '<?=getCodigoInterno(3,147)?>';
		}	
		if(document.formulario.AcessoCDA_Texto.value == '' || document.formulario.AcessoCDA_Texto.value == 0){
			document.formulario.AcessoCDA_Texto.value = '<?=getCodigoInterno(3,166)?>';
		}
		if(document.formulario.AcessoCDA_Selecao.value == '' || document.formulario.AcessoCDA_Selecao.value == 0){
			document.formulario.AcessoCDA_Selecao.value = '<?=getCodigoInterno(3,166)?>';
		}	
	}
	verificaAcao();
	verificaErro();
	inicia();
	atualizaCampo(document.formulario.IdMascaraCampo);
	enterAsTab(document.forms.formulario);
</script>

