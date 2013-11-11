<?
	$localModulo		= 1;
	$localOperacao		= 53;
	$localSuboperacao	= "V";	
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdFormaPagamento			= $_POST['IdFormaPagamento'];
	$local_DescricaoFormaPagamento	= formatText($_POST['DescricaoFormaPagamento'],NULL);
	$local_QTDParcelas				= $_POST['QTDParcelas'];
	
	if($_GET['IdFormaPagamento']!=''){
		$local_IdFormaPagamento	= $_GET['IdFormaPagamento'];	
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_forma_pagamento.php');
			break;		
		case 'alterar':
			include('files/editar/editar_forma_pagamento.php');
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
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/forma_pagamento.js'></script>
		<script type='text/javascript' src='js/forma_pagamento_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Forma de Pagamento')">
		<? include('filtro_forma_pagamento.php'); ?>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_forma_pagamento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>' />
				<input type='hidden' name='Local' value='FormaPagamento' />
				<input type='hidden' name='QTDParcelas' value='0' />
				<input type='hidden' name='TabIndex' value='3' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><b style='color:#000; margin-right:17px'>Forma Pag.</b><b>Nome Forma de Pagamento</b></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdFormaPagamento' value='' autocomplete="off" style='width:73px' maxlength='11' onChange='busca_forma_pagamento(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1' /><input class='agrupador' type='text' name='DescricaoFormaPagamento' value='' style='width:738px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='2' />
							</td>
						</tr>
					</table>
				</div>
				<div id='bl_Parcelas'>
					<div id='cp_tit'>Parcelas</div>
					<table id='tit_Parcelas'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Cadastro de Parcelas [<a style='cursor:pointer;' onClick='add_parcela()'>+</a>]</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
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
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readonly='readonly' />
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='1001' onClick='cadastrar()' />
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='1002' onClick='cadastrar()' />
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='1003' onClick='excluir(document.formulario.IdFormaPagamento.value)' />
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%; height:33px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>	
			</form>
		</div>
		<script type='text/javascript'>
		<?
			if($local_IdFormaPagamento!=''){
				echo "busca_forma_pagamento($local_IdFormaPagamento,false,document.formulario.Local.value);";		
			}
		?>
			verificaAcao();
			inicia();
			verificaErro();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>