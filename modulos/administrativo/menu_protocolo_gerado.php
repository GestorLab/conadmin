<?
	$localModulo		= 1;
	$localOperacao		= 162;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	$array_operacao		= array("162", "1", "2") ;
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica_menu.php");
	
	$local_Login		= $_SESSION["Login"];
	$local_IdContrato	= $_GET["IdContrato"];
	$local_IdPessoa		= $_GET["IdPessoa"];
	$local_IdProtocolo	= $_GET["IdProtocolo"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		
		<style type='text/css'>
			input[type=button] {
				height:25px;
				cursor:pointer; 
			}
		</style>
	</head>
	<body onLoad="ativaNome('Protocolo')">
		<? include('filtro_protocolo.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Protocolo de Atendimento.</p>
			<p id='p2'>Nº <?=$local_IdProtocolo?>.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
			<?
				if($local_IdContrato != ''){
					echo "<input type='button' name='bt_voltar' value='Contrato' onClick=\"direcionar('cadastro_contrato.php?IdContrato=$local_IdContrato')\" /> ";
				}
				
				if($local_IdPessoa != ''){
					echo "<input type='button' name='bt_confirmar' value='Pessoa' onClick=\"direcionar('cadastro_pessoa.php?IdPessoa=$local_IdPessoa')\" /> ";
				}
			?>
				</td>
			</tr>
		</table>
		<script type='text/javascript'>
			addParmUrl("marProtocolo","IdProtocolo",'<?=$local_IdProtocolo?>');
			
			function direcionar(local){
				window.location.replace(local);
			}
		</script>
	</body>
</html>