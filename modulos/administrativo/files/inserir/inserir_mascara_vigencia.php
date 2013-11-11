<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_Mes = explode('К',$local_Mes);
		$local_Mes = $local_Mes[0];	
		
		if($local_IdTipoDesconto == 1){
			$local_LimiteDesconto	=	dataConv($local_LimiteDesconto,'d/m/Y','Y-m-d');
		}
		
		if($local_IdTipoDesconto == 3){
			$local_Fator	=	1;
		}
		
		if($local_IdRepasse == 1) {
			$local_ValorRepasseTerceiro = str_replace(array('.',','), array('','.'), $local_ValorRepasseTerceiro);
			$local_PercentualRepasseTerceiro = "NULL";
		} elseif($local_IdRepasse == 2) { 
			$local_ValorRepasseTerceiro = "NULL";
			$local_PercentualRepasseTerceiro = str_replace(array('.',','), array('','.'), $local_PercentualRepasseTerceiro);
		} else {
			$local_ValorRepasseTerceiro = "NULL";
			$local_PercentualRepasseTerceiro = "NULL";
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql	=	"
				INSERT INTO ServicoMascaraVigencia SET 
					IdLoja						= '$local_IdLoja',
					IdServico					= '$local_IdServico',
					Fator						= '$local_Fator',
					Mes							= '$local_Mes',
					IdTipoDesconto				= '$local_IdTipoDesconto',
					IdContratoTipoVigencia		= '$local_IdContratoTipoVigencia',
					LimiteDesconto				= '$local_LimiteDesconto',
					ValorRepasseTerceiro		= $local_ValorRepasseTerceiro,
					PercentualRepasseTerceiro	= $local_PercentualRepasseTerceiro,
					VigenciaDefinitiva			= '$local_VigenciaDefinitiva',
					DataCriacao					= concat(curdate(),' ',curtime()),
					LoginCriacao				= '$local_Login';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"UPDATE ServicoMascaraVigencia SET							
								VigenciaDefinitiva		= '2'							
						WHERE 
								IdLoja					=  '$local_IdLoja' and
								IdServico				=  '$local_IdServico' and
								Mes						!= '$local_Mes'"; 							
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
				
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			// Muda a aчуo para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		}else{
			$sql = "ROLLBACK;";
			// Muda a aчуo para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
		mysql_query($sql,$con);	
	}
?>