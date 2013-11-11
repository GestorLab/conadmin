<?
	include ('files/conecta.php');
	include ('files/conecta_cntsistemas.php');
	include ('files/funcoes.php');
	
	$local_Erro	=	$_GET['Erro'];
	
	if(file_exists("atualizacao") && $local_Erro == ''){
		header("Location: atualizacao.php");
	} else{
		if(!$conCNT){
			header("Location: erro_conecta_cntsistemas.php");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/helpdesk.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<div id='carregando'><?=dicionario(17)?>...</div>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<h1>Help Desk - Tickets</h1>
			<form action='modulos/helpdesk/rotinas/autentica.php' method='post' name='formulario' onSubmit='return validar()'>
				<input type='hidden' name='Local' value='helpdesk'>
				<input type='hidden' name='Acao' value='login'>	
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<table>
					<tr>
						<td class='descCampo' colspan=2><B><?=dicionario(1)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='IdLoja' value='' style='width: 70px' maxlength='2'  onChange='busca_loja(this.value)' onkeypress="mascara(this,event,'int')" tabindex=1 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='DescricaoLoja' value='' style='width: 268px' readOnly>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'><B><?=dicionario(2)?></B></td>
						<td class='descCampo'><B><?=dicionario(3)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' value='' style='width: 169px' maxlength='20' tabindex=1  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="cadastrar(event)">
						</td>
						<td class='campo'>
							<input type='password' name='Senha' value='' style='width: 169px' maxlength='8' tabindex=2  onFocus="Foco(this,'in');"  onBlur="Foco(this,'out')" onKeyDown="cadastrar(event)">
						</td>
					</tr>
					<tr>
						<td class='descInfo'><a href='http://www.cntsistemas.com.br' target='_blank'><?=dicionario(5)?></a></td>
						<td style='text-align:right'>
							<input type='button' value='<?=dicionario(14)?>' class='botao' tabindex=6 onClick='voltar()'>
							<input type='submit' value='<?=dicionario(16)?>' class='botao' tabindex=3>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script>
	verificaErro();
	inicia();
</script>
