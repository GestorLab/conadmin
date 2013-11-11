<?
	$localModulo		=	1;
	$localOperacao		=	208;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdTipoMensagem			= $_POST['IdTipoMensagem'];

	if($_GET['IdTipoMensagem']!=''){
		$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];	
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_tipo_mensagem_parametro.php');
			break;
		default:
			$local_Acao 	 		= 'alterar';
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
		<script type = 'text/javascript' src = 'js/tipo_mensagem_parametro.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_mensagem_parametro_default.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_mensagem_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Parâmetro Mensagem')">
		<? include('filtro_tipo_mensagem.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_tipo_mensagem_parametro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='TipoMensagemParametro'>
				<input type='text' style='display:none;' name='log'>
				<div id='cpDadosTipoMensagem'>
					<div id='cp_tit' style='margin-top:0'>Dados Tipo Mensagem</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B  style='margin-right:23px; color:#000' id='IdLocalCobranca'>Tipo Msg.</B> Descrição Tipo Mensagem</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaTipoMensagem', true, event, null, 118); limpa_form_tipo_mensagem(); busca_tipo_mensagem_lista(); document.formularioTipoMensagem.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdTipoMensagem' value=''  style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'int')" onChange='busca_tipo_mensagem(this.value,true,document.formulario.Local.value);' tabindex='1'><input type='text' class='agrupador' name='DescricaoTipoMensagem' value='' style='width:740px' maxlength='100' readOnly>
							</td>							
						</tr>
					</table>		
				</div>
				<div>
					<div id='cp_tit'>Dados dos Parâmetros</div>	
					<div id='cpDadosParametros' style='display: none'>					
						<table id='tableParametro'>
							<tr>
								<td class='find'>&nbsp;</td>
							<tr>							
						</table>		
					</div>									
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>							
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='100' onClick='cadastrar()'>
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
			<?php
				include("files/busca/tipo_mensagem.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdTipoMensagem != ""){
			echo "busca_tipo_mensagem($local_IdTipoMensagem,false);";			
			echo "busca_tipo_mensagem_parametro_layout($local_IdTipoMensagem,false);";
			echo "scrollWindow('bottom');";	
		}				
	?>
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
