<?
	$localModulo		=	1;
	$localOperacao		=	56;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdMovimentacaoProduto	= $_POST['IdMovimentacaoProduto'];	
	$local_NumeroNF					= formatText($_POST['NumeroNF'],NULL);
	$local_TipoMovimentacao			= formatText($_POST['TipoMovimentacao'],NULL);
	$local_DataNF					= formatText($_POST['DataNF'],NULL);
	$local_IdPessoa					= formatText($_POST['IdPessoa'],NULL);
	$local_ValorTotalICMS			= formatText($_POST['ValorTotalICMS'],NULL);
	$local_SerieNF					= formatText($_POST['SerieNF'],NULL);
	$local_ValorNF					= formatText($_POST['ValorNF'],NULL);
	$local_ValorBaseCalculoICMS		= formatText($_POST['ValorBaseCalculoICMS'],NULL);
	$local_ValorTotalProduto		= formatText($_POST['ValorTotalProduto'],NULL);
	$local_ValorFrete				= formatText($_POST['ValorFrete'],NULL);
	$local_ValorSeguro				= formatText($_POST['ValorSeguro'],NULL);
	$local_ValorTotalIPI			= formatText($_POST['ValorTotalIPI'],NULL);
	$local_ValorOutrasDespesas		= formatText($_POST['ValorOutrasDespesas'],NULL);
	$local_IdEstoque				= formatText($_POST['IdEstoque'],NULL);
	$local_CFOP						= formatText($_POST['CFOP'],NULL);
	$local_Obs						= formatText($_POST['Obs'],NULL);
	$local_QtdProduto				= formatText($_POST['QtdProduto'],NULL);
	$local_Produtos					= formatText($_POST['Produtos'],NULL);
	
	if($_GET['IdMovimentacaoProduto']!=''){
		$local_IdMovimentacaoProduto	= $_GET['IdMovimentacaoProduto'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_nota_fiscal_entrada.php');
			break;		
		case 'alterar':
			include('files/editar/editar_nota_fiscal_entrada.php');
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
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_cnpj.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 		
		<script type = 'text/javascript' src = 'js/nota_fiscal_entrada.js'></script>
		<script type = 'text/javascript' src = 'js/nota_fiscal_entrada_default.js'></script>
		<script type = 'text/javascript' src = 'js/cfop_default.js'></script>	
		<script type = 'text/javascript' src = 'js/produto_default.js'></script>	
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>	
		<script type = 'text/javascript' src = 'js/fornecedor_default.js'></script>
		
		<script type = "text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type = "text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type = "text/javascript" src="../../classes/calendar/calendar-br.js"></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal Entrada')">
		<? include('filtro_nota_fiscal_entrada.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_nota_fiscal_entrada.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='NotaFiscalEntrada'>
				<input type='hidden' name='IdMovimentacaoProduto' value='<?=$local_IdMovimentacaoProduto?>'>
				<input type='hidden' name='QtdProduto' value=''>
				<input type='hidden' name='Produtos' value=''>
				<input type='hidden' name='ValorTotalICMSTemp' value=''>
				<input type='hidden' name='ValorTotalNFTemp' value=''>
				<input type='hidden' name='NumeroSerieTemp' value=''>
				<input type='hidden' name='validar' value='true'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Número NF</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_CPF_CNPJ_Titulo'>CNPJ Fornecedor</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Série NF</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroNF' value='' autocomplete="off" style='width:120px' maxlength='11' onChange="busca_nota_fiscal_entrada(this.value,document.formulario.IdPessoa.value,document.formulario.SerieNF.value,'',true,document.formulario.Local.Value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:170px' maxlength='18' onChange="validar_CNPJ(this.value); busca_fornecedor('',false,document.formulario.Local.value,this.value); busca_nota_fiscal_entrada(document.formulario.NumeroNF.value,document.formulario.IdPessoa.value,document.formulario.SerieNF.value,'',true,document.formulario.Local.Value)" onkeypress="mascara(this,event,'cnpj')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='SerieNF' value='' autocomplete="off" style='width:50px' maxlength='2' onChange="busca_nota_fiscal_entrada(document.formulario.NumeroNF.value,document.formulario.IdPessoa.value,this.value,'',true,document.formulario.Local.Value)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_nota_fiscal'>
					<div id='cp_tit'>Dados da Nota Fiscal</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>CFOP</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoMovimentacao' style='width:104px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
									<option value=''></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=65 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaCFOP', true, event, null, 165); document.formularioCFOP.Nome.value=''; document.formularioCFOP.CFOP.value=''; valorCampoCFOP=''; busca_cfop_lista(); document.formularioCFOP.CFOP.focus();"></td>
							<td class='campo'>
								<input type='text' name='CFOP' value='' autocomplete="off" style='width:100px' maxlength='9' onkeypress="mascara(this,event,'cfop')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="busca_cfop(this.value,false,document.formulario.Local.value)" tabindex='5'><input class='agrupador' type='text' name='NaturezaOperacao' value='' autocomplete="off" style='width:574px' maxlength='255' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='tit_DataNF'>Data</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor Total NF (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Estoque</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' id='cpDataNF' value='' style='width:95px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('tit_DataNF',this)" tabindex='6'>
							</td>
							<td class='find' id='cpDataNFIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
					    	    inputField     : "cpDataNF",
					    	    ifFormat       : "%d/%m/%Y",
					    	    button         : "cpDataNFIco"
					    	});
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorNF' value='' style='width:157px' maxlength='20' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdEstoque' style='width:300px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
									<option value=''></option>
									<?
										$sql = "select IdEstoque, DescricaoEstoque from Estoque where IdLoja=$local_IdLoja order by DescricaoEstoque ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdEstoque]'>$lin[DescricaoEstoque]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_fornecedor'>
					<div id='cp_tit'>Dados do Fornecedor [<a href='cadastro_fornecedor.php' target='_blank'>+</a>]</div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B>Fornecedor</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_CNPJ'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaFornecedor', true, event, null, 287); limpa_form_fornecedor(); busca_pessoa_lista(); document.formularioFornecedor.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_fornecedor(this.value,'false',document.formulario.Local.value); busca_nota_fiscal_entrada(document.formulario.NumeroNF.value,this.value,document.formulario.SerieNF.value,'',true,document.formulario.Local.Value)\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='9'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CPF_CNPJFornecedor' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B>Fornecedor</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Nome Fantasia</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_CNPJ'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaFornecedor', true, event, null, 287); limpa_form_fornecedor(); busca_pessoa_lista(); document.formularioFornecedor.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_fornecedor(this.value,'false',document.formulario.Local.value); busca_nota_fiscal_entrada(document.formulario.NumeroNF.value,this.value,document.formulario.SerieNF.value,'',true,document.formulario.Local.Value)\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='9'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CPF_CNPJFornecedor' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						switch(getCodigoInterno(3,24)){
							case 'Nome':
								echo "$razao";
								break;
							default:
								echo "$nome";
						}
					?>
				</div>	
				<div id='cp_produto'>
					<div id='cp_tit'>Dados do Produto [<a href='cadastro_produto.php' target='_blank'>+</a>]</div>
					<table id='tabelaProduto' cellspacing='0' cellpading='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Produto</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Unid.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>QTD.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor Unit. (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Alíq. ICMS (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Alíq. IPI (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor IPI (<?=getParametroSistema(5,1)?>)</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
					<table style='width:841px; text-align:right'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_add' value='Adicionar' class='botao' tabindex='300' onClick='verifica_addProduto()'>
							</td>
						</tr>
					</table>
				</div>	
				<div id='cp_valores'>
					<div id='cp_tit'>Valores da Nota Fiscal</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Valor Base Cálc. ICMS (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor ICMS (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total Produto (<?=getParametroSistema(5,1)?>)</td>
							<td class='descCampo'>&nbsp;</td>
							<td class='descCampo'><B>Valor Frete (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='descCampo'>&nbsp;</td>
							<td class='descCampo'><B>Valor Seguro (<?=getParametroSistema(5,1)?>)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorBaseCalculoICMS' value='' style='width:155px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalICMS' value='' style='width:145px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='301'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalProduto' value='' style='width:157px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFrete' value='' style='width:145px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calcula_total_nf()" tabindex='302'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorSeguro' value='' style='width:145px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calcula_total_nf()" tabindex='303'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Valor Outras Despesas (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total IPI (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total NF (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:155px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calcula_total_nf()" tabindex='304'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalIPI' value='' style='width:145px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalNF' value='' style='width:157px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_observacoes'>
					<div id='cp_tit'>Observações e Log</div>						
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Observações</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width: 816px;' rows=5 tabindex='305' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='306' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='307' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='308' onClick="excluir(document.formulario.IdMovimentacaoProduto.value)">
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
				<div id='quadros_fluantes'>
					<?
						include("files/busca/numero_serie.php");
					?>
				</div>
			</div>
		</form>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/cfop.php");
				include("files/busca/fornecedor.php");
				include("files/busca/produto.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdMovimentacaoProduto!=''){
		echo "busca_nota_fiscal_entrada('','','',$local_IdMovimentacaoProduto,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";			
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
