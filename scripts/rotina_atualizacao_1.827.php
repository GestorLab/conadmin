<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				ContratoVigencia.IdLoja,
				ContratoVigencia.IdContrato,
				ContratoVigencia.DataInicio DataInicioVigencia,
				Contrato.DataInicio DataInicioContrato
			from
				ContratoVigencia,
				Contrato
			where
				ContratoVigencia.DataInicio = '0000-00-00' and
				ContratoVigencia.IdLoja = Contrato.IdLoja and
				ContratoVigencia.IdContrato = Contrato.IdContrato";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		
		$sql = "update ContratoVigencia set DataInicio='$lin[DataInicioContrato]' where IdLoja='$lin[IdLoja]' and IdContrato='$lin[IdContrato]' and DataInicio='$lin[DataInicioVigencia]'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);

		if($local_transaction[$tr_i] == false){
			$sql = "delete from ContratoVigencia where IdLoja='$lin[IdLoja]' and IdContrato='$lin[IdContrato]' and DataInicio='$lin[DataInicioVigencia]'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		}
		
		$tr_i++;
	}
		
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true || $tr_i==0){
		$sql = "COMMIT;";
		$local_Erro = 51;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 50;
	}

	echo $sql;
	mysql_query($sql,$con);
?>