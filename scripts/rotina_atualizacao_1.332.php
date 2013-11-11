<?
	include ("../files/conecta.php");
	
	// 0) DiasLimiteBloqueio
	$sql = "select 
				ServicoPeriodicidade.IdLoja,
				ServicoPeriodicidade.IdServico,
				Servico.DiasLimiteBloqueio
			from 
				ServicoPeriodicidade,
				Servico
			where
				Servico.IdLoja = ServicoPeriodicidade.IdLoja and
				Servico.IdServico = ServicoPeriodicidade.IdServico";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update ServicoPeriodicidade set DiasLimiteBloqueio = '$lin[DiasLimiteBloqueio]' where IdLoja='$lin[IdLoja]' and IdServico='$lin[IdServico]'";
		mysql_query($sql,$con);
	}
	
	// 1) TipoContrato
	$sql = "select 
				IdLoja,
				IdServico,
				IdPeriodicidade,
				QtdParcela,
				TipoContrato,
				IdLocalCobranca,
				DiasLimiteBloqueio
			from 
				ServicoPeriodicidade";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update ServicoPeriodicidade set 
					TipoContrato='1' 
				where 
					IdLoja='$lin[IdLoja]' and 
					IdServico='$lin[IdServico]' and 
					IdPeriodicidade='$lin[IdPeriodicidade]' and 
					QtdParcela='$lin[QtdParcela]' and 
					TipoContrato='0' and 
					IdLocalCobranca='0'";
		mysql_query($sql,$con);
		
		$sql = "insert into ServicoPeriodicidade (IdLoja, IdServico, IdPeriodicidade, QtdParcela, TipoContrato, IdLocalCobranca, DiasLimiteBloqueio ) 
				values ('$lin[IdLoja]',  '$lin[IdServico]',  '$lin[IdPeriodicidade]',  '$lin[QtdParcela]',  '2',  '0',  '$lin[DiasLimiteBloqueio]')";
		mysql_query($sql,$con);
	}
	
	// 2) IdLocalCobranca
	$sql = "select 
				IdLoja,
				IdServico,
				IdPeriodicidade,
				QtdParcela,
				TipoContrato,
				IdLocalCobranca,
				DiasLimiteBloqueio
			from 
				ServicoPeriodicidade";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		
		$i = 0;
		$sql2 = "select 
					IdLoja,
					IdLocalCobranca
				from 
					LocalCobranca
				where
					IdLoja = $lin[IdLoja]";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			if($i == 0){
				$sql = "update ServicoPeriodicidade set 
							IdLocalCobranca = '$lin2[IdLocalCobranca]' 
						where 
							IdLoja='$lin[IdLoja]' and 
							IdServico='$lin[IdServico]' and 
							IdPeriodicidade='$lin[IdPeriodicidade]' and 
							QtdParcela='$lin[QtdParcela]' and 
							TipoContrato='$lin[TipoContrato]' and 
							IdLocalCobranca='0'";
				mysql_query($sql,$con);
			}else{
				$sql = "insert into ServicoPeriodicidade (IdLoja, IdServico, IdPeriodicidade, QtdParcela, TipoContrato, IdLocalCobranca, DiasLimiteBloqueio ) 
						values ('$lin[IdLoja]',  '$lin[IdServico]',  '$lin[IdPeriodicidade]',  '$lin[QtdParcela]',  '$lin[TipoContrato]',  '$lin2[IdLocalCobranca]',  '$lin[DiasLimiteBloqueio]')";
				mysql_query($sql,$con);
			}
			$i++;
		}
	}
?>
