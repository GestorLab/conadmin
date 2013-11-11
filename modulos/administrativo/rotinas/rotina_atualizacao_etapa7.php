<?	
	system("rm -r $Path../www/atualizacao");
	sleep(5);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<body onLoad="proximaEtapa()">&nbsp;</body>
</html>
<script>
	function proximaEtapa(){
		alert("Instalação Concluída!\n\nSistema atualizado para a versão <?=$DescricaoVersao?>.");
		parent.location.replace("../../../index.php");
	}
</script>