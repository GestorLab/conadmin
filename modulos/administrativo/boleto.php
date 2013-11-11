<?
	include ("../../files/conecta.php");
	include ("../../files/funcoes.php");

	$MD5 = $_GET['ContaReceber'];

	$sql = "select
				ContaReceber.IdLoja,
				ContaReceber.IdContaReceber,
				LocalCobranca.IdLocalCobrancaLayout
			from
				ContaReceber,
				LocalCobranca
			where
				ContaReceber.Md5 = '$MD5' and
				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				(
					ContaReceber.IdStatus = 0 or
					ContaReceber.IdStatus = 1 or
					ContaReceber.IdStatus = 2 or
					ContaReceber.IdStatus = 3
				)";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		// Aguardando Pagamento
		if($_GET['Tipo'] == 'pdf'){
			$lin[TipoName] = 'pdf';
		}else{
			$lin[TipoName] = 'index';
		}

	   $file = "local_cobranca/$lin[IdLocalCobrancaLayout]/$lin[TipoName].php";
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
	}else{
		// Direcionar para tela de boleto cancelado
		header("Location: ../../aplicacoes/aviso/boleto_inexistente.php?ContaReceber=$MD5");
	}
?>