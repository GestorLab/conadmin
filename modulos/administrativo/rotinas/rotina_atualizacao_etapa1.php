<?
	include("../../../rotinas/verifica.php");

	$Login = $_SESSION["Login"];

	// Coloca o Sistema em Manutenção
	include("rotina_sistema_manutencao.php");

	// Direciona para a Etapa 2 - ABAIXO
	sleep(4);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<body onLoad="proximaEtapa()">&nbsp;</body>
</html>
<script>
	function proximaEtapa(){
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=2');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=2&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>