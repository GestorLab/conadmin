<?
	$localModulo		=	1;
	$localOperacao		=	50;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdProdutoTipoVigencia		= $_POST['IdProdutoTipoVigencia'];
	$local_DescricaoProdutoTipoVigencia	= formatText($_POST['DescricaoProdutoTipoVigencia'],NULL);
	
	if($_GET['IdProdutoTipoVigencia']!=''){
		$local_IdProdutoTipoVigencia	= $_GET['IdProdutoTipoVigencia'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_produto_tipo_vigencia.php');
			break;		
		case 'alterar':
			include('files/editar/editar_produto_tipo_vigencia.php');
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/produto_tipo_vigencia.js'></script>
		<script type = 'text/javascript' src = 'js/produto_tipo_vigencia_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Tipo Vigência Produto')">
		<? include('filtro_produto_tipo_vigencia.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_produto_tipo_vigencia.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='ProdutoTipoVigencia'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:8px'>Tipo Vigência</B><B>Nome Tipo Vigência Produto</B></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProdutoTipoVigencia' value='' autocomplete="off" style='width:73px' maxlength='11' onChange='busca_produto_tipo_vigencia(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoProdutoTipoVigencia' value='' style='width:398px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
				<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Alteração</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Alteração</td>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='7' onClick='excluir(document.formulario.IdProdutoTipoVigencia.value)'>
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
	if($local_IdProdutoTipoVigencia!=''){
		echo "busca_produto_tipo_vigencia($local_IdProdutoTipoVigencia,false);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
