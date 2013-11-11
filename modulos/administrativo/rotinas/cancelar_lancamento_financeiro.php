<?
	$localModulo		=	1;
	$localOperacao		=	31;	

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Login					= $_SESSION["Login"];
	$local_Lancamentos				= $_POST['Lancamentos'];
	$local_ObsLancamentoFinanceiro	= $_POST['ObsLancamentoFinanceiro'];
	$local_IdContaEventual			= $_POST['IdContaEventual'];
	$local_IdContaReceber			= $_POST['CancelarContaReceber'];
	$local_IdOrdemServico			= $_POST['IdOrdemServico'];
	$exibir_contaReceber			= 0;
	$local_CancelarOrdemServico		= $_POST['CancelarOrdemServico'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$IdLancamentoFinanceiro 	= explode(",",$local_Lancamentos);
		$IdContaReceber				= explode(",",$local_IdContaReceber);
		$QtdLancamentosFinanceiro 	= count($IdLancamentoFinanceiro);
		$QtdContaReceber			= count($IdContaReceber);
		
		for($i=0; $i<$QtdLancamentosFinanceiro; $i++){
			$sql2 = "select
						IdLancamentoFinanceiro,
						IdOrdemServico,
						ObsLancamentoFinanceiro
					from
						LancamentoFinanceiro
					where
						IdLoja = $local_IdLoja and
						IdLancamentoFinanceiro = ".$IdLancamentoFinanceiro[$i].";";
			$res2 = mysql_query($sql2,$con);
			if($lin2 = mysql_fetch_array($res2)){
				$local_Obs2	=	date("d/m/Y H:i:s")." [".$local_Login."] - Motivo cancelamento: $local_ObsLancamentoFinanceiro";
				
				if($lin2[ObsLancamentoFinanceiro]!=""){
					if($local_Obs2!=""){
						$local_Obs2	.=	"\n";
					}
					$local_Obs2	.=	trim($lin2[ObsLancamentoFinanceiro]);
				}
				
				$sql	=	"update LancamentoFinanceiro set 
								IdStatus		 		= '0',
								ObsLancamentoFinanceiro = '$local_Obs2',
								DataCancelamento 		= concat(curdate(),' ',curtime()),
								LoginCancelamento  		= '$local_Login'
							where 
								IdLoja					='$local_IdLoja' and 
								IdLancamentoFinanceiro	='".$IdLancamentoFinanceiro[$i]."'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
		}
		if($local_IdContaReceber != ''){
			for($i=0; $i<$QtdContaReceber; $i++){
				$sql4 = "select
							IdContaReceber
						from
							ContaReceber
						where
							IdLoja = $local_IdLoja and
							IdContaReceber = ".$IdContaReceber[$i].";";
				$exibir_contaReceber = 1;
				$res4 = mysql_query($sql4,$con);
				if($lin4 = mysql_fetch_array($res4)){
					$local_Obs3	=	date("d/m/Y H:i:s")." [".$local_Login."] - Motivo cancelamento: $local_ObsLancamentoFinanceiro";
					
					if($lin4[ObsLancamentoFinanceiro]!=""){
						if($local_Obs3!=""){
							$local_Obs3	.=	"\n";
						}
						$local_Obs3	.=	trim($lin2[ObsLancamentoFinanceiro]);
					}
					
					$sql	=	"UPDATE ContaReceber SET
									IdStatus						= '0',
									IdStatusConfirmacaoPagamento	=  'NULL',
									Obs								= '$local_Obs3',
									LoginAlteracao					= '$local_Login',
									DataAlteracao					= concat(curdate(),' ',curtime())
								WHERE 
									IdLoja							= '$local_IdLoja' and
									IdContaReceber					= ".$IdContaReceber[$i].";";
									
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}
		}
		
		
		if($lin2[IdOrdemServico]!=""){
			$sql3	=	"select Obs from OrdemServico where IdLoja = $local_IdLoja and IdOrdemServico = $lin2[IdOrdemServico]";
			$res3 	=   mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
			if($QtdLancamentosFinanceiro > 1){
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Cancelou os lançamentos financeiros $local_Lancamentos\n$lin3[Obs]";
			}else{
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Cancelou o lançamento financeiro $local_Lancamentos\n$lin3[Obs]";
			}
			$local_Obs	= str_replace("'",'"',$local_Obs);
			
			$sql	=	"UPDATE OrdemServico SET 							
							Obs						= '$local_Obs'
						WHERE 
							IdLoja					= '$local_IdLoja' and
							IdOrdemServico			= '$lin2[IdOrdemServico]'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		if($local_IdContaEventual != ''){
			$sql = "SELECT
						COUNT(*) Qtd
					FROM 
						LancamentoFinanceiro
					WHERE 
						IdLoja = '$local_IdLoja' AND 
						IdContaEventual = '$local_IdContaEventual' AND 
						IdStatus != 0;";
			$res = mysql_query($sql, $con);
			$lin = mysql_fetch_array($res);
			
			if($lin[Qtd] == 0){
				$sql = "UPDATE ContaEventual SET
							IdStatus		= 0,
							LoginAlteracao	='$local_Login',
							DataAlteracao	= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja = '$local_IdLoja' and
							IdContaEventual = '$local_IdContaEventual'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($lin[IdOrdemServico] =="" && $local_IdOrdemServico ==""){
			$local_CancelarOrdemServico = "";
		}else{
			include('../files/editar/editar_ordem_servico.php');
			if($local_Erro == '5'){
				$local_transaction = false;
			}else{
				$local_transaction = true;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			//$local_Erro = 101;
			$local_Erro = 67;
		}else{
			$sql = "ROLLBACK;";
			//$local_Erro = 102;
			$local_Erro = 68;
		}
		
		
		mysql_query($sql,$con);
		if($exibir_contaReceber > 0){
			header("Location: ../cadastro_cancelar_lancamento_financeiro.php?IdOrdemServico=$local_IdOrdemServico&FormaCobranca=2&Erro=$local_Erro1");
		}else{
			header("Location: ../listar_lancamento_financeiro.php?Lancamentos=$local_Lancamentos&Erro=$local_Erro");
		}
	}
?>