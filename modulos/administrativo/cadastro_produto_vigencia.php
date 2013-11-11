<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdProduto					= $_POST['IdProduto'];
	$local_DescricaoProduto				= formatText($_POST['DescricaoProduto'],NULL);
	$local_DataInicio					= formatText($_POST['DataInicio'],NULL);
	$local_DataTermino					= formatText($_POST['DataTermino'],NULL);
	$local_DataLimiteDesconto			= formatText($_POST['DataLimiteDesconto'],NULL);
	$local_IdProdutoTipoVigencia		= formatText($_POST['IdProdutoTipoVigencia'],NULL);
	$local_Valor						= formatText($_POST['Valor'],NULL);
	$local_ValorDesconto				= formatText($_POST['ValorDesconto'],NULL);
	
	if($_GET['IdProduto']!=''){
		$local_IdProduto	= $_GET['IdProduto'];	
	}
	if($_GET['DataInicio']!=''){
		$local_DataInicio	= $_GET['DataInicio'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_produto_vigencia.php');
			break;		
		case 'alterar':
			include('files/editar/editar_produto_vigencia.php');
			break;
		default:
			$local_Acao 	 		= 'inserir';
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/produto_default.js'></script>
		<script type = 'text/javascript' src = 'js/produto_tipo_vigencia_default.js'></script>
		<script type = 'text/javascript' src = 'js/produto_vigencia_default.js'></script>
		<script type = 'text/javascript' src = 'js/produto_vigencia.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Produto/Vigência')">
		<? include('filtro_produto_vigencia.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_produto_vigencia.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='ProdutoVigencia'>
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'>Dados do Produto</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdProduto'><B>Produto</B></B>Nome Produto</td>
						</tr>
						<tr>
							<td class='find' onClick="janela_busca_produto();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdProduto' value=''  style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'int')" onChange='busca_produto_vigencia(this.value,document.formulario.DataInicio.value,true,document.formulario.Local.value)' tabindex='1'><input type='text' class='agrupador' name='DescricaoProduto' value='' style='width:740px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>		
				</div>
				<div  id='cpDadosValores'>
					<div id='cp_tit'>Dados da Vigência</div>	
					<table>
						<tr>
							
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='DataInicio'>Data Início</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='DataTermino'>Data Término</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor Desconto (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Desconto (%)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Final (<?=getParametroSistema(5,1)?>)</td>
							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' id='cpDataInicio' value='' style='width:95px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataInicio',this); busca_produto_vigencia(document.formulario.IdProduto.value,formatDate(this.value),'false',document.formulario.Local.value)" tabindex='2'>
							</td>
							<td class='find' id='cpDataInicioIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataInicio",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataInicioIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' id='cpDataTermino' value='' style='width:95px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataTermino',this); verificaDataFinal(document.formulario.DataInicio.value,this.value)" tabindex='3'>
							</td>
							<td class='find' id='cpDataTerminoIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataTermino",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataTerminoIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:125px' maxLength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)"  onBlur="Foco(this,'out'); calculaDesconto(this)" tabindex='4'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDesconto' value='' style='width:125px'  maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)"  onBlur="Foco(this,'out'); calculaDesconto(this)" tabindex='5'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescontoPerc' value='' style='width:123px'  maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)"  onBlur="Foco(this,'out'); calculaDesconto(this)" tabindex='6'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:123px' maxLength='16' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:30px'>Tipo Vig.</B>Nome Tipo Vigência Produto</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Dia Limite Desconto</td>
						</tr>
						<tr valign='top'>
							<td class='find' onClick="janela_busca_produto_tipo_vigencia();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdProdutoTipoVigencia' value='' style='width:70px'  maxlength='11' onChange='busca_produto_tipo_vigencia(this.value,false,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'><input type='text' class='agrupador' name='DescricaoProdutoTipoVigencia' value='' style='width:306px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLimiteDesconto' value='' autocomplete="off" style='width:120px' maxlength='5' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'numerico')" tabindex='8'><BR><B style='font-size:9px; font-weight:normal'>Base Vencimento</b>
							</td>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='9' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='10' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='11' onClick="excluir(document.formulario.IdProduto.value,formatDate(document.formulario.DataInicio.value))">
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
				<div id='cp_vigencia_cadastrada' style='margin-bottom:0'>
					<div id='cp_tit' style='margin-bottom:0'>Vigênicas Cadastradas</div>
					<table id='tabelaVigencia' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Data Início</td>
							<td>Data Término</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor Final (<?=getParametroSistema(5,1)?>)</td>
							<td>Tipo Vigênica</td>
							<td>Dia Limite Desc.</td>
							<td  class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaVigenciaTotal'>Total: 0</td>
							<td id='tabelaVigenciaValor' class='valor'>0,00</td>
							<td id='tabelaVigenciaValorDesconto' class='valor'>0,00</td>
							<td id='tabelaVigenciaValorFinal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<table style='display:none' id='tabelahelpText2'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdProduto!="" && $local_DataInicio != ""){
			echo "busca_produto_vigencia($local_IdProduto,'".dataConv($local_DataInicio,'d/m/Y','Y-m-d')."',false,document.formulario.Local.value);";
			echo "listarVigencia($local_IdProduto,false);";
		}else{
			if($local_IdProduto != ""){
				echo "busca_produto($local_IdProduto,false);";
			}
		}
	?>
	
	function status_inicial(){
		if(document.formulario.Valor.value == ''){
			document.formulario.Valor.value = '0,00';
		}
		if(document.formulario.ValorDesconto.value == ''){
			document.formulario.ValorDesconto.value = '0,00';
		}
		if(document.formulario.DescontoPerc.value == ''){
			document.formulario.DescontoPerc.value = '0,00';
		}
		if(document.formulario.ValorFinal.value == ''){
			document.formulario.ValorFinal.value = '0,00';
		}
		if(document.formulario.DataLimiteDesconto.value == ''){
			document.formulario.DataLimiteDesconto.value =	'<?=getCodigoInterno(3,50)?>';
		}
	}
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
