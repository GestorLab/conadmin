<?
	$sql = "SELECT
				NotaFiscal.IdLoja,
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.DataEmissao				
			FROM 
				NotaFiscal
			WHERE
				NotaFiscal.DataEmissao = '".date("Y-m-d")."'";//Nao manda para CR com LC do tipo 3(Debito em conta)
	$res  = mysql_query($sql,$con);		
	$nReg = mysql_num_rows($res);
	while($NotaFiscal = mysql_fetch_array($res)){
		enviaNotasFiscaisEmissaoDiaria($NotaFiscal[IdLoja], $NotaFiscal[DataEmissao],$NotaFiscal[IdNotaFiscal]);
	}
?>