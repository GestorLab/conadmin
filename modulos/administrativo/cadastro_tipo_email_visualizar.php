<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdTipoEmail			= $_POST['IdTipoEmail'];
	$local_DescricaoTipoEmail	= formatText($_POST['DescricaoTipoEmail'],NULL);
	$local_EstruturaEmail		= formatText($_POST['EstruturaEmail'],NULL);
	$local_DiasParaEnvio		= formatText($_POST['DiasParaEnvio'],NULL);
	$local_AssuntoEmail			= formatText($_POST['AssuntoEmail'],NULL);
	
	if($_GET['IdEmail']!=''){
		$local_IdTipoEmail	= $_GET['IdEmail'];	
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_tipo_email.php');
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
</html>
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script>  
		<script type = 'text/javascript' src = '../../js/event.js'></script>  
		<script type = 'text/javascript' src = 'js/tipo_email.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_email_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<div id='cp_tit' style='margin-top:0'>Dados do Tipo E-mail</div>
			<form name='formulario' method='post' action='cadastro_tipo_email_visualizar.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>'>
				<input type='hidden' name='Local' value='TipoEmail'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:17px'>Tipo E-mail</B><B>Nome Tipo E-mail</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Dias para envio</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaTipoEmail', true, event, null, 94); document.formularioTipoEmail.Nome.value=''; busca_tipo_email_lista(); document.formularioTipoEmail.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdTipoEmail' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_tipo_email(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoTipoEmail' value='' style='width:624px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DiasParaEnvio' value='' autocomplete="off" style='width:100px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Assunto E-mail</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='AssuntoEmail' value='' autocomplete="off" style='width:816px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Estrutura E-mail</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='EstruturaEmail' style='width: 816px;' rows=10 tabindex='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='6' onClick='voltar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_visualizar' value='Visualizar' class='botao' tabindex='7' onClick='visualizar()'>
							</td>
						</tr>
					</table>
					<table style='width:100%'>
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
				include("files/busca/tipo_email.php");
			?>
		</div>
		<HR style='color:#004492'>
	</body>	
</html>
<script>
<?
	if($local_IdTipoEmail!=''){
		echo "busca_tipo_email($local_IdTipoEmail,false);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
