<?
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include('../../rotinas/verifica.php');

	$MD5 = $_GET['OrdemServico'];

	$sql = "select 
				OrdemServico.IdLoja,
				OrdemServico.IdOrdemServico,
				OrdemServico.IdTipoOrdemServico,
				Servico.IdOrdemServicoLayout 
			from 
				OrdemServico left join Servico on (
					Servico.IdLoja = OrdemServico.IdLoja and
					Servico.IdServico = OrdemServico.IdServico and
					Servico.IdOrdemServicoLayout is not null
				)
			where
				OrdemServico.MD5 = '$MD5';";
	$res = @mysql_query($sql, $con);
	if($lin = @mysql_fetch_array($res)){
		if($lin[IdTipoOrdemServico] != "2"){
			$file = "ordem_servico/$lin[IdOrdemServicoLayout]/imprimir_ordem_servico.php";
		} else{
			$file = "ordem_servico/".getCodigoInterno(3,104)."/imprimir_ordem_servico.php";
		}
		
		echo "
			<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
				\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
			<html>	
				<body>
					<form action='$file' name='formulario' method='post'>
						<input type='hidden' name='IdLoja'			value='$lin[IdLoja]'>
						<input type='hidden' name='IdOrdemServico'	value='$lin[IdOrdemServico]'>
					</form>
					<script type='text/javascript'>
					<!--
						document.formulario.submit();
						-->
					</script>
				</body>
			</html>";
	} else{
		// Direcionar para tela de aviso
		header("Location: ../../aplicacoes/aviso/ordem_servico_inexistente.php?OrdemServico=$MD5");
	}
?>