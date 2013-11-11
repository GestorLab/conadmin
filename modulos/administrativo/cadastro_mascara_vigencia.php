<?
	$localModulo		=	1;
	$localOperacao		=	68;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login						= $_SESSION["Login"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdServico	 				= $_POST['IdServico'];
	$local_Fator		 				= formatText($_POST['Fator'],NULL);
	$local_Mes							= formatText($_POST['Mes'],NULL);
	$local_IdTipoDesconto				= formatText($_POST['IdTipoDesconto'],NULL);
	$local_IdContratoTipoVigencia		= formatText($_POST['IdContratoTipoVigencia'],NULL);
	$local_LimiteDesconto				= formatText($_POST['LimiteDesconto'],NULL);
	$local_VigenciaDefinitiva			= formatText($_POST['VigenciaDefinitiva'],NULL);
	$local_ValorDesconto				= formatText($_POST['ValorDesconto'],NULL);
	$local_IdRepasse					= formatText($_POST['IdRepasse'],NULL);
	$local_ValorRepasseTerceiro			= formatText($_POST['ValorRepasseTerceiro'],NULL);
	$local_PercentualRepasseTerceiro	= formatText($_POST['PercentualRepasseTerceiro'],NULL);
	
	if($_GET['IdServico']!=''){
		$local_IdServico	= $_GET['IdServico'];	
	}
	if($_GET['Mes']!=''){
		$local_Mes	= $_GET['Mes'];	
	}
	
	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_mascara_vigencia.php');
			break;
		case 'alterar':
			include('files/editar/editar_mascara_vigencia.php');
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/val_data.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/mascara_vigencia.js'></script>
		<script type = 'text/javascript' src = 'js/mascara_vigencia_default.js'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(573)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_mascara_vigencia.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='CountMes' value=''>
				<input type='hidden' name='Local' value='MascaraVigencia'>
				<input type='hidden' name='Fator' value=''>
				
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
				<div>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(575)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(188)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(574)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(312)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titDesconto' style='display:none;'><?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titPercentual' style='display:none;'><?=dicionario(521)?> (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><span id='titValorFinal' style='display:none;'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</span></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Mes' value=''  style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorServico' value='' style='width:130px' maxlength='15' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoDesconto' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verifica_limite_desconto(this.value)" tabindex='2'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=73 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpDesconto' name='ValorDesconto' value='' style='width:130px; display:none;' autocomplete="off" maxlength='15' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onBlur="Foco(this,'out'); calcula_fator(this.value); verifica_limite_desconto(document.formulario.IdTipoDesconto.value); calculaValorFinal(document.formulario.ValorServico.value,this.value,document.formulario.ValorPercentual.value,this)" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' id='cpPercentual' name='ValorPercentual' value='' style='width:130px; display:none;' autocomplete="off" maxlength='8' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)'onBlur="Foco(this,'out'); calculaValorFinal(document.formulario.ValorServico.value,document.formulario.ValorDesconto.value,this.value,this)" tabindex='4'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpValorFinal' name='ValorFinal' value='' style='width:117px; display:none;' maxlength='15' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(519)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(576)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='display:none;' id='titLimiteDesconto'><?=dicionario(522)?></B></td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdContratoTipoVigencia' style='width:265px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdContratoTipoVigencia, DescricaoContratoTipoVigencia from ContratoTipoVigencia where IdLoja=$local_IdLoja order by DescricaoContratoTipoVigencia ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdContratoTipoVigencia]'>$lin[DescricaoContratoTipoVigencia]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='VigenciaDefinitiva' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=144 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>		
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top' id='cpLimiteDesconto' style='display:none'>
								<input type='text' name='LimiteDesconto' value='' autocomplete="off" style='width:120px' maxlength='3' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this, event, 'int', null, 'neg');" tabindex='7'>
							</td>											
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(577)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(556)?></td>
							<td class='descCampo'><B id='cpValorRepasseMensal' style='margin-left:9px; display:none;'><?=dicionario(557)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='descCampo'><B id='cpPercRepasseMensal' style='margin-left:7px; display:none;'><?=dicionario(521)?> (%)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdRepasse' style='width:107px;' onchange="verificarRepasse(this.value);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6'>
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
								<input type='text' name='ValorRepasseTerceiro' id='cpValorRepasseTerceiro' value='' style='width:150px; margin-left:9px; display:none;' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out');">
							</td>
							<td class='campo'>
								<input type='text' name='PercentualRepasseTerceiro' value='' style='width:150px; margin-left:7px; display:none;' maxlength='8' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out');">
							</td>
						</tr>
					</table>
				</div>
				<div id='Log'>
					<div id='cp_tit'>Log</div>
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
								<input type='text' name='DataAlteracao' value='' style='width:203px'  readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div id='cp_botao' style='height:23px'>
					<table style='float:right; margin-right:6px; '>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='11' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='12' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='13' onClick="excluir(document.formulario.IdServico.value,document.formulario.Mes.value,'listar')">
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
				<div id='cp_vigencia_cadastrada'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(578)?></div>
					<table id='tabelaMascaraVigencia' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(188)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(579)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(576)?></td>
							<td class='valor'><?=dicionario(520)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(580)?> (%)</td>
							<td  class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='tabelaVigenciaTotal'><?=dicionario(128)?>: 0</td>
							<td id='tabelaVigenciaValor' class='valor'>0,00</td>
							<td id='tabelaVigenciaValorDesconto' class='valor'>0,00</td>
							<td id='tabelaVigenciaValorFinal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaValorRepasse' class='valor'>0,00</td>
							<td id='tabelaPercentualRepasse' class='valor'>0,00</td>
							<td>&nbsp;</td>
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
			?>
		</div>
	</body>	
</html>
<script type="text/javascript">
<?
	if($local_IdServico!='' && $local_Mes != ''){ 
		echo "busca_mascara_vigencia($local_IdServico,false,document.formulario.Local.value,$local_Mes);";
	}else{
		if($local_IdServico!=''){
			$sql = "select IdTipoServico from Servico where IdLoja = $local_IdLoja and IdServico = $local_IdServico";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			if($lin[IdTipoServico] == '1' || $lin[IdTipoServico] == '4'){
				echo "busca_servico($local_IdServico,false,document.formulario.Local.value);";
			}
		} else{
			echo "limparDesconto()";
		}		
	}
?>	
	function status_inicial(){
		if(document.formulario.Acao.value == "inserir"){
			document.formulario.IdTipoDesconto.value = '<?=getCodigoInterno(3,53)?>';
		}
		
		if(document.formulario.ValorDesconto.value == ''){
			document.formulario.ValorDesconto.value = "0,00";
		}
		
		if(document.formulario.ValorPercentual.value == ''){
			document.formulario.ValorPercentual.value = "0,00";
		}
		
		if(document.formulario.ValorFinal.value == ''){
			document.formulario.ValorFinal.value = "0,00";
		}
		
		if(document.formulario.ValorRepasseTerceiro.value == ''){
			document.formulario.ValorRepasseTerceiro.value = "0,00";
		}
	}
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>