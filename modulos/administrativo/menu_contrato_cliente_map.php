<?php
	$localModulo		= 1;
	$localOperacao		= 195;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$localIdServico		= $_POST["filtro_id_servico"];
	$localIdEstado		= $_POST["filtro_estado"];
	$localNomeCidade	= $_POST["filtro_cidade"];
	$localIdStatus		= $_POST["filtro_status"];
	$localLimit			= $_POST["filtro_limit"];
	$where				= "";
	$focoMap			= "{pais: '{\$lin[\"NomePais\"]}'";
	
	if($localIdEstado != ''){
		$where .= " and Estado.IdEstado = '$localIdEstado'";
		$focoMap .= ", uf: '{\$lin[\"SiglaEstado\"]}'";
	}
	
	if($localNomeCidade != ''){
		$where .= " and Cidade.NomeCidade LIKE '%$localNomeCidade%'";
		$focoMap .= ", cidade: '{\$lin[\"NomeCidade\"]}'";
	}
	
	$focoMap .= "}";
	
	$sql = "SELECT 
				Pais.NomePais, 
				Estado.SiglaEstado, 
				Cidade.NomeCidade
			FROM 
				Loja, 
				Pessoa, 
				PessoaEndereco, 
				Pais,
				Estado,
				Cidade 
			WHERE
				Loja.IdLoja = 1 AND Loja.IdPessoa = Pessoa.IdPessoa AND 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
				Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco AND 
				PessoaEndereco.IdPais = Pais.IdPais AND 
				Pais.IdPais = Estado.IdPais AND 
				Estado.IdPais = Cidade.IdPais AND 
				Estado.IdEstado = Cidade.IdEstado  
				$where
			LIMIT 1";
	$res = mysql_query($sql, $con);
	$lin = mysql_fetch_array($res);
	
	eval("\$focoMap = \"{$focoMap}\";");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/contrato_cliente_map.js'></script>
		
		<script type="text/javascript">
			<?php echo "initialize_map('map_canvas', get_lat_lng, {$focoMap});"; ?>
		</script>
		
		<style type="text/css">
			html, body { 
				height: 100%; 
				margin: 0; 
				padding: 0; 
			}
			.impress { 
				padding: 0 2px 2px 2px; 
				cursor: pointer; 
				border: 1px #A4A4A4 solid; 
				height: 20px;
			}
			@media print { 
				html, body { 
					height: auto; 
				}
			}
		</style>
	</head>
	<body onLoad="ativaNome('Contrato/Mapeamento');">
		<?php include('filtro_contrato_cliente_map.php'); ?>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>