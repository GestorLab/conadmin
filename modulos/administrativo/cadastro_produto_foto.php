<?
	$localModulo		=	1;
	$localOperacao		=	96;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../files/resizeImage.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdProduto		= $_POST['IdProduto'];
	$local_IdProdutoFoto	= $_POST['IdProdutoFoto'];
	$local_DescricaoFoto	= $_POST['DescricaoFoto'];
	$local_Redimensionar	= $_POST['Redimensionar'];
	
	if($_GET['IdProduto']!=''){
		$local_IdProduto		= $_GET['IdProduto'];	
	}
	if($_GET['IdProdutoFoto']!=''){
		$local_IdProdutoFoto	= $_GET['IdProdutoFoto'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_produto_foto.php');
			break;		
		case 'alterar':
			include('files/editar/editar_produto_foto.php');
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
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />

		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/produto_foto_default.js'></script>
		<script type = 'text/javascript' src = 'js/produto_foto.js'></script>
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
	<body  onLoad="ativaNome('Produto/Foto')">
		<? include('filtro_produto_foto.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_produto_foto.php' onSubmit='return validar()'  enctype='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='ProdutoFoto'>
				<input type='hidden' name='ExtFoto' value=''>
				<input type='hidden' name='EndFoto' value=''>
				<div>
					<table style='margin:0;' cellpadding='0' cellspacing='0'>
						<tr>
							<td valign='top'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='color:#00; margin-right:35px'>Produto</B>Nome Produto</td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaProduto', true, event, null, 92); limpa_form_produto(); busca_produto_lista(); document.formularioProduto.DescricaoProduto.focus();"></td>
										<td class='campo'>
											<input type='text' name='IdProduto' value='' autocomplete="off" style='width:73px' maxlength='11' onChange='busca_produto(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoProduto' value='' style='width:620px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='color:#000; margin-right:22px'>Foto</B><B>Arquivo</B></td>
										<td class='seperador'>&nbsp;</td>
										<td class='descCampo'><B>Redimensionar</B></td>
										<td class='seperador'>&nbsp;</td>
										<td class='descCampo'>Descrição Foto</td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' class='foto' name='IdProdutoFoto' style='width:40px' value='' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2' onChange="busca_produto_foto(document.formulario.IdProduto.value,this.value)">
											<input type='text' class='descfoto' name='NomeArquivo' style='width:240px' value='' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2' onChange="busca_produto_foto(document.formulario.IdProduto.value,this.value)">
											<div id='bt_foto' name='btfoto' tabindex='5'></div>
											<input type='file' class='realuploadFoto' name='Foto' value='' size='1' maxlength='100' onChange="document.formulario.NomeArquivo.value = this.value; document.formulario.EndFoto.value=document.formulario.Foto.value; ativa_imagem(document.formulario.EndFoto.value);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
										</td>
										<td class='seperador'>&nbsp;</td>
										<td class='campo'>
											<select name='Redimensionar' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
												<option value='0'></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=60 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='seperador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DescricaoFoto' value='' style='width:206px' maxlength='50'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td style='padding-left:5px;'>Upload de arquivo JPG, GIF ou PNG (largura máxima: <?=getCodigoInterno(3,45)?>px).</td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td>
								<div id='quadros'>
									<div  id='quadroFoto'>
										<div style='padding-top:5px'>
											<img style='border:1px #A4A4A4 solid; width:100px; cursor:pointer' src='../../img/estrutura_sistema/sem_foto.gif' alt='Foto' id='quadroFotoFoto' onClick="janelas('foto.php',540,540,100,100,'?IdProduto='+document.formulario.IdProduto.value+'&EndFoto='+document.formulario.EndFoto.value,'yes');">
										</div>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='22' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='23' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='24' onClick="excluir(document.formulario.IdProduto.value,document.formulario.IdProdutoFoto.value)">
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
	if($local_IdProduto!='' && $local_IdProdutoFoto !=''){
		echo "busca_produto_foto($local_IdProduto,$local_IdProdutoFoto,false,document.formulario.Local.value);";		
	}else{
		if($local_IdProduto!=''){
			echo "busca_produto($local_IdProduto,false,document.formulario.Local.value);";		
		}
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
