<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql3	=	"select IdTabelaPreco from TabelaPreco where IdLoja = $local_IdLoja order by DescricaoTabelaPreco ASC";
		$res3	=	mysql_query($sql3,$con);
		while($lin3 = mysql_fetch_array($res3)){
			$i	=	$lin3[IdTabelaPreco];
		
			$sql2	=	"select IdFormaPagamento from FormaPagamento where IdLoja = $local_IdLoja order by DescricaoFormaPagamento ASC";			
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
				$ii	=	$lin2[IdFormaPagamento];
			
				$local_ValorPrecoMinimo[$i][$ii] =  $_POST['ValorPrecoMinimo_'.$i.'_'.$ii]; 
				$local_ValorPrecoMinimo[$i][$ii] =	str_replace(".", "", $local_ValorPrecoMinimo[$i][$ii]);	
				$local_ValorPrecoMinimo[$i][$ii] = 	str_replace(",", ".", $local_ValorPrecoMinimo[$i][$ii]);
				
				
				$local_ValorPreco[$i][$ii]	    =  $_POST['ValorPreco_'.$i.'_'.$ii]; 
				$local_ValorPreco[$i][$ii]		=  str_replace(".", "", $local_ValorPreco[$i][$ii]);	
				$local_ValorPreco[$i][$ii]		=  str_replace(",", ".", $local_ValorPreco[$i][$ii]);
				
				$sql4	=	"select ValorPrecoMinimo,ValorPreco from ProdutoTabelaPreco where IdLoja = $local_IdLoja and IdProduto = $local_IdProduto and IdTabelaPreco = $lin3[IdTabelaPreco] and IdFormaPagamento = $lin2[IdFormaPagamento]";
				$res4	=	mysql_query($sql4,$con);
							
				if(mysql_num_rows($res4) >= 1){
					$sql	=	"
							UPDATE  ProdutoTabelaPreco SET 
								ValorPrecoMinimo			= '".$local_ValorPrecoMinimo[$i][$ii]."',
								ValorPreco					= '".$local_ValorPreco[$i][$ii]."'
							WHERE
								IdLoja						= $local_IdLoja and
								IdProduto					= $local_IdProduto and 
								IdTabelaPreco				= $lin3[IdTabelaPreco] and
								IdFormaPagamento			= $lin2[IdFormaPagamento]";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}else{
					$sql	=	"
							INSERT INTO ProdutoTabelaPreco SET 
								IdLoja						= $local_IdLoja,
								IdProduto					= $local_IdProduto,
								IdTabelaPreco				= $lin3[IdTabelaPreco],
								IdFormaPagamento			= $lin2[IdFormaPagamento], 
								ValorPrecoMinimo			= '".$local_ValorPrecoMinimo[$i][$ii]."',
								ValorPreco					= '".$local_ValorPreco[$i][$ii]."'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				
			}
		}
		
		$sql	=	"UPDATE Produto SET
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
					 WHERE 
							IdLoja					= $local_IdLoja and		
							IdProduto				= $local_IdProduto";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		// Executa a Sql de Inserção de ProdutoVigencia
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$local_Erro = 4;		// Mensagem de Alteração Positiva
			$sql = "COMMIT;";
		}else{
			$local_Erro = 5;		// Mensagem de Alteração Negativa
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
