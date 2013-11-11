<?
	$localModulo		=	1;
	$localOperacao		=	55;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	$local_IdProduto	= $_POST['IdProduto'];
	
	if($_GET['IdProduto']!=''){
		$local_IdProduto	= $_GET['IdProduto'];	
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_produto_tabela_preco.php');
			break;
		default:
			$local_Acao = 'alterar';
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/produto_tabela_preco.js'></script>
		<script type = 'text/javascript' src = 'js/produto_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Produto/Tabela Preço')">
		<? include('filtro_produto.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_produto_tabela_preco.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='ProdutoTabelaPreco'>
				<input type='hidden' name='ValorMinimo' value='<?=getCodigoInterno(3,48)?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#00; margin-right:35px'>Produto</B>Nome Produto</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaProduto', true, event, null, 92); limpa_form_produto(); busca_produto_lista(); document.formularioProduto.DescricaoProduto.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdProduto' value='' autocomplete="off" style='width:73px' maxlength='11' onChange='busca_produto(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoProduto' value='' style='width:738px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_ultima_compra'>
					<div id='cp_tit'>Última Compra</div>				
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Fornecedor</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor (<?=getParametroSistema(5,1);?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Médio (<?=getParametroSistema(5,1);?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdUltimoFornecedor' value='' style='width:70px' autocomplete="off" maxlength='11'  readOnly><input class='agrupador' type='text' name='NomeFornecedor' value='' style='width:348px' maxlength='100' readOnly>
							</td>
							<td class='separador'>
							<td class='campo'>
								<input type='text' name='ValorPrecoUltimaCompra' value='' style='width:121px' maxlength='16' readOnly>
							</td>
							<td class='separador'>
							<td class='campo'>
								<input type='text' name='DataUltimaCompra' value='' style='width:100px' maxlength='16' readOnly>
							</td>
							<td class='separador'>
							<td class='campo'>
								<input type='text' name='ValorPrecoMedio' value='' style='width:121px' maxlength='16' readOnly>
							</td>
						</tr>
					</table>
				</div>	
				<?
					$tabindex	=	2;
					
					$sql	=	"select IdTabelaPreco,DescricaoTabelaPreco from TabelaPreco where IdLoja = $local_IdLoja order by DescricaoTabelaPreco ASC";
					$res	=	mysql_query($sql,$con);
					while($lin	=	mysql_fetch_array($res)){
						echo"
							<div>
								<div id='cp_tit'>Tabela Preço: $lin[DescricaoTabelaPreco]</div>
								<table>";
						$sql2	=	"select IdFormaPagamento,DescricaoFormaPagamento from FormaPagamento where IdLoja = $local_IdLoja order by DescricaoFormaPagamento ASC";			
						$res2	=	mysql_query($sql2,$con);
						while($lin2	=	mysql_fetch_array($res2)){
							
							$valor	=	getCodigoInterno(3,48);
							
							echo"
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'>Forma Pagamento</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><B>Preço Mínimo (".getParametroSistema(5,1).")</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><B>Preço (".getParametroSistema(5,1).")</B></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td>
										<input type='text' name='DescricaoFormaPagamento_$lin[IdTabelaPreco]_$lin2[IdFormaPagamento]' value='$lin2[DescricaoFormaPagamento]' style='width:482px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td><input type='text' name='ValorPrecoMinimo_$lin[IdTabelaPreco]_$lin2[IdFormaPagamento]' value='$valor' style='width:150px' maxlength='16' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); preencheValor(this)\" tabindex='$tabindex'></td>
									<td class='separador'>&nbsp;</td>
									<td><input type='text' name='ValorPreco_$lin[IdTabelaPreco]_$lin2[IdFormaPagamento]' value='$valor' style='width:150px' maxlength='16' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); preencheValor(this)\" tabindex='$tabindex'></td>
								</tr>
							";
							$tabindex++;
						}
						echo"	</table>
							</div><BR>	
						";
					}
				?>
				<div id='cp_observacoes'>
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
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='6' onClick='cadastrar()'>
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
				include("files/busca/produto.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdProduto!=''){
		echo "busca_produto($local_IdProduto,false,document.formulario.Local.value);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
