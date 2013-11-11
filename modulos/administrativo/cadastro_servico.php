<?
	$localModulo		= 1;
	$localOperacao		= 25;
	$localSuboperacao	= "V";	
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdServico					= $_POST['IdServico'];
	$local_IdTipoServico				= $_POST['IdTipoServico'];
	$local_DescricaoServico				= formatText($_POST['DescricaoServico'],NULL);
	$local_DetalheServico				= formatText($_POST['DetalheServico'],NULL);	
	$local_IdServicoGrupo				= $_POST['IdServicoGrupo'];
	$local_IdTipoCobranca				= $_POST['IdTipoCobranca'];
	$local_ContratoViaCDA				= $_POST['ContratoViaCDA'];
	$local_IdStatus						= $_POST['IdStatus'];
	$local_IdPlanoConta					= $_POST['IdPlanoConta'];
	$local_IdCentroCusto				= $_POST['IdCentroCusto'];
	$local_AtivacaoAutomatica			= $_POST['AtivacaoAutomatica'];
	$local_AtivacaoAutomaticaTemp		= $_POST['AtivacaoAutomaticaTemp'];
	$local_ValorInicial					= formatText($_POST['ValorInicial'],NULL);
	$local_ParametroDemonstrativo		= $_POST['ParametroDemonstrativo'];
	$local_IdServicoImportar			= $_POST['IdServicoImportar'];
	$local_ExibirReferencia				= $_POST['ExibirReferencia'];
	$local_Cor							= formatText($_POST['Cor'],NULL);
	$local_Periodicidade				= $_POST['Periodicidade'];
	$local_ServicoAgrupador				= $_POST['ServicoAgrupador'];
	$local_ServicoVinculado				= $_POST['ServicoVinculado'];
	$local_DiasAvisoAposVencimento		= formatText($_POST['DiasAvisoAposVencimento'],NULL);
	$local_DiasLimiteBloqueio			= formatText($_POST['DiasLimiteBloqueio'],NULL);
	$local_Filtro_IdPaisEstadoCidade	= formatText($_POST['Filtro_IdPaisEstadoCidade'],NULL);
	$local_MsgAuxiliarCobranca			= formatText($_POST['MsgAuxiliarCobranca'],NULL);
	$local_ValorRepasseTerceiro			= formatText($_POST['ValorRepasseTerceiro'],NULL);		
	$local_EmailCobranca				= formatText($_POST['EmailCobranca'],NULL);	
	$local_EmailCobrancaTemp			= formatText($_POST['EmailCobrancaTemp'],NULL);
	$local_ExecutarRotinas				= formatText($_POST['ExecutarRotinas'],NULL);
	$local_DetalheDemonstrativoTerceiro	= formatText($_POST['DetalheDemonstrativoTerceiro'],NULL);
	$local_IdOrdemServicoLayout			= formatText($_POST['IdOrdemServicoLayout'],NULL);
	$local_IdTipoPessoa					= formatText($_POST['IdTipoPessoa'],NULL);
	$local_Unidade						= formatText($_POST['Unidade'],NULL);
	$local_IdNotaFiscalTipo				= $_POST['IdNotaFiscalTipo'];
	$local_IdCategoriaTributaria		= $_POST['IdCategoriaTributaria'];
	$local_IdFaturamentoFracionado		= $_POST['IdFaturamentoFracionado'];	
	$local_ImportarParametro			= $_POST['ImportarParametro'];	
	$local_ImportarMascaraVigencia		= $_POST['ImportarMascaraVigencia'];	
	$local_ImportarRotina				= $_POST['ImportarRotina'];	
	$local_ImportarAliquota				= $_POST['ImportarAliquota'];
	$local_ImportarParametroNF			= $_POST['ImportarParametroNF'];
	$local_ImportarCFOP					= $_POST['ImportarCFOP'];
	$local_ImportarAgendamento			= $_POST['ImportarAgendamento'];
	$local_IdTecnologia					= $_POST['IdTecnologia'];
	$local_IdDedicado					= $_POST['IdDedicado'];
	$local_FatorMega					= $_POST['FatorMega'];
	$local_IdGrupoVelocidade			= $_POST['IdGrupoVelocidade'];
	$local_SICIAtivoDefault				= $_POST['SICIAtivoDefault'];
	$local_Terceiros					= $_POST['Terceiros'];
	$local_ColetarSICI					= $_POST['ColetarSICI'];	
	$local_DescricaoServicoSMS			= formatText($_POST['DescricaoServicoSMS'],NULL);
	/*
	 * Variaver recebe um array com grupos device para ser
	 * inseridos em um servico
	 */
	$local_GrupoDevice 						= $_POST['device'];
	/*
	 * variavel recebe um array com grupos device para ser
	 * removido de um servico
	 */
	$local_removeGrupoDevice				= $_POST['removeDevice'];
	
	if($_GET['IdServico'] != '') {
		$local_IdServico = $_GET['IdServico'];	
	}
	
	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_servico.php');
			break;		
		case 'alterar':
			include('files/editar/editar_servico.php');
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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/terceiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_grupo_default.js'></script>
		<script type = 'text/javascript' src = 'js/plano_conta_default.js'></script>
		<script type = 'text/javascript' src = 'js/centro_custo_default.js'></script>
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
			
			.esconde {
				display: none;
			}
			#mask {
				position: absolute;
				left: 0;
				top: 0;
				z-index: 0000;
				
				display: none;
			}
			#quadros_fluantes #dialog {
				position: absolute;
				left:0;
				top:0;
				width:440px;
				height:200px;
				z-index:9999;
			}
			.close {
				display: block;
				text-align: right;
			}
			
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(30)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Servico'>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='QuantPeriodicidade' value=''>
				<input type='hidden' name='ServicoAgrupador' value=''>
				<input type='hidden' name='ServicoVinculado' value=''>
				<input type='hidden' name='Filtro_IdPaisEstadoCidade' value=''>
				<input type='hidden' name='AtivacaoAutomaticaTemp' value=''>
				<input type='hidden' name='EmailCobrancaTemp' value=''>
				<input type='hidden' name='Terceiros' value=''>
				<input type='hidden' name='FaturamentoFracionadoDefault' value='<?=getCodigoInterno(3, 132);?>'>
				<input type='hidden' name='SICIAtivoDefault' value='<?=getCodigoInterno(37, 1);?>'>
				<input type='hidden' name='IdServicoImportar_Obrigatorio' value='<?=getCodigoInterno(11,8)?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(30)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(436)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificaTipoServico(this.value); limpar_servico_vinculado();" tabindex='2'>
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
				<div id='cpDadosServico'>
					<div id='cp_tit'><?=dicionario(435)?></div>
					<table style='width:851px'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(223)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(526)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(527)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(528)?></B></td>
							<td class='descCampo'><span id='tit_cor' style='display:none;'><?=dicionario(529)?></span></td>
							<td class='descCampo' />
							<td class='descCampo'><B><?=dicionario(140)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoServico' value='' style='width:412px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ContratoViaCDA' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="return altera_Url(this)" tabindex='4'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 186 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Unidade' style='width:60px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=66 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='vertical-align:top;'>
								<select name='ExibirReferencia' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' tabindex='6'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=42 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo'>
								<input type='text' name='Cor' value='' autocomplete="off" style='width:66px; display:none;' maxlength='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
							</td>
							<td class='campo' rowspan='2'><img id='ico_cor' src='../../img/estrutura_sistema/cores.gif' style='display:none; margin-right:8px;' alt='Alterar Cor' title='Alterar Cor' onClick="vi_id('quadroBuscaCor', true, event, null, 100);"></td>
							<td class='campo'>
								<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' tabindex='8'>
									<option value='0' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=17 order by ValorParametroSistema";
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
							<td class='descCampo'><?=dicionario(530)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DetalheServico' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(367)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(531)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' colspan='6'><?=dicionario(677)?></td>
						</tr>
						<tr>
							<td class='find' style='vertical-align:top;'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' style='margin-top:4px;' onClick="vi_id('quadroBuscaServicoGrupo', true, event, null, 320); document.formularioServicoGrupo.DescricaoServicoGrupo.value=''; valorCampoServicoGrupo = ''; busca_servico_grupo_lista(); document.formularioServicoGrupo.DescricaoServicoGrupo.focus();"></td>
							<td class='campo' style='vertical-align:top;'>
								<input type='text' name='IdServicoGrupo' value='' style='width:70px' maxlength='11' onChange='busca_servico_grupo(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'><input class='agrupador' type='text' name='DescricaoServicoGrupo' value='' style='width:263px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='vertical-align:top;'>
								<select name='DetalheDemonstrativoTerceiro' style='width:100px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='11'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=93 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='Campo' colspan='6'>
								<input type='text' name='DescricaoServicoSMS' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12' style='width: 350px' maxlength='30' />
							</td>
						</tr>
					</table>
					<div id="tb_FaturamentoFracionado">
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='setOrdemServicoLayout'><?=dicionario(532)?></B></td>
								<td class='descCampo'><B id='setFaturamentoFracionado'><?=dicionario(533)?></B></td>
							</tr>
							<tr>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='vertical-align:top;'>
									<select name='IdOrdemServicoLayout' style='width:344px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'>
										<option value='' selected></option>
										<?
											$sql = "select IdOrdemServicoLayout, DescricaoOrdemServicoLayout from OrdemServicoLayout where 1 order by DescricaoOrdemServicoLayout ASC";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdOrdemServicoLayout]'>$lin[DescricaoOrdemServicoLayout]</option>";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<select name='IdFaturamentoFracionado' style='width:342px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 172;";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
									<span id="descIdFaturamentoFracionado"><?=dicionario(534)?> (<?=dicionario(535)?>)</span>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id='cpDadosServicoImportacaoParametros'>
					<div id='cp_tit'><?=dicionario(536)?></div>
					<table id='cpServicoImportar'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><span id='cpIdServicoImportar'><?=dicionario(537)?></span></td>						
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 370);"></td>
							<td class='campo'>
								<input type='text' name='IdServicoImportar' value=''  style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'int')" onChange="busca_servico(this.value,false,'ServicoImportar')" tabindex='15'><input type='text' class='agrupador' name='DescricaoServicoImportar' value='' style='width:741px' maxlength='100' readOnly>
							</td>							
						</tr>									
					</table>
					<table id='cpServicoImportarOpcoes'>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><B><?=dicionario(538)?></B></td>
							<td class='separador'>&nbsp;</td>							
							<td class='descCampo'><B><?=dicionario(275)?></B></td>
							<td class='separador'>&nbsp;</td>							
							<td class='descCampo'><B><?=dicionario(539)?></B></td>
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B><?=dicionario(540)?></B></td>
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B><?=dicionario(541)?></B></td>
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B><?=dicionario(247)?></B></td>
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B><?=dicionario(542)?></B></td>
							<td class='separador'>&nbsp;</td>	
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarMascaraVigencia' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='16'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 184;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>						
							<td class='separador'>&nbsp;</td>
								<td class='campo'>
								<select name='ImportarParametro' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='17'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 178;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarRotina' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 179;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarAliquota' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 180;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select> 
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarParametroNF' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 181;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarCFOP' style='width:107px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 182;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ImportarAgendamento' style='width:108px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 183;";
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
				<div id="cpServicoVinculado" style="display:none;">
					<div id="cp_tit" name="titServicoVinculado"><?=dicionario(543)?></div>
					<div id="ServicoVinculadoLista" style='display:none;'>
						<table id='tableServicoVinculado' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width: 40px'><?=dicionario(141)?></td>
								<td><?=dicionario(223)?></td>
								<td><?=dicionario(436)?></td>
								<td><?=dicionario(367)?></td>
								<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='4' id='tableServicoVinculadoTotal'><?=dicionario(128)?>: 0</td>
								<td class='valor' id='tableServicoVinculadoValorTotal'>0,00</td>
								<td />
							</tr>
						</table>
					</div>
				</div>
				<div id='cpFinanceiro'>
					<div id='cp_tit'><?=dicionario(544)?></div>
					<table cellpading='0' cellspacing='0'>
						<tr>
							<td valign='top'> 
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(545)?></td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdNotaFiscalTipo' style='width:415px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='mudarNotaFicalTipo(this.value)' tabindex='23'>
												<option value='0'>&nbsp;</option>
												<?
													$sql = "select
																NotaFiscalTipo.IdNotaFiscalTipo,
																NotaFiscalLayout.DescricaoNotaFiscalLayout
															from
																NotaFiscalLayout,
																NotaFiscalTipo
															where
																NotaFiscalTipo.IdLoja = $local_IdLoja and
																NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout and
																NotaFiscalTipo.IdStatus = 1
															order by
																NotaFiscalLayout.DescricaoNotaFiscalLayout;";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdNotaFiscalTipo]' ".compara($local_IdNotaFiscalTipo,$lin[IdNotaFiscalTipo],"selected", "").">$lin[DescricaoNotaFiscalLayout]</option>";
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(44)?></B></td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaCentroCusto', true, event, null, 442); document.formularioCentroCusto.DescricaoCentroCusto.value=''; valorCampoCentroCusto = ''; busca_centro_custo_lista(); document.formularioCentroCusto.DescricaoCentroCusto.focus();"></td>
										<td class='campo'>
											<input type='text' name='IdCentroCusto' value='' style='width:70px' maxlength='11' onChange='busca_centro_custo(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='24'><input class='agrupador' type='text' name='DescricaoCentroCusto' value='' style='width:333px' readOnly>
										</td>
									</tr>
								</table>
								<table style='margin-top: -7px;'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(46)?></B></td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPlanoConta', true, event, null, 442); limpa_form_plano_conta(); busca_plano_conta_lista(); document.formularioPlanoConta.IdPlanoConta.focus();"></td>
										<td class='campo'>
											<input type='text' name='IdPlanoConta' value='' style='width:110px' maxlength='34' onChange="busca_plano_conta(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'PlanoConta')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='25'><input type='text' class='agrupador' name='DescricaoPlanoConta' value='' style='width:293px' maxlength='100' readOnly>
										</td>
									</tr>
								</table>
							</td>
							<td valign='top'>
								<table cellpading='0' cellspacing='1'>
									<tr>
										<td class='find'></td>
										<td class='descCampo'><B id='tit_CategoriaTributaria' style='display: none;'><?=dicionario(546)?></B></td>
									</tr>
									<tr>
										<td class='find'></td>
										<td class='campo'>
											<select name='IdCategoriaTributaria' id='cp_CategoriaTributaria' style='width:383px; display: none;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='26'>
												<option value='0'>&nbsp;</option>
												<?
													$sql = "select
																ParametroSistema.IdParametroSistema,
																ParametroSistema.ValorParametroSistema
															from
																ParametroSistema
															where
																ParametroSistema.IdGrupoParametroSistema = 159
															order by
																ParametroSistema.ValorParametroSistema;";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdCategoriaTributaria,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
									</tr>
								</table>
								<table cellpading='0' cellspacing='1'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(547)?></td>				
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<textarea name='MsgAuxiliarCobranca' style='width:377px; height:98px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='27'></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>			
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorInicial'><?=dicionario(237)?></B><B id='cpValorInicialMoeda'> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titAtivacaoAutomatica'><?=dicionario(548)?>.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titEmailCobranca'><?=dicionario(549)?></B></td>	
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B id='titRotinasDiarias'><?=dicionario(550)?></B></td>
							<td class='separador' id='sepDiasAviso'>&nbsp;</td>
							<td class='descCampo' id='titDiasAviso'><?=dicionario(551)?>.</td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(552)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorInicial' value='' style='width:158px' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_percentual(document.formulario.ValorRepasseTerceiro);"  tabindex='28'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='AtivacaoAutomatica' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:105px' tabindex='29' onchange="document.formulario.AtivacaoAutomaticaTemp.value=this.value">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=42 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='EmailCobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' tabindex='30' onchange="document.formulario.EmailCobrancaTemp.value=this.value">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=86 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>										
							<td class='separador'>&nbsp;</td>		
							<td class='campo' valign='top'>
								<select name='ExecutarRotinas' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' tabindex='31'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=87 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador' id='sep2DiasAviso'>&nbsp;</td>
							<td class='campo' id='cpDiasAviso' valign='top'>
								<input type='text' name='DiasAvisoAposVencimento' value='' style='width:143px' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')"  tabindex='32'><BR /><?=dicionario(554)?>: 5,10,15 <?=dicionario(553)?> 'X' (<?=dicionario(153)?>)
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='DiasLimiteBloqueio' value='' style='width:92px' maxlength='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='33'>
							</td>
						</tr>
					</table>	
				</div>
				<div id='cpPeriodicidade' style='display:none'>
					<div id='cp_tit'><?=dicionario(224)?></div>
					<table id='titTabelaPeriodicidade'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(225)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(82)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(40)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(227)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(243)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPeriodicidade' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:140px' tabindex='34' onChange="periodicidade_parcelas(this.value)">
									<option value='' selected></option>
									<?
										$sql = "select IdPeriodicidade, DescricaoPeriodicidade from Periodicidade where IdLoja = $local_IdLoja and Ativo=1 order by DescricaoPeriodicidade";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdPeriodicidade]'>$lin[DescricaoPeriodicidade]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='QtdParcelaMaximo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:90px' tabindex='35'>
									<option value='0' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:110px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='36'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:120px' onBlur="Foco(this,'out')" tabindex='37'>
									<option value='0'>&nbsp;</option>
										<?
											$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobrancaGeracao where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdLocalCobranca]' ".compara($local_IdLocalCobranca,$lin[IdLocalCobranca],"selected", "").">$lin[AbreviacaoNomeLocalCobranca]</option>";
											}
										?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='MesFechado' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='38'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=70 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdMesesFidelidade' value='' style='width:125px' maxlength='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='39'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='40' onClick='adicionar_periodicidade(document.formulario.IdPeriodicidade.value,document.formulario.QtdParcelaMaximo.value,document.formulario.TipoContrato.value,document.formulario.IdLocalCobranca.value,document.formulario.MesFechado.value,document.formulario.QtdMesesFidelidade.value,document.formulario.ValorInicial.value)'>
							</td>
						</tr>
					</table>
					<table id='tabelaPeriodicidade' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(224)?></td>
							<td><?=dicionario(225)?></td>
							<td><?=dicionario(82)?></td>
							<td><?=dicionario(40)?></td>
							<td><?=dicionario(227)?></td>
							<td><?=dicionario(243)?></td>
							<td class='valor'><?=dicionario(240)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='8' id='totaltabelaPeriodicidade'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cpAgrupado' style='display:none'>
					<div id='cp_tit'><B id='titServicoAgrupado'><?=dicionario(555)?></B></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(30)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServicoAgrupador', true, event, null, 440);"></td>
							<td class='campo'>
								<input type='text' name='IdServicoAgrupador' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_servico(this.value,true,'ServicoAgrupador')" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='41'><input class='agrupador' type='text' name='DescricaoServicoAgrupador' value='' style='width:300px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add' value='Adicionar' class='botao' tabindex='42' onClick='adicionar_servico(document.formulario.IdServicoAgrupador.value)'>
							</td>
						</tr>
					</table>
					<table id='tabelaServico' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(223)?></td>
							<td><?=dicionario(436)?></td>
							<td class='valor'><?=dicionario(204)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='totaltabelaServico'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cpTerceiro'>
					<div id='cp_tit'><?=dicionario(33)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(33)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(556)?></td>
							<td class='descCampo'><span id='cpValorRepasseMensal' style='margin-left:9px; display:none;'><?=dicionario(557)?> (<?=getParametroSistema(5,1)?>)</span></td>
							<td class='descCampo'><span id='cpPercRepasseMensal' style='margin-left:7px; display:none;'><?=dicionario(521)?> (%)</span></td>
							<td class='descCampo'><span id='cpPercRepasseMensalOutros' style='margin-left:9px; display:none;'><?=dicionario(558)?> (%)</span></td>
							<td colspan='2' />
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaTerceiro', true, event, null, 440); limpa_form_terceiro(); busca_terceiro_lista(); document.formularioTerceiro.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdTerceiro' value='' style='width:70px' maxlength='11' onChange='busca_terceiro(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='43'><input class='agrupador' type='text' name='NomeTerceiro' value='' style='width:254px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdRepasse' style='width:107px;' onchange="verificarRepasse(this.value);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='44'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 197 order by ValorParametroSistema ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiro' id='cpValorRepasseTerceiro' value='' style='width:150px; margin-left:9px; display:none;' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_percentual(this)" tabindex='45'>
							</td>
							<td class='campo'>
								<input type='text' name='PercentualRepasseTerceiro' value='' style='width:120px; margin-left:7px; display:none;' maxlength='8' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_percentual(this)" tabindex='46'>
							</td>
							<td class='campo'>
								<input type='text' name='PercentualRepasseTerceiroOutros' value='' style='width:126px; display:none; margin-left:9px;' maxlength='8' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_percentual(this)" tabindex='47'>
							</td>
							<td class='campo'>
								<input type='button' name='bt_add_terceiro' value='Adicionar' class='botao' style='width:79px; margin-left:4px;' tabindex='48' onClick='adicionar_terceiro(false,undefined,document.formulario.IdTerceiro.value);'>
							</td>
						</tr>
					</table>
					<table id='tabelaTerceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(33)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(521)?> (%)</td>
							<td class='valor'><?=dicionario(558)?> (%)</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='8' id='totaltabelaTerceiro'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cpDevice'>
					<div id='cp_tit'>Device</div>
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo">Device</td>
						</tr>
						<tr>
							<td class='find'><img id="#dialog" alt="Buscar" src="../../img/estrutura_sistema/ico_lupa.gif" name="modal"></td>
							<td class='campo'>
								<input type='text' id='IdGrupoDevice' name='IdGrupoDevice' value='' style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='43'><input class='agrupador' type='text' id='DescricaoGrupoDevice' name='DescricaoGrupoDevice' value='' style='width:254px' readOnly>
								<input type="hidden" id="IdLoja"  value="<?php echo $local_IdLoja;?>"/>
								<input type="hidden" id="Id_Servico"  value="<?php echo $local_IdServico;?>"/>
							</td>
							<td class='campo'>
								<input type='button' id="bt_add_device" name='bt_add_device' value='Adicionar' class='botao' style='width:79px; margin-left:4px;' tabindex='48'>
							</td>
						</tr>
					</table>
					<table id='tabelaDevice' class="tableListarCad" cellspacing="0">
						<tr id="listaDevice" class="tableListarTitleCad">
							<td class="tableListarEspaco">Id</td>
							<td>Grupo Device</td>
							<td>QTD. Device</td>
							<td></td>
						</tr>
						<tr class="tableListarTitleCad">
							<td class="tableListarEspaco" colspan="4">Total: 0</td>
						</tr>
					</table>
				</div>
				<div>
					<div class='cp_tit' id='Filtros'><?=dicionario(559)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(112)?></td>
						</tr>
						<tr>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoPessoa' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:822px' tabindex='49'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table id='titTabelaCidade' style='margin:10px 0 5px 0'>						
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' colspan='9'><?=dicionario(560)?> [<a href="#" onClick="vi_id('quadroBuscaCidade', true, event, null, 300);" id='FiltroCidade' tabindex='50'>+</a>]</td>
						</tr>
					</table>
					<table id='tabelaCidade' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:60px'>&nbsp;</td>
							<td><?=dicionario(257)?></td>
							<td><?=dicionario(259)?></td>
							<td><?=dicionario(260)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='totaltabelaCidade'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>
				<div id='cp_SICI' style='display:none;'>
					<div id='cp_tit'><?=dicionario(561)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(932)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(562)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(563)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(564)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(565)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='ColetarSICI' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificarColetaSICI();" style='width:111px' tabindex='51'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=261 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select><br />&nbsp;
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTecnologia' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificar_Sici(this,document.formulario.IdDedicado)" style='width:111px' tabindex='51'>
									<option value='' selected></option>
									<?
										$sql = "SELECT IdTecnologia,DescricaoTecnologia FROM SICITecnologia ORDER BY DescricaoTecnologia ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdTecnologia]'>$lin[DescricaoTecnologia]</option>";
										}
									?>
								</select><br />&nbsp;
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdDedicado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:111px' tabindex='52' onChange="verificar_Sici(this,document.formulario.FatorMega)">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=191 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select><br />&nbsp;
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FatorMega' value='' autocomplete="off" style='width:121px' maxlength='12' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onChange="verificar_Sici(this,document.formulario.IdGrupoVelocidade)"  onBlur="Foco(this,'out')" tabindex='53'><br />
								<?=dicionario(554)?>: 512<?=dicionario(566)?> = 0,50<?=dicionario(567)?>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoVelocidade' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' tabindex='54'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=190 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select><br />&nbsp;
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table id='cpHistorico' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(130)?> [<a style='cursor:pointer' onClick="atualizarHistorico(document.formulario.IdServico.value);"><?=dicionario(264)?></a>]</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  readOnly></textarea>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
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
					<table style="width:100%; text-align:right;">
						<tr>
							<td class='campo' style="padding-right:5px;">
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='55' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='56' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='57' onClick="excluir(document.formulario.IdServico.value)">
							</td>
						</tr>
					</table>
					<table style="width:100%;">
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
				include("files/busca/servico_grupo.php");
				include("files/busca/servico.php");
				include("files/busca/centro_custo.php");
				include("files/busca/plano_conta.php");
				include("files/busca/terceiro.php");
				include("files/busca/servico_agrupador.php");
				include("files/busca/cidade2.php");
				include("files/busca/cores.php");
				include("files/busca/grupo_device.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdServico != ""){
			echo "busca_servico($local_IdServico,false);";
			echo "scrollWindow('bottom');";
		} else{
			echo "verificaTipoServico('');";
		}
	?>
	
	function status_inicial(){
		if(document.formulario.AtivacaoAutomatica.value == '' || document.formulario.AtivacaoAutomatica.value == 0){
			document.formulario.AtivacaoAutomatica.value = '<?=getCodigoInterno(3,16)?>';
			document.formulario.AtivacaoAutomaticaTemp.value = '<?=getCodigoInterno(3,16)?>';
		}
		if(document.formulario.IdStatus.value == '' || document.formulario.IdStatus.value == 0){
			document.formulario.IdStatus.value = '<?=getCodigoInterno(3,4)?>';
		}
		if(document.formulario.ExibirReferencia.value == '' || document.formulario.ExibirReferencia.value == 0){
			document.formulario.ExibirReferencia.value = '<?=getCodigoInterno(3,31)?>';
		}	
		if(document.formulario.EmailCobranca.value == '' || document.formulario.EmailCobranca.value == 0){
			document.formulario.EmailCobranca.value = '<?=getCodigoInterno(3,56)?>';
			document.formulario.EmailCobrancaTemp.value = '<?=getCodigoInterno(3,56)?>';
		}	
		if(document.formulario.ExecutarRotinas.value == '' || document.formulario.ExecutarRotinas.value == 0){
			document.formulario.ExecutarRotinas.value = '<?=getCodigoInterno(3,57)?>';
		}
		if(document.formulario.DetalheDemonstrativoTerceiro.value == '' || document.formulario.DetalheDemonstrativoTerceiro.value == 0){
			document.formulario.DetalheDemonstrativoTerceiro.value = '<?=getCodigoInterno(3,61)?>';
		}
		if(document.formulario.Unidade.value == '' || document.formulario.Unidade.value == 0){
			document.formulario.Unidade.value = '<?=getCodigoInterno(3,131)?>';			
		}	
		
		if(document.formulario.ImportarParametro.value == '' || document.formulario.ImportarParametro.value == 0){
			document.formulario.ImportarParametro.value = '<?=getCodigoInterno(3,135)?>';			
		}	
		
		if(document.formulario.ImportarMascaraVigencia.value == '' || document.formulario.ImportarMascaraVigencia.value == 0){
			document.formulario.ImportarMascaraVigencia.value = '<?=getCodigoInterno(3,141)?>';			
		}
			    
		if(document.formulario.ImportarRotina.value == '' || document.formulario.ImportarRotina.value == 0){
			document.formulario.ImportarRotina.value = '<?=getCodigoInterno(3,136)?>';			
		}
		
		if(document.formulario.ImportarAliquota.value == '' || document.formulario.ImportarAliquota.value == 0){
			document.formulario.ImportarAliquota.value = '<?=getCodigoInterno(3,137)?>';			
		}
		
		if(document.formulario.ImportarParametroNF.value == '' || document.formulario.ImportarParametroNF.value == 0){
			document.formulario.ImportarParametroNF.value = '<?=getCodigoInterno(3,138)?>';			
		}
		
		if(document.formulario.ImportarCFOP.value == '' || document.formulario.ImportarCFOP.value == 0){
			document.formulario.ImportarCFOP.value = '<?=getCodigoInterno(3,139)?>';			
		}
		
		if(document.formulario.ImportarAgendamento.value == '' || document.formulario.ImportarAgendamento.value == 0){
			document.formulario.ImportarAgendamento.value = '<?=getCodigoInterno(3,140)?>';			
		}
		
		if(document.formulario.ContratoViaCDA.value == '' || document.formulario.ContratoViaCDA.value == 0){
			document.formulario.ContratoViaCDA.value = '<?=getCodigoInterno(3,142)?>';			
		}
		
		if(document.formulario.IdServicoImportar_Obrigatorio.value == 1){
			document.getElementById("cpIdServicoImportar").style.color = "#c10000";
		} else{
			document.getElementById("cpIdServicoImportar").style.color = "#000";
		}
		
		document.formulario.IdRepasse.disabled							=	true;
		document.formulario.ValorRepasseTerceiro.readOnly				=	true;
		document.formulario.PercentualRepasseTerceiro.readOnly			=	true;
		document.formulario.PercentualRepasseTerceiroOutros.readOnly	=	true;
		
		if(document.formulario.PercentualRepasseTerceiro.value == ''){
			document.formulario.PercentualRepasseTerceiro.value = '0,00';
		}
		
		if(document.formulario.PercentualRepasseTerceiroOutros.value == ''){
			document.formulario.PercentualRepasseTerceiroOutros.value = '0,00';
		}
		
		document.formulario.IdOrdemServicoLayout.style.display 					= 'none';
		document.getElementById('setOrdemServicoLayout').style.display			= 'none';
		document.getElementById('setFaturamentoFracionado').style.display		= 'none';
		document.formulario.IdFaturamentoFracionado.style.display				= 'none';
		document.getElementById("descIdFaturamentoFracionado").style.display	= 'none';
	}
	
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
