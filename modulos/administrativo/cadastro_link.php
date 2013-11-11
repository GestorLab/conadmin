<?
	$localModulo		=	1;
	$localOperacao		=	37;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdLink			= formatText($_POST['IdLink'],NULL);
	$local_DescricaoLink	= formatText($_POST['DescricaoLink'],NULL);
	$local_Link				= formatText($_POST['Site'],getParametroSistema(4,5));
	
	if($local_Link == ""){
		$local_Link = "http://";
	}
	
	if($_GET['IdLink']!=''){
		$local_IdLink	=	$_GET['IdLink'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_link.php');
			break;		
		case 'alterar':
			include('files/editar/editar_link.php');
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
		<script type = 'text/javascript' src = '../../js/val_url.js'></script>	
		<script type = 'text/javascript' src = '../../js/event.js'></script>		
		<script type = 'text/javascript' src = 'js/link.js'></script>
		<script type = 'text/javascript' src = 'js/link_default.js'></script>
		
	</head>
	<style type="text/css">
		input[type=text]:readOnly  		{ background-color: #FFF; }
		input[type=datetime]:readOnly  	{ background-color: #FFF; }
		input[type=date]:readOnly  		{ background-color: #FFF; }
		textarea:readOnly  				{ background-color: #FFF; }
		select:disabled  { background-color: #FFF; }
		select:disabled  { color: #000; }
	</style>
	<body  onLoad="ativaNome('Link')">
		<? 
			include('filtro_link.php'); 
		?>
	<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_link.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Link'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:52px'>Link</B><B>Descrição</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>URL Link</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLink' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_link(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoLink' value='' autocomplete="off" style='width:450px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Site' value='<?=$local_Link?>' style='width:250px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='find' onClick='JsHttp(document.formulario.Site.value)'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Acessar Site'></td>
						</tr>
					</table>
				</div>
				<div id='cp_observacoes'>
					<div id='cp_tit'>Observações e Log</div>					
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='4' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.IdLink.value)">
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
	</div>
</body>
</html>
<script language='JavaScript' type='text/javascript'> 
<?
	if($local_IdLink!=''){
		echo "busca_link($local_IdLink,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
