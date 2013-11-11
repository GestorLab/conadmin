<?
	$localModulo		= 1;
	$localOperacao		= 166;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET['Erro'];
	
	$local_IdTemplate			= $_POST['IdTemplate'];
	$local_DescricaoTemplate	= formatText($_POST['DescricaoTemplate'],NULL);
	$local_Estrutura			= formatText($_POST['Estrutura'],NULL);
	
	if($local_IdTemplate == ''){
		$local_IdTemplate = $_GET['IdTemplate'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_template_mensagem.php');
			break;
		case 'alterar':
			include('files/editar/editar_template_mensagem.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/template_mensagem.js'></script>
		<script type='text/javascript' src='js/template_mensagem_default.js'></script>
		
		<style type='text/css'>
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Template Mensagem')">
		<? 
			include('filtro_conta_email.php'); 
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_template_mensagem.php' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='TemplateMensagem' />
					<div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Template</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdTemplate' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_template_mensagem(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Dados da Mensagem (Template)</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Descrição</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescricaoTemplate' value='' style='width:816px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Estrutura</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Estrutura' style='width:816px; height:141px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2'></textarea>
								</td>
							</tr>
						</table>
					</div>
					<div>
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
								<td class='separador'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao' value='' style='width:181px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao' value='' style='width:202px' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly />
								</td>							
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' style='width:74px;' name='bt_inserir' value='Cadastrar' class='botao' tabindex='23' onClick="cadastrar('inserir')" />
									<input type='button' style='width:57px;' name='bt_alterar' value='Alterar' class='botao' tabindex='24' onClick="cadastrar('alterar')" />
									<input type='button' style='width:57px;' name='bt_excluir' value='Excluir' class='botao' tabindex='25' onClick="excluir(document.formulario.IdTemplate.value)" />
								</td>
							</tr>
						</table>
						<table style='height:32px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
							</tr>
						</table>
					</div>
					<div style='margin-top:10px'>
						<div id='cp_tit' style='margin:0'>Templates Cadastrados</div>	
						<table class='tableListarCad' id='tabelaTemplateMensagem' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'>Id</td>
								<td>Descrição</td>
								<td>Data Cadastro</td>
								<td>Data Alteração</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='4' id='tabelaTemplateMensagemTotal'>Total: 0</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<table id='tabelahelpText2' style='height:32px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>
<script type='text/javascript'>
<!-- 
<?
	if($local_IdTemplate != '') {
		echo "busca_template_mensagem($local_IdTemplate,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	} else{
		echo "listar_template_mensagem();";
	}
?>
	function inicia(){
		document.formulario.IdTemplate.focus();
	}
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
	-->
</script>