<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	$local_IdProcesso		= $_POST['IdProcesso'];
	
	if($_GET['IdProcesso']!=''){
		$local_IdProcesso	=	$_GET['IdProcesso'];
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
		<script type = 'text/javascript' src = 'js/processo_default.js'></script>
		
	</head>
	<style type="text/css">
		input[type=text]:readOnly  		{ background-color: #FFF; }
		input[type=datetime]:readOnly  	{ background-color: #FFF; }
		input[type=date]:readOnly  		{ background-color: #FFF; }
		textarea:readOnly  				{ background-color: #FFF; }
		select:disabled  { background-color: #FFF; }
		select:disabled  { color: #000; }
	</style>
	<body  onLoad="ativaNome('Processos')">
	<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_processo.php'>
				<input type='hidden' name='Local' value='Processo'>
				<div>
					<div id='cp_tit' style='margin-top:0'>Processo</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Processo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Host</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProcesso' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_processo(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='User' value='' style='width:312px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Host' value='' style='width:400px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Banco de Dados</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Comando</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tempo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DB' value='' style='width:300px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Command' value='' style='width:165px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Time' value='' style='width:100px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='State' value='' style='width:200px' readOnly>
							</td>
						</tr>
					</table>				
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Informações</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Info' style='width: 816px;' rows=5 tabindex='60' readOnly></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='2' onClick='voltar()'>
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
	if($local_IdProcesso!=''){
		echo "busca_processo($local_IdProcesso,false,document.formulario.Local.value);";
	}
?>
	inicia();
	enterAsTab(document.forms.formulario);
</script>
