<?
	$localModulo		=	1;
	$localOperacao		=	63;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdLocalCobranca						= $_POST['IdLocalCobranca'];
	$local_IdLocalCobrancaParametroContrato		= $_POST['IdLocalCobrancaParametroContrato'];
	$local_DescricaoParametroContrato			= trim($_POST['DescricaoParametroContrato']);
	$local_ValorDefaultInput					= trim($_POST['ValorDefaultInput']);
	$local_ValorDefaultSelect					= trim($_POST['ValorDefaultSelect']);
	$local_Editavel								= $_POST['Editavel'];
	$local_Obrigatorio							= $_POST['Obrigatorio'];
	$local_Obs									= trim($_POST['Obs']);
	$local_ParametroDemonstrativo				= trim($_POST['ParametroDemonstrativo']);
	$local_IdTipoParametro						= $_POST['IdTipoParametro'];
	$local_OpcaoValor							= trim($_POST['OpcaoValor']);
	$local_IdMascaraCampo						= $_POST['IdMascaraCampo'];
	$local_ObrigatorioStatus					= $_POST['ObrigatorioStatus'];
	$local_IdStatusParametro					= $_POST['IdStatusParametro'];
	$local_Calculavel							= $_POST['Calculavel'];
	$local_RotinaCalculo						= trim($_POST['RotinaCalculo']);
	$local_VisivelOS							= trim($_POST['VisivelOS']);
	$local_Visivel								= trim($_POST['Visivel']);
	
	if($_GET['IdLocalCobranca']!=''){
		$local_IdLocalCobranca	= $_GET['IdLocalCobranca'];	
	}
	if($_GET['IdLocalCobrancaParametroContrato']!=''){
		$local_IdLocalCobrancaParametroContrato	= $_GET['IdLocalCobrancaParametroContrato'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_local_cobranca_parametro_contrato.php');
			break;		
		case 'alterar':
			include('files/editar/editar_local_cobranca_parametro_contrato.php');
			break;
		default:
			$local_Acao 	 		= 'inserir';
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/local_cobranca_parametro_contrato.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_parametro_contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(844)?>')">
		<? include('filtro_local_cobranca_parametro_contrato.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_local_cobranca_parametro_contrato.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='LocalCobrancaParametroContrato'>
				<input type='hidden' name='IdLocalCobrancaParametroContratoTemp' value='<?=$local_IdLocalCobrancaParametroContrato?>'>
				
				<div id='cpDadosLocalCobranca'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(816)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B  style='margin-right:23px; color:#000' id='IdLocalCobranca'><?=dicionario(285)?>.</B><?=dicionario(843)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(818)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaLocalCobranca', true, event, null, 118); limpa_form_local_cobranca(); busca_local_cobranca_lista(); document.formularioLocalCobranca.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdLocalCobranca' value=''  style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'int')" onChange='busca_local_cobranca(this.value,false,document.formulario.Local.value)' tabindex='1'><input type='text' class='agrupador' name='DescricaoLocalCobranca' value='' style='width:590px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='AbreviacaoNomeLocalCobranca' value=''  style='width:134px' readOnly>
							</td>
						</tr>
					</table>		
				</div>
				<div  id='cpDadosParametros'>
					<div id='cp_tit'><?=dicionario(845)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(275)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLocalCobrancaParametroContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="busca_local_cobranca_parametro_contrato(document.formulario.IdLocalCobranca.value,'false',document.formulario.Local.value,document.formulario.IdLocalCobrancaParametroContrato.value)" tabindex='2'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(846)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(591)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoParametroContrato' value='' style='width:606px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoParametro' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4' onChange="verificaTipoParametro(this.value)">
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
								<textarea name='OpcaoValor' style='width: 816px' rows=5 onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='5' onChange="busca_opcao_valor(this.value,'')"></textarea>
								<BR>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(592)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(593)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(847)?>.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(848)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(596)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(597)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(140)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='titMascara' style='display:none'><?=dicionario(849)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='Editavel' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' onChange="verificaEditavel(this.value)">
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
								<input type='hidden' name='ObrigatorioStatus' value=''>
								<select name='Obrigatorio' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7' OnChange="document.formulario.ObrigatorioStatus.value=document.formulario.Obrigatorio.value;">
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
								<select name='ParametroDemonstrativo' style='width:127px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
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
								<select name='Calculavel' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9' onChange="ativarRotina(this.value)">
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
								<select name='Visivel' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'>
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
								<select name='VisivelOS' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
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
								<select name='IdStatusParametro' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12'>
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
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cpMascara' style='display:none'>
								<select name='IdMascaraCampo' style='width:191px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13' onChange="atualizaCampo(this)">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=75 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
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
								<input type='text' name='ValorDefaultInput' value='' style='width:555px' maxlength='255' onkeypress="chama_mascara(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='14'>
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
								<select name='ValorDefaultSelect' style='width:563px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15' onChange="verificaObrigatorio(this.value)">
									<option value='' selected></option>
								</select>
							</td>
						</tr>
					</table>
					<table id='cpRotinaCalculo' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(850)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RotinaCalculo' value='' style='width:555px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='16'>
							</td>
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
								<textarea name='Obs' style='width: 816px' rows=5 onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='17'></textarea>
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
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='18' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='19' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='20' onClick="excluir(document.formulario.IdLocalCobranca.value,document.formulario.IdLocalCobrancaParametroContrato.value)">
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
				<div id='cpParametrosCadastrados'  style='margin-top:10px'>
					<div id='cp_tit' style='margin:0'><?=dicionario(851)?></div>	
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
				include("files/busca/local_cobranca.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdLocalCobranca != ""){
			echo "busca_local_cobranca($local_IdLocalCobranca,false);";
			if($local_IdLocalCobrancaParametroContrato != ""){
				echo "busca_local_cobranca_parametro_contrato($local_IdLocalCobranca,false,document.formulario.Local.value,'$local_IdLocalCobrancaParametroContrato');";
				echo "scrollWindow('bottom');";	
				
			}
		}
	?>
	function status_inicial(){
		if(document.formulario.Editavel.value == '' || document.formulario.Editavel.value == 0){
			document.formulario.Editavel.value = '<?=getCodigoInterno(3,19)?>';
		}
		if(document.formulario.Obrigatorio.value == '' || document.formulario.Obrigatorio.value == 0){
			document.formulario.Obrigatorio.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.ObrigatorioStatus.value == '' || document.formulario.ObrigatorioStatus.value == 0){
			document.formulario.ObrigatorioStatus.value = '<?=getCodigoInterno(3,18)?>';
		}
		if(document.formulario.ParametroDemonstrativo.value == '' || document.formulario.ParametroDemonstrativo.value == 0){
			document.formulario.ParametroDemonstrativo.value = '<?=getCodigoInterno(3,37)?>';
		}
		if(document.formulario.Calculavel.value == '' || document.formulario.Calculavel.value == 0){
			document.formulario.Calculavel.value = '<?=getCodigoInterno(3,29)?>';
		}
		if(document.formulario.Visivel.value == '' || document.formulario.Visivel.value == 0){
			document.formulario.Visivel.value = '<?=getCodigoInterno(3,55)?>';
		}
		if(document.formulario.VisivelOS.value == '' || document.formulario.VisivelOS.value == 0){
			document.formulario.VisivelOS.value = '<?=getCodigoInterno(3,38)?>';
		}
		if(document.formulario.IdStatusParametro.value == '' || document.formulario.IdStatusParametro.value == 0){
			document.formulario.IdStatusParametro.value = '<?=getCodigoInterno(3,4)?>';
		}		
	}
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>