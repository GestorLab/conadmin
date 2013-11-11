<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	
	 
	$local_IdLoja	 		= $_SESSION['IdLoja'];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdProduto		= $_GET['IdProduto'];
	$local_EndFoto			= $_GET['EndFoto'];

	if($local_EndFoto != ""){
		$local_IdProdutoFotoTemp	=	explode("/",$local_EndFoto);
		$local_IdProdutoFotoTemp	=	$local_IdProdutoFotoTemp[6];
		$local_IdProdutoFoto		=	explode(".",$local_IdProdutoFotoTemp);	
		$local_IdProdutoFoto		=	$local_IdProdutoFoto[0];
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
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<div style='padding:3px; margin:0 auto;'>
			<form name='formulario'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='ProdutoFoto'>
				<input type='hidden' name='IdProduto' value='<?=$local_IdProduto?>'>
				<input type='hidden' name='IdProdutoFoto' value='<?=$local_IdProdutoFoto?>'>
			</form>
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
				$res	=	@mysql_query($sql,$con);
				$lin	=	@mysql_fetch_array($res);
				
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
					<div style='padding:3px; width:90%; height:auto; font-size:14px;' class='descCampo'>$lin[DescricaoProduto]</div>
					<div style='height:400px; overflow-y: auto; text-align:center'><img id='foto_G' src='$Foto'></div>
					<div id='DescricaoFoto' style='text-align:center; padding-top:2px'>$Descricao</div>
				";			
			?>
		</div>
	</body>
</html>
<script>
	var fotos 	= new  Array();
	var desc 	= new  Array();
	var produto	= new  Array();
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
	function visualizarFoto(pos){
		document.getElementById('foto_G').src 				= prefixo + fotos[pos];
		document.getElementById('DescricaoFoto').innerHTML	= desc[pos]; 	
		document.formulario.IdProdutoFoto.value				= produto[pos]; 	
	}
</script>

