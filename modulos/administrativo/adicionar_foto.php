<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	
	include ('../../files/resizeImage.php');
	 
	$local_IdLoja	 		= $_SESSION['IdLoja'];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdProduto 		= $_POST['IdProduto'];
	$local_IdProdutoFoto	= $_POST['IdProdutoFoto'];
	$local_DescricaoFoto	= $_POST['DescricaoFoto'];
	$local_Redimensionar	= $_POST['Redimensionar'];
	
	if($_GET['IdProduto'] != ''){
		$local_IdProduto 		= $_GET['IdProduto'];
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
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/produto_foto.js'></script>
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<div style='padding:3px; margin:0 auto;'>
		<form name='formulario' method='post' action='adicionar_foto.php' onSubmit='return validar()' enctype='multipart/form-data'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='Local' value='ProdutoFoto'>
			<input type='hidden' name='IdProduto' value='<?=$local_IdProduto?>'>
			<input type='hidden' name='IdProdutoFoto' value=''>
			<?
				$sql	=	"select 
								count(IdProdutoFoto) Qtd, 
								DescricaoProduto 
							from 
								Produto,
								ProdutoFoto 
							where 
								Produto.IdLoja = $local_IdLoja and 
								Produto.IdLoja = ProdutoFoto.IdLoja and
								Produto.IdProduto = $local_IdProduto and
								Produto.IdProduto = ProdutoFoto.IdProduto
							group by
								Produto.IdProduto";
				$res	=	mysql_query($sql,$con);
				$lin	=	mysql_fetch_array($res);
				
				echo"<div id='miniaturas' style='width:100%; overflow-x: auto;'>
					<table>
						<tr>";
				
				$Foto		=	"";
				$Descricao	=	"";
						
				if($lin[Qtd] == 0){
					$Foto	=	"../../img/estrutura_sistema/indisponivel.jpg";	
					echo"<td><img src='../../img/estrutura_sistema/indisponivel_p.gif'></td>";
				}else{
					$i	=	0;
					$sqlFoto	=	"select 
										IdProdutoFoto,
										DescricaoFoto,
										ExtFoto 
									from 
										ProdutoFoto
									where 
										IdLoja = $local_IdLoja and 
										IdProduto = $local_IdProduto 
									order by
										IdProdutoFoto ASC";
					$resFoto	=	mysql_query($sqlFoto,$con);
					while($linFoto = mysql_fetch_array($resFoto)){
						if($local_IdProdutoFoto == ''){
							if($i == 0){
								$Foto		=	"../../img/produtos/$local_IdLoja/$local_IdProduto/$linFoto[IdProdutoFoto].$linFoto[ExtFoto]";	
								$Descricao	=	$linFoto[DescricaoFoto];
								echo"<script>
									document.formulario.IdProdutoFoto.value		=	$linFoto[IdProdutoFoto];
									document.formulario.Acao.value				=	'alterar';
								</script>";
							}
						}else{
							if($local_IdProdutoFoto == $linFoto[IdProdutoFoto]){
								$Foto		=	"../../img/produtos/$local_IdLoja/$local_IdProduto/$linFoto[IdProdutoFoto].$linFoto[ExtFoto]";	
								$Descricao	=	$linFoto[DescricaoFoto];
								echo"<script>
									document.formulario.IdProdutoFoto.value		=	$linFoto[IdProdutoFoto];
									document.formulario.Acao.value				=	'alterar';
								</script>";
							}
						}
						$array_fotos[$i] 	= $linFoto[IdProdutoFoto].".".$linFoto[ExtFoto];
						$array_desc[$i]		= $linFoto[DescricaoFoto];
						$array_prod[$i]		= $linFoto[IdProdutoFoto];
				
						echo"<td valign='bottom' style='padding-right:5px'><img id='foto_$i' onClick='visualizarFoto($i)' style='cursor:pointer; width:50px; height:50px' src='../../img/produtos/$local_IdLoja/$local_IdProduto/tumb/$linFoto[IdProdutoFoto].$linFoto[ExtFoto]'></td>";
						$i++;
					}
				}		
						
				echo"	</tr>
					</table>
				</div>
				<HR>";	
				
				echo"
					<p style='padding:3px; font-size:14px; height:14px' class='descCampo'>$lin[DescricaoProduto]</p>
					<div style='height:330px; overflow-y: auto; text-align:center'><img id='foto_G' src='$Foto'></div>
					<HR>
				";			
			?>
			<table id='tabela_foto' style='margin:0 0 0 3px'>
				<tr>
					<td class='descCampo'>Foto [<a href='#' onClick="addFoto()">+</a>]</td>
					<td class='seperador'>&nbsp;</td>
					<td class='descCampo'>Redimensionar</td>
				</tr>
			</table>
			<table style='margin-bottom:0; margin-left:3px'>
				<tr>
					<td class='descCampo'>Descrição Foto</td>
				</tr>
				<tr>
					<td class='descCampo'><input type='text' name='DescricaoFoto' tabindex='3' style='width:489px' maxlength='50' value='<?=$Descricao?>'></td>
				</tr>
			</table>
			<table  style='margin-top:0; margin-left:3px'>
				<tr>
					<td>Você pode fazer upload de arquivo JPG, GIF ou PNG (largura máxima: <?=getCodigoInterno(3,45)?>px).</td>
				</tr>
			</table>
			<table style='width:500px; margin:0 8px 0 3px'>
				<tr>
					<td><h1 id='helpText' name='helpText' style='text-align:left; margin:0'>&nbsp;</h1></td>
					<td class='campo' style='text-align:right'>
						<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='4' onClick='cadastrar()'>
						<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='5' onClick='cadastrar()'>
						<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.IdProduto.value,document.formulario.IdProdutoFoto.value)">
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
<script>
<?
	echo"	
		var prefixo = '../../img/produtos/$local_IdLoja/$local_IdProduto/';
	";	
	$i=0;
	while($array_fotos[$i]){
		echo"
			fotos[$i]   = '".$array_fotos[$i]."';
			desc[$i]  	= '".$array_desc[$i]."';
			produto[$i] = '".$array_prod[$i]."';
		";
		$i++;
	}
?>
	tabela_nova();
	
	verificaErro();
	verificaAcao();
	enterAsTab(document.forms.formulario);
</script>

