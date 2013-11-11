<?
	$localModulo		=	1;
	$localOperacao		=	82;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdTipoOrdemServico			= $_POST['IdTipoOrdemServico'];
	$local_DescricaoTipoOrdemServico	= formatText($_POST['DescricaoTipoOrdemServico'],NULL);
	$local_Cor							= formatText($_POST['Cor'],NULL);
	
	if($_GET['IdTipoOrdemServico']!=''){
		$local_IdTipoOrdemServico	= $_GET['IdTipoOrdemServico'];	
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_tipo_ordem_servico.php');
			break;
		default:
			$local_Acao = 'inserir';
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
		<script type = 'text/javascript' src = 'js/tipo_ordem_servico.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_ordem_servico_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(737)?>')">
		<? include('filtro_tipo_ordem_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_tipo_ordem_servico.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='TipoOrdemServico'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:40px'><?=dicionario(487)?></B><?=dicionario(738)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Cor</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdTipoOrdemServico' value='' autocomplete="off" style='width:73px' maxlength='11' onChange='busca_tipo_ordem_servico(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoTipoOrdemServico' value='' style='width:398px' maxlength='20' tabindex='2' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Cor' value='' autocomplete="off" style='width:100px' maxlength='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/cores.gif' alt='Alterar Cor' title='Alterar Cor' onClick="vi_id('quadroBuscaCor', true, event, null, 90);"></td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
				<div id='cp_tit'><?=dicionario(571)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
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
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='4' onClick='cadastrar()'>
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
				include("files/busca/cores.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdTipoOrdemServico!=''){
		echo "busca_tipo_ordem_servico($local_IdTipoOrdemServico,false,document.formulario.Local.value);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>