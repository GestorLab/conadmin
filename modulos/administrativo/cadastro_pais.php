<?
	$localModulo		=	1;
	$localOperacao		=	13;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdPais		 	= $_POST['IdPais'];
	$local_Pais			 	= formatText($_POST['Pais'],NULL);

	$sql	=	"SHOW TABLE STATUS WHERE Name='Cidade'";
	$res	=	@mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
	
	if($lin[Comment] == 'VIEW'){
		header("Location: sem_permissao.php");
	}

	if($_GET['IdPais']!=''){
		$local_IdPais	= $_GET['IdPais'];	
	}
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_pais.php');
			break;		
		case 'alterar':
			include('files/editar/editar_pais.php');
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/pais.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(256)?>')">
		<? include('filtro_pais.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_pais.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='Pais'>
				<div>
					<table>
						<tr>
							<td class='find'></td>
							<td class='descCampo'><B style='color:#000; margin-right:53px'><?=dicionario(256)?></B><B><?=dicionario(257)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPais', true, event, null, 94); document.formularioPais.NomePais.value=''; valorCampoPais = ''; busca_pais_lista(); document.formularioPais.NomePais.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:70px' autocomplete="off" maxlength='11' onChange='busca_pais(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='Pais' value='' style='width:398px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
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
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='5' onClick="excluir(document.formulario.IdPais.value)">
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
				include("files/busca/pais.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdPais!=''){
		echo "busca_pais($local_IdPais,false,document.formulario.Local.value);";
	}
?>	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
