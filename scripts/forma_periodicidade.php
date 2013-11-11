<?php
	include ("../files/conecta.php");

	$IdLoja = 1;

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				Servico.Periodicidade
			from
				Contrato,
				Servico
			where
				Contrato.IdLoja = 1 and
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdServico = Servico.IdServico";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlAltera = "update `Contrato` set  `IdPeriodicidade`='$lin[Periodicidade]',  `QtdParcela`='1' where `IdLoja`='$lin[IdLoja]' and `IdContrato`='$lin[IdContrato]' ";
		$local_transaction[$tr_i]	=	mysql_query($sqlAltera,$con);
		$tr_i++;
	}

	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}
	
	if($local_transaction == true){
		echo $sql = "COMMIT;";
		$local_Erro = 47;
	}else{
		echo $sql = "ROLLBACK;";
		$local_Erro = 50;
	}
	mysql_query($sql,$con);
?>