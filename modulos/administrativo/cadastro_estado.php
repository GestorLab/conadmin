<?
	$localModulo		=	1;
	$localOperacao		=	14;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdPais		 	= $_POST['IdPais'];
	$local_IdEstado		 	= $_POST['IdEstado'];
	$local_Estado	 		= formatText($_POST['Estado'],NULL);
	$local_SiglaEstado	 	= formatText($_POST['SiglaEstado'],NULL);

	if($_GET['IdPais']!=''){
		$local_IdPais	= $_GET['IdPais'];	
	}
	if($_GET['IdEstado']!=''){
		$local_IdEstado	= $_GET['IdEstado'];	
	}
	
	$sql	=	"SHOW TABLE STATUS WHERE Name='Cidade'";
	$res	=	@mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
	
	if($lin[Comment] == 'VIEW'){
		header("Location: sem_permissao.php");
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_estado.php');
			break;		
		case 'alterar':
			include('files/editar/editar_estado.php');
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
		<script type = 'text/javascript' src = 'js/estado.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(157)?>')">
		<? include('filtro_estado.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_estado.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='Estado'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:53px'><?=dicionario(256)?></B><?=dicionario(257)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:38px'><?=dicionario(157)?></B><B><?=dicionario(259)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(815)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPais', true, event, null, 94); document.formularioPais.NomePais.value=''; valorCampoPais = ''; busca_pais_lista(); document.formularioPais.NomePais.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:70px' autocomplete="off" maxlength='11' onChange='busca_pais(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='Pais' value='' style='width:259px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaEstado', true, event, null, 94); document.formularioEstado.NomeEstado.value=''; valorCampoEstado = ''; busca_estado_lista(); document.formularioEstado.NomeEstado.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_estado(document.formulario.IdPais.value,this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'><input class='agrupador' type='text' name='Estado' value='' style='width:291px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='SiglaEstado' value='' style='width:60px' maxlength='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'>
							</td>						
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='7' onClick="excluir(document.formulario.IdPais.value,document.formulario.IdEstado.value)">
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
				include("files/busca/estado.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdPais!=''){
		echo "busca_pais($local_IdPais,false,document.formulario.Local.value);";
	}
	if($local_IdEstado!=''){
		echo "busca_estado($local_IdPais,$local_IdEstado,false,document.formulario.Local.value);";
	}
?>	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
