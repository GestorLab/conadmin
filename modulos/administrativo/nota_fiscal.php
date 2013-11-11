<?
	include ("../../files/conecta.php");
	include ("../../files/funcoes.php");

	$MD5 = $_GET['NotaFiscal'];

	$sql = "select
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber,
				NotaFiscal.IdNotaFiscalLayout
			from
				ContaReceberDados,
				NotaFiscal	
			where
				ContaReceberDados.IdLoja = NotaFiscal.IdLoja and 
				ContaReceberDados.IdContaReceber = NotaFiscal.IdContaReceber and
				(
					NotaFiscal.IdStatus = 0 or 
					NotaFiscal.IdStatus = 1
				) and
				NotaFiscal.Md5 = '$MD5';";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)) {
		$file = "nota_fiscal/$lin[IdNotaFiscalLayout]/nota_fiscal_html.php";

		echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html>	
	<body>
		<form action='$file' name='formulario' method='post'>
			<input type='hidden' name='IdLoja'			value='$lin[IdLoja]'>
			<input type='hidden' name='IdContaReceber'	value='$lin[IdContaReceber]'>
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
		header("Location: ../../aplicacoes/aviso/nota_fiscal_inexistente.php?NotaFiscal=$MD5");
	}
?>