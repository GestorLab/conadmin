<?
	$localModulo		=	1;
	$localOperacao		=	111;
	$localSuboperacao	=	"V";	

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];

	$local_IdLocalCobranca	= $_POST['IdLocalCobranca'];
	$local_IdLocalCobrancaLayout	= $_POST['IdLocalCobrancaLayout'];

	if($_GET['IdLocalCobranca']!=''){
		$local_IdLocalCobranca	= $_GET['IdLocalCobranca'];	
	}
	if($_GET['IdLocalCobrancaParametro']!=''){
		$local_IdLocalCobrancaParametro	= $_GET['IdLocalCobrancaParametro'];	
	}

	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_local_cobranca_parametro.php');
			break;
		default:
			$local_Acao 	 		= 'alterar';
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
		<script type = 'text/javascript' src = 'js/local_cobranca_parametro.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_parametro_default.js'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(842)?>')">
		<? include('filtro_local_cobranca.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_local_cobranca_parametro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='LocalCobrancaParametro'>
				<input type='hidden' name='IdLocalCobrancaLayout' value=''>
				<input type='text' style='display:none;' name='log'>
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaLocalCobranca', true, event, null, 118); limpa_form_local_cobranca(); busca_local_cobranca_lista(); document.formularioLocalCobranca.Nome.focus();"></td>
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
				<div>
					<div id='cp_tit'><?=dicionario(589)?></div>	
					<div id='cpDadosParametros' style='display: none'>					
						<table id='tableParametro'>
							<tr>
								<td class='find'>&nbsp;</td>
							<tr>							
						</table>		
					</div>									
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>							
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='100' onClick='cadastrar()'>
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
				include("files/busca/local_cobranca.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdLocalCobranca != ""){
			echo "busca_local_cobranca($local_IdLocalCobranca,false);";			
			echo "busca_local_cobranca_parametro($local_IdLocalCobranca,false,document.formulario.Local.value);";
			echo "scrollWindow('bottom');";	
		}				
	?>
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>

