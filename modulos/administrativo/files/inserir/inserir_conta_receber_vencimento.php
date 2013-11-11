<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_DataVencimento					= 	dataConv($local_DataVencimento,'d/m/Y','Y-m-d');
		$local_ValorContaReceber				= 	str_replace(",", ".", $local_ValorVencimento);
		$local_ValorMoraMulta					= 	str_replace(",", ".", $local_ValorMoraMulta);
		$local_ValorDescontoVencimento			= 	str_replace(",", ".", $local_ValorDescontoVencimento);
		$local_ValorJurosVencimento				= 	str_replace(",", ".", $local_ValorJurosVencimento);
		$local_ValorTaxaReImpressaoBoleto		= 	str_replace(",", ".", $local_ValorTaxaReImpressaoBoleto);			
		$local_ValorOutrasDespesas				= 	str_replace(",", ".", $local_ValorOutrasDespesas);
		
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;	
		
		$sqlObs	=	"select Obs from ContaReceber where IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber";
		$resObs	=	mysql_query($sqlObs,$con);
		$linObs	=	mysql_fetch_array($resObs);		
		
		$temp	=	$local_DataVencimentoAntiga;
		$temp2	=	dataConv($local_DataVencimento,'Y-m-d','d/m/Y');
		$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Data Vencimento [$temp > $temp2]\n";
		$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Valor (R$) Atualizado de: [R$ $local_ValorFinal > R$ $local_ValorFinalVencimento]";
		
		if($local_ManterDescontoAConceber == ''){
			$local_ManterDescontoAConceber = "NULL";
		} 
		
		if($linObs[Obs]!="")	$local_Obs	.= "\n".$linObs[Obs];

		$sql	=	"UPDATE ContaReceber SET Obs = '".$local_Obs."' WHERE IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		$sqlVerifica = "SELECT 
							IdLoja,
							IdContaReceber,
							DataVencimento 
						FROM
							ContaReceberVencimento 
						WHERE
							IdLoja						= $local_IdLoja and
							DataVencimento				= '$local_DataVencimento' and
							IdContaReceber				= $local_IdContaReceber";
		$resVerifica = mysql_query($sqlVerifica,$con);
		if(mysql_num_rows($resVerifica) < 1){
			$sql	=	"
					INSERT INTO ContaReceberVencimento SET 
						IdLoja						= $local_IdLoja,
						IdContaReceber				= $local_IdContaReceber,
						DataVencimento				= '$local_DataVencimento',
						ValorContaReceber			= '$local_ValorContaReceber',
						ValorMulta					= '$local_ValorMoraMulta',
						ValorDesconto				= '$local_ValorDescontoVencimento',
						ValorJuros					= '$local_ValorJurosVencimento',
						ValorTaxaReImpressaoBoleto	= '$local_ValorTaxaReImpressaoBoleto',
						ValorOutrasDespesas			= '$local_ValorOutrasDespesas',
						ManterDescontoAConceber		=  $local_ManterDescontoAConceber,
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, 9, $local_Login);
			$tr_i++;
			
			$comm = 3;
			$roll = 8;
		}else{
			$sql=	"UPDATE ContaReceberVencimento SET 
						ValorContaReceber			= '$local_ValorContaReceber',
						ValorMulta					= '$local_ValorMoraMulta',
						ValorDesconto				= '$local_ValorDescontoVencimento',
						ValorJuros					= '$local_ValorJurosVencimento',
						ValorTaxaReImpressaoBoleto	= '$local_ValorTaxaReImpressaoBoleto',
						ValorOutrasDespesas			= '$local_ValorOutrasDespesas',
						ManterDescontoAConceber		=  $local_ManterDescontoAConceber,
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login'
					WHERE
						IdLoja						= $local_IdLoja and
						DataVencimento				= '$local_DataVencimento' and
						IdContaReceber				= $local_IdContaReceber";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$comm = 4;
			$roll = 5;
		}
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;	
			}
		}
		
		
		if($local_transaction == true){
			$sql = "COMMIT;";

			// Muda a ação para Inserir
			$local_DataVencimento	=	"";
			$local_Acao = 'inserir';
			$local_Erro = $comm;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			
			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = $roll;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
	}
?>
