<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_IdPlanoContaArray	= explode(".",$local_IdPlanoConta);
		$QtdNiveisPlano				= count($local_IdPlanoContaArray);
		$QtdNiveisPlanoConta 		= getParametroSistema(5,2);
		
		$local_IdPlanoContaPai = "";
		
		for($i=0;$i<$QtdNiveisPlano-1;$i++){
			$local_IdPlanoContaPai .= $local_IdPlanoContaArray[$i];	
			if($i+1<$QtdNiveisPlano-1){
				$local_IdPlanoContaPai .= ".";
			}
		}
		
		$sql	=	"select IdPlanoConta, TipoPlanoConta, AcaoContabil from PlanoConta where IdLoja = $local_IdLoja and IdPlanoConta='$local_IdPlanoContaPai'";
		$res	=	@mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
		
			$tr_i = 0;
		
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			
			$sql = "UPDATE PlanoConta SET TipoPlanoConta=2 WHERE IdLoja=$local_IdLoja AND IdPlanoConta='$local_IdPlanoContaPai';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
				
			$local_TipoPlanoConta = 1;
			
			if($local_IdAcessoRapido == ""){
				$local_IdAcessoRapido = "NULL";
			}else{
				$local_IdAcessoRapido = "'$local_IdAcessoRapido'";
			}
				
			$sql	=	"INSERT INTO PlanoConta SET
							IdLoja					= 	$local_IdLoja,
							IdPlanoConta			=	'$local_IdPlanoConta',
							DescricaoPlanoConta		=	'$local_DescricaoPlanoConta', 
							IdAcessoRapido			=	$local_IdAcessoRapido,
							AcaoContabil			=	$lin[AcaoContabil],
							TipoPlanoConta			= 	$local_TipoPlanoConta,
							LoginCriacao			=	'$local_Login',
							DataCriacao				=	(concat(curdate(),' ',curtime())),
							DataAlteracao			= NULL, 
							LoginAlteracao			= NULL";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;	
				}
			}
			
			if($local_transaction == true){
				$local_Erro = 3;		// Mensagem de Inserção Positiva
				$sql = "COMMIT;";
			}else{
				$local_Erro = 8;		// Mensagem de Inserção Negativa
				$sql = "ROLLBACK;";
			}	
			mysql_query($sql,$con);			
		}else{
			$local_Acao	=	'inserir';
			$local_Erro	=	56;
		}		
	}
?>
