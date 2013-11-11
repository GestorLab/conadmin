<?
	$localModulo		=	1;
	$localOperacao		=	138;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Acao 				= $_POST['Acao'];
	$local_Erro					= $_GET['Erro'];
	
	$local_IdServico			= $_POST['IdServico'];
	$local_IdCFOPDisponiveis	= trim($_POST['IdCFOPDisponiveis']);
	
	if($_GET['IdServico'] != ''){
		$local_IdServico	= trim($_GET['IdServico']);	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_servico_cfop.php');
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
	print_r($_POST['IdCFOPDisponiveis']);
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
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/servico_cfop.js'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(638)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_servico_cfop.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ServicoCFOP'>
				<input type='hidden' name='QtdMesesTemp' value=''>
				<input type='hidden' name='IdQtdMesesTemp' value=''>
				
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value='<?=$local_IdServico?>'  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100'>
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
					<div id='cp_tit'><?=dicionario(247)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(639)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:822px' name='IdCFOPDisponiveis' size='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5' multiple>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:5px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' style='width:77px;' value='<?=dicionario(640)?>' class='botao' tabindex='5' onClick='servico_cfop();'>
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
				<div id='cpServicoCFOP' style='margin-top:10px; display:none;'>
					<div id='cp_tit' style='margin:0'><?=dicionario(641)?></div>	
					<table class='tableListarCad' id='tabelaServicoCFOP' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style="width:77px;"><?=dicionario(247)?></td>
							<td><?=dicionario(642)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='tabelaServicoCFOPTotal'><?=dicionario(128)?>: 0</td>
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
			echo "listar_cfop_servico($local_IdServico);";
			echo "scrollWindow('bottom');";
		}
	?>
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>