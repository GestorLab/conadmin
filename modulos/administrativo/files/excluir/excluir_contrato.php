<?
	$localModulo		=	1;
	$localOperacao		=	2;

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	@include ('../../../../files/funcoes_personalizadas.php');
	include ('../../../../rotinas/verifica.php');

	$local_Login 		=	$_SESSION["Login"];
	$local_IdLoja		=	$_SESSION["IdLoja"];
	$local_IdContrato	=	$_GET['IdContrato'];
	$Contrato			=	"";
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$ii	  =	0;
		$tr_i = 0;	
		
		$Contrato	=	"_".$local_IdContrato;
		
		$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato";
		$res2 	= 	@mysql_query($sql2,$con);
		while($lin2  = 	@mysql_fetch_array($res2)){
			
			$Contrato	.=	"_".$lin2[IdContratoAutomatico];
			
			$sql3 = "select IdServico from Contrato where Contrato.IdLoja = $local_IdLoja and Contrato.IdContrato = $lin2[IdContratoAutomatico]";
			$res3 = mysql_query($sql3,$con);
			$lin3 = mysql_fetch_array($res3);
		
			$local_IdServicoTemp[$ii] = $lin3[IdServico];
			$local_IdServico		  = $lin3[IdServico];
			$ii++;
			
			$sql	=	"DELETE FROM ContratoParametro WHERE IdLoja = $local_IdLoja and IdContrato=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql	=	"DELETE FROM ContratoParametroLocalCobranca WHERE IdLoja = $local_IdLoja and IdContrato=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
	
			$sql	=	"DELETE FROM ContratoVigencia WHERE IdLoja = $local_IdLoja and IdContrato=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
	
			$sql	=	"DELETE FROM ContratoAutomatico WHERE IdLoja = $local_IdLoja and IdContrato = $local_IdContrato and IdContratoAutomatico=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql	=	"UPDATE Contrato set IdContratoAgrupador=NULL where IdLoja= $local_IdLoja and IdContratoAgrupador = $local_IdContrato";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			$sql	=	"DELETE FROM ContratoStatus WHERE IdLoja = $local_IdLoja and IdContrato=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			$sql	=	"DELETE FROM Contrato WHERE IdLoja = $local_IdLoja and IdContrato=$lin2[IdContratoAutomatico];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			$sql = "select
						UrlRotinaCancelamento
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = ".$local_IdServico;
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			if($lin[UrlRotinaCancelamento] != ''){
				include("../../".$lin[UrlRotinaCancelamento]);
			}
		}
		
		$sql3 = "select IdServico from Contrato where Contrato.IdLoja = $local_IdLoja and Contrato.IdContrato = $local_IdContrato";
		$res3 = mysql_query($sql3,$con);
		$lin3 = mysql_fetch_array($res3);
		
		$local_IdServicoTemp[$ii]	= $lin3[IdServico];
		$local_IdServico			= $lin3[IdServico];
		$ii++;
				
		$sql	=	"DELETE FROM ContratoParametro WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM ContratoParametroLocalCobranca WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql	=	"DELETE FROM ContratoVigencia WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql	=	"UPDATE Contrato set IdContratoAgrupador=NULL where IdLoja= $local_IdLoja and IdContratoAgrupador = $local_IdContrato";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM ContratoStatus WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql	=	"DELETE FROM Contrato WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

		$sql = "select
					UrlRotinaCancelamento
				from
					Servico
				where
					IdLoja = $local_IdLoja and
					IdServico = ".$local_IdServico;
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		if($lin[UrlRotinaCancelamento] != ''){
			include("../../".$lin[UrlRotinaCancelamento]);
		}

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
