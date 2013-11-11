<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de MovimentacaoProduto
		$sql	=	"select (max(IdMovimentacaoProduto)+1) IdMovimentacaoProduto from MovimentacaoProduto where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdMovimentacaoProduto]!=NULL){
			$local_IdMovimentacaoProduto	=	$lin[IdMovimentacaoProduto];
		}else{
			$local_IdMovimentacaoProduto	=	1;
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
		
		$local_DataNF					=	dataConv($local_DataNF,'d/m/Y','Y-m-d');
		
		$local_ValorBaseCalculoICMS		=	str_replace(".", "", $local_ValorBaseCalculoICMS);	
		$local_ValorBaseCalculoICMS		= 	str_replace(",", ".", $local_ValorBaseCalculoICMS);
		
		$local_ValorTotalICMS			=	str_replace(".", "", $local_ValorTotalICMS);	
		$local_ValorTotalICMS			= 	str_replace(",", ".", $local_ValorTotalICMS);
		
		$local_ValorTotalProduto		=	str_replace(".", "", $local_ValorTotalProduto);	
		$local_ValorTotalProduto		= 	str_replace(",", ".", $local_ValorTotalProduto);
		
		$local_ValorNF					=	str_replace(".", "", $local_ValorNF);	
		$local_ValorNF					= 	str_replace(",", ".", $local_ValorNF);
		
		$local_ValorFrete				=	str_replace(".", "", $local_ValorFrete);	
		$local_ValorFrete				= 	str_replace(",", ".", $local_ValorFrete);
		
		$local_ValorSeguro				=	str_replace(".", "", $local_ValorSeguro);	
		$local_ValorSeguro				= 	str_replace(",", ".", $local_ValorSeguro);
		
		$local_ValorTotalIPI			=	str_replace(".", "", $local_ValorTotalIPI);	
		$local_ValorTotalIPI			= 	str_replace(",", ".", $local_ValorTotalIPI);
		
		$local_ValorOutrasDespesas		=	str_replace(".", "", $local_ValorOutrasDespesas);	
		$local_ValorOutrasDespesas		= 	str_replace(",", ".", $local_ValorOutrasDespesas);
		
		$sql	=	"
				INSERT INTO MovimentacaoProduto SET
					IdLoja							='$local_IdLoja',
					IdMovimentacaoProduto			='$local_IdMovimentacaoProduto',
					TipoMovimentacao				='$local_TipoMovimentacao',
					DataNF							='$local_DataNF',
					NumeroNF						='$local_NumeroNF',
					IdFornecedor					='$local_IdPessoa',
					ValorBaseCalculoICMS			='$local_ValorBaseCalculoICMS',
					ValorICMS						='$local_ValorTotalICMS',
					SerieNF							='$local_SerieNF',
					ValorNF							='$local_ValorNF',
					ValorTotalProduto				='$local_ValorTotalProduto',
					ValorFrete						='$local_ValorFrete',
					ValorSeguro						='$local_ValorSeguro',
					ValorTotalIPI					='$local_ValorTotalIPI',
					ValorOutrasDespesas				='$local_ValorOutrasDespesas',
					IdEstoque						='$local_IdEstoque',
					CFOP							='$local_CFOP',
					Obs								='$local_Obs',
					LoginCriacao					='$local_Login', 
					DataCriacao						=(concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$pos = 1;
		for($ii=1;$ii<=$local_QtdProduto;$ii++){
			if($_POST['IdProduto_'.$ii] != ''){
				$local_IdProduto		=	$_POST['IdProduto_'.$ii];
				
				$local_Quantidade		=	$_POST['Quantidade_'.$ii];
				$local_Quantidade		=	str_replace(".", "", $local_Quantidade);	
				$local_Quantidade		= 	str_replace(",", ".", $local_Quantidade);
				
				$local_ValorUnitario	=	$_POST['ValorUnitario_'.$ii];
				$local_ValorUnitario	=	str_replace(".", "", $local_ValorUnitario);	
				$local_ValorUnitario	= 	str_replace(",", ".", $local_ValorUnitario);
				
				$local_AliquotaIPI		=	$_POST['AliquotaIPI_'.$ii];
				$local_AliquotaIPI		=	str_replace(".", "", $local_AliquotaIPI);	
				$local_AliquotaIPI		= 	str_replace(",", ".", $local_AliquotaIPI);
				
				$local_ValorIPI			=	$_POST['ValorIPI_'.$ii];
				$local_ValorIPI			=	str_replace(".", "", $local_ValorIPI);	
				$local_ValorIPI			= 	str_replace(",", ".", $local_ValorIPI);
				
				$local_AliquotaICMS		=	$_POST['AliquotaICMS_'.$ii];
				$local_AliquotaICMS		=	str_replace(".", "", $local_AliquotaICMS);	
				$local_AliquotaICMS		= 	str_replace(",", ".", $local_AliquotaICMS);
				
				$local_NumeroSerie		=	$_POST['NumeroSerie_'.$ii];
				
				$sql	=	"
						INSERT INTO MovimentacaoProdutoItem SET 
							IdLoja						= '$local_IdLoja',
							IdMovimentacaoProduto		= '$local_IdMovimentacaoProduto',
							IdProduto			 		= '$local_IdProduto',
							Quantidade					= '$local_Quantidade',
							ValorUnitario				= '$local_ValorUnitario',
							AliquotaIPI 				= '$local_AliquotaIPI', 
							AliquotaICMS				= '$local_AliquotaICMS';";
				// Executa a Sql de Inserção de MovimentacaoProdutoItem
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				if($local_NumeroSerie!= ''){
					$serie	=	explode("\n",$local_NumeroSerie);
					
					$local_Quantidade	=	(int)$local_Quantidade;
					
					$j = 0;
					$temp = 0;
					while($j < $local_Quantidade){
						$serie[$temp]	=	trim($serie[$temp]);
						
						while($serie[$temp]==''){
							$temp++;
							
							$serie[$temp]	=	trim($serie[$temp]);
						}
						
						$sql	=	"
							INSERT INTO MovimentacaoProdutoItemSerie SET 
								IdLoja						= '$local_IdLoja',
								IdMovimentacaoProduto		= '$local_IdMovimentacaoProduto',
								IdProduto			 		= '$local_IdProduto',
								NumeroSerie					= '".$serie[$temp]."';";
						// Executa a Sql de Inserção de MovimentacaoProdutoItem
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
						
						$temp++;
						$j++;
					}				
				}
			}
		}
		
					
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
	}
?>
