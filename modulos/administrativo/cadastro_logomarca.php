<?
	$localModulo		= 1;
	$localOperacao		= 182;
	$localSuboperacao	= "I";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$array_operacao 	= array( "182" );
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Largura 			= $_POST['Largura'];
	$local_Alinhamento		= $_POST['floate'];
	$local_MargemEsquerda	= $_POST['Margem_Esquerda'];
	$local_MargemSuperior	= $_POST['Margem_Superior'];
	$local_fakeupload		= $_POST['fakeupload'];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	switch($local_Acao){
		case 'alterar':
			include('files/editar/editar_logomarca.php');
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
		
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/val_url.js'></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script> 
		<script type='text/javascript' src='js/logomarca.js'></script>
		<script type='text/javascript' src='js/logomarca_default.js'></script>
	</head>
	<style type="text/css">
		input[type=text]:readOnly { background-color: #FFF; }
		input[type=datetime]:readOnly { background-color: #FFF; }
		input[type=date]:readOnly { background-color: #FFF; }
		textarea:readOnly { background-color: #FFF; }
		select:disabled { background-color: #FFF; }
		select:disabled { color: #000; }
	</style>
	<body onLoad="ativaNome('Logomarca');atualiza_logo();">
		<? include('filtro_logomarca.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_logomarca.php' onSubmit='return validar();' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='Logomarca' />
				<input type='hidden' name='tempEndArquivo' id='tempEndArquivo' value='' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Largura</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Margem Esquerda</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Margem Superior</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Largura' value='' style='width:120px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='1' />
								<p style='margin-top:3px'>Tamanho em pixel</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Margem_Esquerda' value='' style='width:120px' maxlength='11'  onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								<p style='margin-top:3px'>Tamanho em pixel</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Margem_Superior' value='' style='width:120px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' />
								<p style='margin-top:3px'>Tamanho em pixel</p>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Logomarca</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' colspan='3'>Nova Logomarca</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top' id='EndArquivo' style='display:block;'>																
								<div style='border-right:1px #A4A4A4 solid; width:1px; height:22px; margin-top:1px; float:left'> </div>
								<input type="text" id="fakeupload" name="fakeupload" class="fakeupload" style='width:295px; margin-left:0' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='4' />								
								<div id='bt_procurar' style='margin-left:295px;' tabindex='5'></div>
								<input type="file" id="realupload" name='EndArquivo' size='1' class="realupload" onchange="document.formulario.fakeupload.value = this.value; document.formulario.tempEndArquivo.value = document.formulario.EndArquivo.value; verificaImagem();" /> 																								
								<p style='width:375px; margin-top:3px'>Dimensões(máxima): largura 200px; altura 40px. Extensão: GIF</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<div style='width:200px; height:40px; border:1px #A4A4A4 solid;'>
									<img id='VerImagem' src="../../img/personalizacao/logo_cab.gif" style='background-repeat:no-repeat;'/>
								</div>
							</td>																				
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_resetar' value='Resetar Configurações' class='botao' tabindex='6' onClick="limpa_campos_posicionamento_logo(<?=$local_IdLoja;?>)" />
								<input type='button' name='bt_atualizar' value='Atualizar Logo' class='botao' tabindex='6' onClick="atualiza_logo()" />
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='7' onClick="cadastrar('alterar')" />
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
		<script type='text/javascript'>
			busca_dados_logomarca();
			verificaErro();
			verificaAcao();
		</script>
	</body>
</html>