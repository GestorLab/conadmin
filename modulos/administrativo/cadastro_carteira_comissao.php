<?
	$localModulo		=	1;
	$localOperacao		=	35;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdAgenteAutorizado		= $_POST['IdAgenteAutorizado'];
	$local_IdCarteira				= $_POST['IdCarteira'];
	$local_IdServico				= $_POST['IdServico'];
	$local_Parcela					= $_POST['Parcela'];		
	$local_Percentual				= $_POST['Percentual'];
	
	if($_GET['IdAgenteAutorizado']!=''){
		$local_IdAgenteAutorizado	= $_GET['IdAgenteAutorizado'];	
	}
	if($_GET['IdCarteira']!=''){
		$local_IdCarteira			= $_GET['IdCarteira'];	
	}
	if($_GET['IdServico']!=''){
		$local_IdServico			= $_GET['IdServico'];	
	}
	if($_GET['Parcela']!=''){
		$local_Parcela				= $_GET['Parcela'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_carteira_comissao.php');
			break;		
		case 'alterar':
			include('files/editar/editar_carteira_comissao.php');
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
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/carteira_comissao.js'></script>
		<script type = 'text/javascript' src = 'js/carteira_comissao_default.js'></script>
		<script type = 'text/javascript' src = 'js/agente_default.js'></script>
		<script type = 'text/javascript' src = 'js/carteira_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Comissionamento Vendedor')">
		<? include('filtro_carteira_comissao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_carteira_comissao.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='CarteiraComissao'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Agente Autorizado</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Vendedor</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaAgente', true, event, null, 92); limpa_form_agente(); busca_agente_lista(); document.formularioAgente.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdAgenteAutorizado' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_agente(this.value,false,document.formulario.Local.value); busca_carteira_comissao(this.value,document.formulario.IdCarteira.value,document.formulario.IdServico.value,document.formulario.Parcela.value,false);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='Nome' value='' style='width:313px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaCarteira', true, event, null, 92); limpa_form_carteira(); busca_carteira_lista(); document.formularioCarteira.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdCarteira' value=''  style='width:70px' maxlength='11' onChange='busca_carteira(document.formulario.IdAgenteAutorizado.value,this.value,false,document.formulario.Local.value); busca_carteira_comissao(document.formulario.IdAgenteAutorizado.value,this.value,document.formulario.IdServico.value,document.formulario.Parcela.value,false);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' name='NomeCarteira' value='' style='width:314px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:35px'>Serviço</B>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Parcela</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Percentual (%)</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 92); document.formularioServico.DescricaoServico.value=''; valorCampoServico=''; busca_servico_lista(); document.formularioServico.DescricaoServico.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value); busca_carteira_comissao(document.formulario.IdAgenteAutorizado.value,document.formulario.IdCarteira.value,this.value,document.formulario.Parcela.value,false);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:314px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Parcela' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_carteira_comissao(document.formulario.IdAgenteAutorizado.value,document.formulario.IdCarteira.value,document.formulario.IdServico.value,this.value,false);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Percentual' value=''  style='width:100px' maxlength='8' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
							</td>
						</tr>
					</table>
				</div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='7' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='8' onClick="excluir(document.formulario.IdAgenteAutorizado.value,document.formulario.IdCarteira.value,document.formulario.IdServico.value,document.formulario.Parcela.value)">
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
				include("files/busca/agente.php");
				include("files/busca/carteira.php");
				include("files/busca/servico.php");
			?>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdAgenteAutorizado!='' && $local_IdCarteira!='' && $local_IdServico != '' && $local_Parcela !=''){
		echo "busca_carteira_comissao($local_IdAgenteAutorizado,$local_IdCarteira,$local_IdServico,$local_Parcela,false);";		
	}else{
		if($local_IdAgenteAutorizado!=''){
			echo	"busca_agente($local_IdAgenteAutorizado,false);";
		}
		if($local_IdAgenteAutorizado!="" && $local_IdCarteira!=''){
			echo	"busca_carteira($local_IdAgenteAutorizado,$local_IdCarteira,false);";
		}
	}
	
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
