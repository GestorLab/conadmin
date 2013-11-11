<?
	include('./files/conecta.php');
	include('./files/funcoes.php');
	
	$user_browser = getDataBrowser();
	
	if(browserCompativel($user_browser)){
		/* DIRECIONA PARA A TELA PRINCIPAL DO SISTEMA SE O BROWSER FOR COMPATIVEL */
		header("Location: ./");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ANSI" />
		<title>ConAdmin - Sistema Administrativo de Qualidade</title>
		
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='sem_permissao'>
			<p id='p1'>
				Voc� est� usando o navegador (browser): <?=$user_browser[name].' '.preg_replace("/\?/", '', $user_browser[version])?>
				<br />
				<br />
				Estas configura��es n�o s�o compat�veis com os servi�os do ConAdmin.
			</p>
			<p id='p2'>
				A seguir, � apresentada uma tabela com todos os navegadores que s�o compat�veis para uso do ConAdmin:
				<br />
				<br />
				<table style='width:570px; margin:auto; margin-left:-20px;'>
					<tr>
						<td style='text-align:center; height:25px; background-color:#E7E7A9;' colspan='6'><b>Navegadores compat�veis</b></td>
					</tr>
					<tr>
						<?
							foreach($versao_browser as $key => $abbreviation){
								echo "<td style='text-align:center; padding-top:6px;'><img src='".$versao_browser[$key][0]."' /></td>";
							}
						?>
					</tr>
					<tr>
						<?
							$style = "style='background-color:#F1F1CF; padding:1px 2px 1px 2px; vertical-align:top;'";
							
							foreach($versao_browser as $key => $abbreviation){
								echo "<td $style><b>".preg_replace("/( )([\d\.|]*)(x)/", "", trim($versao_browser[$key]['1']))."</b><br />";
								
								foreach($abbreviation as $id => $value){
									$value = preg_replace("/[A-z]|\//", '', $value);
									
									if($id > 0){
										echo $value."x<br />";
									}
								}
								
								echo "</td>";
							}
						?>
					</tr>
				</table>
				<br /><br />
				<form name='formulario' method='post' action='./'>
					<input type='hidden' name='VerificarBrowser' value='1'>
					<input style='width:75px;' type='submit' name='Avancar' value='Avan�ar' />
				</form>
				<br />
				Caso clique em 'Avan�ar', algumas ferramentas do sistema ConAdmin n�o poder�o estar funcionando corretamente. Duvidas, contacte o suporte.</p>
		</div>
		
		<script type='text/javascript'>
		<!--
			document.formulario.Avancar.focus();
			-->
		</script>
	</body>
</html>