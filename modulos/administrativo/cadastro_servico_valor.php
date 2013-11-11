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
	$local_IdTipoServico					= $_POST['IdTipoServico'];
	$local_DataTermino						= $_POST['DataTermino'];
	$local_DescricaoServicoValor			= formatText($_POST['DescricaoServicoValor'],NULL);
	$local_DataInicio						= formatText($_POST['DataInicio'],NULL);
	$local_Valor							= formatText($_POST['Valor'],NULL);
	$local_MultaFidelidade					= formatText($_POST['MultaFidelidade'],NULL);
	$local_ValorAnterior					= formatText($_POST['ValorAnterior'],NULL);
	$local_IdContratoTipoVigencia			= formatText($_POST['IdContratoTipoVigencia'],NULL);
	
	if($_GET['IdServico']!=''){
		$local_IdServico	= $_GET['IdServico'];	
	}
	if($_GET['DataInicio']!=''){
		$local_DataInicio	= $_GET['DataInicio'];	
	}
	
	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_servico_valor.php');
			break;		
		case 'alterar':
			include('files/editar/editar_servico_valor.php');
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico_valor.js'></script>
		<script type = 'text/javascript' src = 'js/servico_valor_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
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
	<body  onLoad="ativaNome('<?=dicionario(569)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico_valor.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ServicoValor'>
				<input type='hidden' name='IdTipoServico' value=''>
				<input type='hidden' name='ValorAnterior' value=''>
				<input type='hidden' name='maxQtdMesesFidelidade' value=''>
				<input type='hidden' name='DataInicioTemp' value=''>
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:95px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServicoAux' style='width:175px' disabled>
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
				<div  id='cpDadosValores'>
					<div id='cp_tit'><?=dicionario(204)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='DataInicio'><?=dicionario(376)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='DataTermino'><?=dicionario(433)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='Valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorMulta' style='color:#000'><?=dicionario(244)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='TipoVigencia'><?=dicionario(45)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' id='cpDataInicio' value='' style='width:100px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataInicio',this); busca_servico_valor(document.formulario.IdServico.value,'false',document.formulario.Local.value,formatDate(document.formulario.DataInicio.value));verificaDataInicio(this.value,document.formulario.DataInicioTemp.value);" tabindex='2'>
							</td>
							<td class='find' id='cpDataInicioIco'><img name='icoDataInicio' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataInicio",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataInicioIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' id='cpDataTermino' value='' style='width:100px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataTermino',this); verificaDataFinal('DataInicio',document.formulario.DataInicio.value,this.value)" tabindex='3'>
							</td>
							<td class='find' id='cpDataTerminoIco'><img name='icoDataTermino' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataTermino",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataTerminoIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:169px' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out')" tabindex='4'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MultaFidelidade' value='' style='width:166px'  maxlength='11' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out')" tabindex='5'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdContratoTipoVigencia' style='width:175px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdContratoTipoVigencia, DescricaoContratoTipoVigencia from ContratoTipoVigencia where IdLoja = $local_IdLoja order by DescricaoContratoTipoVigencia ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdContratoTipoVigencia]'>$lin[DescricaoContratoTipoVigencia]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(570)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoServicoValor' value='' style='width:630px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='7'>
							</td>
						</tr>
					</table>
				</div>
				<div id='cpTerceiro'>
					<div id='cp_tit'><?=dicionario(33)?></div>
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
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='8' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='9' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='10' onClick="excluir(document.formulario.IdServico.value,formatDate(document.formulario.DataInicio.value))">
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
				<div id='cpValoresCadastrados' style='margin-top:10px'>
					<div id='cp_tit' style='margin:0'><?=dicionario(572)?></div>	
					<table class='tableListarCad' Id='tabelaValor' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 100px'><?=dicionario(376)?></td>
							<td style='width: 100px'><?=dicionario(433)?></td>
							<td><?=dicionario(125)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(244)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaValorTotal'><?=dicionario(128)?>: 0</td>
							<td>&nbsp;</td>
							<td id='tabelaValorValor' class='valor'>0,00</td>
							<td id='tabelaValorMulta' class='valor'>0,00</td>
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
		if($local_IdServico != ""){
			# A FUNCTION QUE BUSCA O SERVIÇO VALOR ESTA DENTRO DA FUNCTION ABAIXO #
			# PARA QUE ESTA SEJA EXECUTADA SÓ APOS A CONCLUSÃO DA TAL ----------- #
			echo "busca_servico($local_IdServico,false);";
		}
		
		if($_GET['IdServico']!= "" && $_GET['DataInicio'] != ""){
			echo "busca_servico_valor_bloquear('".$_GET['IdServico']."','".$_GET['DataInicio']."');";
		}
	?>
	
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>