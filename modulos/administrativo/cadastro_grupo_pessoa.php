<?
	$localModulo		=	1;
	$localOperacao		=	12;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdGrupoPessoa 			= formatText($_POST['IdGrupoPessoa'],NULL);
	$local_DescricaoGrupoPessoa		= formatText($_POST['DescricaoGrupoPessoa'],NULL);

	if($_GET['IdGrupoPessoa']!=''){
		$local_IdGrupoPessoa	= $_GET['IdGrupoPessoa'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_grupo_pessoa.php');
			break;		
		case 'alterar':
			include('files/editar/editar_grupo_pessoa.php');
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_pessoa.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_pessoa_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(106)?>')">
		<? include('filtro_grupo_pessoa.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_grupo_pessoa.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='GrupoPessoa'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:11px'><?=dicionario(734)?>.</B><B><?=dicionario(735)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdGrupoPessoa' value='' style='width:70px' autocomplete="off" maxlength='11' onChange='busca_grupo_pessoa(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoPessoa' value='' style='width:392px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
							</td>							
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
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='3' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='4' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='5' onClick="excluir(document.formulario.IdGrupoPessoa.value)">
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
	</body>
</html>
<script>
<?
	if($local_IdGrupoPessoa!=''){
		echo "busca_grupo_pessoa($local_IdGrupoPessoa,false,document.formulario.Local.value);";
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>