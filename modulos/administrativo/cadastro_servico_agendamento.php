<?
	$localModulo		=	1;
	$localOperacao		=	136;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login							= $_SESSION["Login"];
	$local_IdLoja							= $_SESSION["IdLoja"];
	$local_Acao 							= $_POST['Acao'];
	$local_Erro								= $_GET['Erro'];
	
	$local_IdServico						= $_POST['IdServico'];
	$local_IdTipoServico					= $_POST['IdTipoServicoTemp'];
	$local_IdStatus							= $_POST['IdStatus'];
	$local_IdServicoTemp					= $_POST['IdServicoTemp'];
	$local_IdQtdMeses						= $_POST['IdQtdMeses'];
	$local_IdNovoStatus						= $_POST['IdNovoStatus'];
	$local_MudarStatusContratoConcluirOS	= $_POST['MudarStatusContratoConcluirOS'];
	$local_QtdDias							= $_POST['QtdDias'];
	
	if($_GET['IdServico'] != ''){
		$local_IdServico	= $_GET['IdServico'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_servico_agendamento.php');
			break;
		case 'alterar':
			include('files/editar/editar_servico_agendamento.php');
			break;
		default:
			$local_Acao 	= 'inserir';
			break;
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico_agendamento.js'></script>
		<script type = 'text/javascript' src = 'js/servico_agendamento_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = '../../classes/calendar/calendar.js'></script>
	    <script type = 'text/javascript' src = '../../classes/calendar/calendar-setup.js'></script>
	    <script type = 'text/javascript' src = '../../classes/calendar/calendar-br.js'></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(643)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico_agendamento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ServicoAgendamento'>
				<input type='hidden' name='QtdMesesTemp' value=''>
				<input type='hidden' name='IdQtdMesesTemp' value=''>
				<input type='hidden' name='IdTipoServicoTemp' value=''>
				<input type='hidden' name='IdServicoTemp' value='<?=$local_IdServicoTemp?>'>
				
				<div id='cpDadosServicoNotaFiscalParametro'>				
					<div id='cp_tit' style='margin-top:0'><?=dicionario(435)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(436)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
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
				<div>
					<div id='cp_tit'><?=dicionario(643)?></div>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="vertical-align:top;">
								<table id="cp_opPeriodico" style="margin:0;">
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(644)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(140)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id="titNovoStatus" style="color:#000;"><?=dicionario(454)?></B></td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='IdQtdMeses' value=''  style='width:81px' maxlength='3' onChange="verificar_qtd_meses(this.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='IdStatus' style='width:287px' onChange="busca_novo_status(this.value, undefined, true)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
												<option value='' selected></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='IdNovoStatus' style='width:287px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificar_status(this.value);" tabindex='4' disabled>
												<option value='' selected></option>
											</select>
										</td>
									</tr>
								</table>
								<table id="cp_opAgrupado" style="display:none; margin:0;">
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(645)?></td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='MudarStatusContratoConcluirOS' style='width:390px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificar_status(this.value);" tabindex='3'>
												<option value='' selected></option>
												<?
													$sql = "SELECT
																IdParametroSistema,
																ValorParametroSistema
															FROM 
																ParametroSistema
															WHERE 
																IdGrupoParametroSistema = 69 AND 
																IdParametroSistema NOT IN (305, 101)
															ORDER BY 
																ValorParametroSistema;";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}

												?>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td style="vertical-align:top;">
								<table id="cpQtdDias" style="margin:0; display:none;">
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(646)?></B></td>
									</tr>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='QtdDias' value='' style='width:120px;' maxlength='3' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='2'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(571)?></div>
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
					<table style='float:right; margin-right:5px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' style='width:77px;' value='<?=dicionario(146)?>' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' style='width:60px;' value='<?=dicionario(15)?>' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' style='width:60px;' value='<?=dicionario(147)?>' class='botao' tabindex='7' onClick="excluir(document.formulario.IdServico.value, document.formulario.IdQtdMeses.value)">
							</td>
						</tr>
					</table>
					<table style='height:32px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
				<div id='cpAgendamentosCadastrados' style='margin-top:10px; display:none;'>
					<div id='cp_tit' style='margin:0'><?=dicionario(647)?></div>	
					<table class='tableListarCad' id='tabelaAgendamento' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(644)?></td>
							<td><?=dicionario(140)?></td>
							<td><?=dicionario(454)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaAgendamentoTotal'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
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
			
			if($local_IdQtdMeses != ""){
				echo "busca_servico_agendamento($local_IdServico,$local_IdQtdMeses,false);";
			}
			
			echo "scrollWindow('bottom');";
		}
	?>
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
