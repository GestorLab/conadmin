<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	
	include ('../../files/resizeImage.php');
	 
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Login					= $_SESSION["Login"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdProduto	 			= formatText($_POST['IdProduto'],NULL);
	$local_DescricaoProduto			= formatText($_POST['DescricaoProduto'],NULL);
	$local_EspecificacaoProduto		= formatText($_POST['EspecificacaoProduto'],NULL);
	$local_Valor					= $_POST['Valor'];
	$local_Desconto					= $_POST['Desconto'];	
	$local_DescricaoFoto 			= formatText($_POST['DescricaoFoto'],NULL);
	$local_EndFoto		    		= formatText($_POST['EndFoto'],NULL);
	$local_Redimensionar    		= formatText($_POST['Redimensionar'],NULL);
	$local_IdFabricante	 			= formatText($_POST['IdFabricante'],NULL);
	$local_IdUnidade	 			= formatText($_POST['IdUnidade'],NULL);
	$local_Garantia		 			= formatText($_POST['Garantia'],NULL);
	$local_IdTipoGarantia		 	= formatText($_POST['IdTipoGarantia'],NULL);
	$local_IdUnidadeGarantia		= formatText($_POST['IdUnidadeGarantia'],NULL);
	$local_DescricaoReduzidaProduto	= formatText($_POST['DescricaoReduzidaProduto'],NULL);
	$local_CodigoBarra		  		= formatText($_POST['CodigoBarra'],NULL);
	$local_QtdMinima		  		= formatText($_POST['QtdMinima'],NULL);
	$local_QtdMaxima		  		= formatText($_POST['QtdMaxima'],NULL);
	$local_PesoKG			  		= formatText($_POST['PesoKG'],NULL);
	$local_SubGrupoProduto	  		= formatText($_POST['SubGrupoProduto'],NULL);
	$local_ObsProduto	  			= formatText($_POST['ObsProduto'],NULL);
	$local_NumeroSerie	  			= formatText($_POST['NumeroSerie'],NULL);
	$local_NumeroSerieObrigatorio	= formatText($_POST['NumeroSerieObrigatorio'],NULL);

	if($_GET['IdProduto']!=''){
		$local_IdProduto	= $_GET['IdProduto'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_produto.php');
			break;		
		case 'alterar':
			include('files/editar/editar_produto.php');
			break;
		default:
			$local_Acao 	= 'inserir';
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
		<script type = 'text/javascript' src = 'js/produto.js'></script>
		<script type = 'text/javascript' src = 'js/produto_default.js'></script>
		<script type = 'text/javascript' src = 'js/fabricante_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Produto')">
		<?include('filtro_produto.php')?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_produto.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>'>	
				<input type='hidden' name='Local' value='Produto'>						
				<input type='hidden' name='qtdFoto' value=''>				
				<input type='hidden' name='SubGrupoProduto' value=''>		
				<input type='hidden' name='EndFoto' value=''>		
				<input type='hidden' name='ValorMinimo' value='<?=getCodigoInterno(3,48)?>'>				
				<div>
					<!-- TABELA DA LUPINHA DO CADASTRO DE PRODUTO -->
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Produto</td>
							<td class='separador'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProduto' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_produto(this.value,true,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_cadastro'>
					<div id='cp_tit'>Dados Cadastrais</div>	
					<!-- TABELA DOS DADOS CADASTRAIS DE PRODUTO -->
					<table style='margin:0;' cellspading=0 cellspacing='0'>
						<tr>
							<td>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Nome Produto</B></td>
									</tr>
									<tr>
										<td class='find'></td>
										<td class='campo'>
											<input type='text' name='DescricaoProduto' value='' style='width:700px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Descrição Reduzida Produto</B></td>
									</tr>
									<tr>
										<td class='find'></td>
										<td class='campo'>
											<input type='text' name='DescricaoReduzidaProduto' value='' style='width:700px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
										</td>
									</tr>
								</table>								
								<!-- TABELA DOS DADOS CADASTRAIS DE MARCA -->
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Fabricante</B>&nbsp;[<a href='cadastro_fabricante.php' target='_blank'>+</a>]</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B>Garantia</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='cpUnidadeGarantia' style='color:#000'>Unidade Garantia</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='cpTipoGarantia' style='color:#000'>Tipo Garantia</B></td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaFabricante', true, event, null, 260); document.formularioFabricante.Nome.value=''; valorCampoFabricante=''; busca_fabricante_lista(); document.formularioFabricante.Nome.focus();"></td>
										<td class='campo'>
											<input type='text' name='IdFabricante' value='' style='width:70px' autocomplete="off" maxlength='11' onChange='busca_fabricante(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'><input class='agrupador' type='text' name='DescricaoFabricante' value='' style='width:348px' maxlength='100' readOnly>
										</td>
										<td class='separador'>
										<td class='campo'>
											<input type='text' name='Garantia' value='' style='width:60px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificaObrigatoriedade(this.value)" tabindex='5'>
										</td>
										<td class='separador'>
										<td class='campo'>
											<select name='IdUnidadeGarantia' style='width: 100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
												<option value='' selected></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=61 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>
										<td class='campo'>
											<select name='IdTipoGarantia' style='width: 80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
												<option value='' selected></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=62 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Código de Barras</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B>Unidade</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B>QTD. Mínima Estoque</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>QTD. Máxima Estoque</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Peso (Kg)</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CodigoBarra' value='' style='width:208px' autocomplete="off" maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='IdUnidade' style='width: 70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'>
												<option value='' selected></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=66 order by ValorParametroSistema ASC";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>
										<td class='campo'>
											<input type='text' name='QtdMinima' value='' style='width:120px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'>
										</td>
										<td class='separador'>
										<td class='campo'>
											<input type='text' name='QtdMaxima' value='' style='width:120px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
										</td>
										<td class='separador'>
											<td class='campo'>
											<input type='text' name='PesoKG' value='' style='width:120px' maxlength='16' onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12'>
										</td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td>
								<div id='quadros'>
									<div  id='quadroFoto'>
										<div>
											<img style='border:1px #A4A4A4 solid; width:100px; cursor:pointer' src='../../img/estrutura_sistema/sem_foto.gif' alt='Foto' id='quadroFotoFoto' onClick="janelas('foto.php',540,540,100,100,'?IdProduto='+document.formulario.IdProduto.value+'&EndFoto='+document.formulario.EndFoto.value,'yes');">
											<p id='quadroFotoDescricao' style='margin:0; text-align:center; width:100px'>&nbsp;</p>
											<table style='width:100%; text-align:center' valign='top'>
												<tr>
													<td><img  id="seta_voltar" style='cursor:pointer' src='../../img/estrutura_sistema/ico_seta_left_c.gif' alt='Volta' onClick="voltarFoto(document.formulario.EndFoto.value)"></td>
													<td><img  id="seta_proximo" style='cursor:pointer' src='../../img/estrutura_sistema/ico_seta_right_c.gif' alt='Próximo' onClick="avancaFoto(document.formulario.EndFoto.value)"></td>
												</tr>
											</table>						
										</div>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Especificações Produto</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='EspecificacaoProduto' style='width: 816px;' rows=5 tabindex='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Número Série</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpNumeroSerieObrigatoriedade' style='color:#000'>Número Série Obrigatório</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='NumeroSerie' style='width: 150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14' onChange="verificaNumeroSerie(this.value)">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=63 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='NumeroSerieObrigatorio' style='width: 150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=64 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>	
				<div>
					<div id='cp_tit'>SubGrupo Produto</div>	
					<table id='titTabelaSubGrupo'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Grupo Produto</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>SubGrupo Produto</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoProduto' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:300px' tabindex='16' onChange="subgrupo_produto(this.value,false,document.formulario.Local.value)">
									<option value='' selected></option>
									<?
										$sql = "select IdGrupoProduto, DescricaoGrupoProduto from GrupoProduto where IdLoja = $local_IdLoja order by DescricaoGrupoProduto ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoProduto]'>$lin[DescricaoGrupoProduto]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdSubGrupoProduto' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:300px' tabindex='17'>
									<option value='0' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add' value='Adicionar' class='botao' tabindex='19' onClick='adicionar_subgrupo(document.formulario.IdGrupoProduto.value,document.formulario.IdSubGrupoProduto.value)'>
							</td>
						</tr>
					</table>
					<table id='tabelaSubGrupo' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 330px'><B>Grupo Produto</B></td>
							<td><B>SubGrupo Produto</B></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='3' id='totaltabelaSubGrupo'>Total: 0</td>
						</tr>
					</table>
				</div>
				<div id='cp_observacoes'>
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
								<textarea name='ObsProduto' style='width: 816px;' rows=5 tabindex='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='22' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='23' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='24' onClick="excluir(document.formulario.IdProduto.value)">
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
				include("files/busca/fabricante.php");
			?>
		</div>
	</body>
</html>
<script language='JavaScript' type='text/javascript'> 
			
<?
	if($local_IdProduto != ''){
		echo "busca_produto($local_IdProduto,false,document.formulario.Local.value);";
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>

