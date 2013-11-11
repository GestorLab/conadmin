<?
	include ("../files/conecta.php");
	include ("../files/funcoes.php");

	$MesAno = $_GET['MesAno'];

	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				Pessoa.DiaCobranca
			from 
				Contrato,
				Pessoa
			where
				Contrato.DataBaseCalculo is NULL and
				Contrato.IdPessoa = Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$DiaCobranca = $lin[DiaCobranca]-1;

		if($DiaCobranca < 10){
			$DiaCobranca = "0".$DiaCobranca;
		}

		if($MesAno == ''){
			$MesAno = date('m/Y');
		}

		$DiaCobranca.= "/".$MesAno;

		$DiaCobranca = dataConv($DiaCobranca,'d/m/Y','Y-m-d');

		$sqlUpdate = "update Contrato set  DataBaseCalculo='$DiaCobranca' where IdLoja = '$lin[IdLoja]' and IdContrato='$lin[IdContrato]'";
		mysql_query($sqlUpdate,$con);
	}
?>