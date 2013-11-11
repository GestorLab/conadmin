<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;		
		
		$local_QtdMinima		=	str_replace(".", "", $local_QtdMinima);	
		$local_QtdMinima		= 	str_replace(",", ".", $local_QtdMinima);
		
		if($local_QtdMaxima != ''){
			$local_QtdMaxima	=	str_replace(".", "", $local_QtdMaxima);	
			$local_QtdMaxima	= 	"'".str_replace(",", ".", $local_QtdMaxima)."'";
		}else{
			$local_QtdMaxima	=	'NULL';	
		}
		
		if($local_PesoKG != ''){
			$local_PesoKG			=	str_replace(".", "", $local_PesoKG)."'";	
			$local_PesoKG			= 	"'".str_replace(",", ".", $local_PesoKG);
		}else{
			$local_PesoKG			=	'NULL';
		}
		
		if($local_Garantia == 0)		  			 $local_IdUnidadeGarantia		=	'NULL';
		if($local_IdUnidadeGarantia == '')			 $local_IdUnidadeGarantia		=	'NULL';
		if($local_NumeroSerieObrigatorio == '')  	 $local_NumeroSerieObrigatorio	=	'NULL';	
		if($local_IdTipoGarantia == '')				 $local_IdTipoGarantia 			= 	'NULL';
	 		
		$sql	=	"UPDATE Produto SET
							DescricaoProduto			='$local_DescricaoProduto',
							EspecificacaoProduto		='$local_EspecificacaoProduto',
							DescricaoReduzidaProduto	='$local_DescricaoReduzidaProduto',
							IdUnidade					= $local_IdUnidade,
							IdFabricante				= $local_IdFabricante,
							Garantia					= $local_Garantia,
							IdUnidadeGarantia			= $local_IdUnidadeGarantia,
							IdTipoGarantia				= $local_IdTipoGarantia,
							CodigoBarra					='$local_CodigoBarra',
							QtdMinima					='$local_QtdMinima',
							QtdMaxima					= $local_QtdMaxima,
							NumeroSerie					= $local_NumeroSerie,
							NumeroSerieObrigatorio		= $local_NumeroSerieObrigatorio,
							PesoKG						= $local_PesoKG,		
							LoginAlteracao				= '$local_Login',
							ObsProduto					='$local_ObsProduto',
							DataAlteracao				= concat(curdate(),' ',curtime())
					 WHERE 
							IdLoja					= $local_IdLoja and		
							IdProduto				= $local_IdProduto";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		
		$sql	=	"DELETE from ProdutoSubGrupoProduto where IdLoja = $local_IdLoja and IdProduto = $local_IdProduto";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$ii		=	0;
		$temp	=	explode('#',$local_SubGrupoProduto);
		
		while($temp[$ii] != ''){
			$temp2	=	explode('¬',$temp[$ii]);
		
			$local_IdGrupoProduto		=	$temp2[0];
			$local_IdSubGrupoProduto	=	$temp2[1];
			
			$sql	=	"
				INSERT INTO ProdutoSubGrupoProduto SET 
					IdLoja						= '$local_IdLoja',
					IdProduto					= '$local_IdProduto',
					IdGrupoProduto				= '$local_IdGrupoProduto',
					IdSubGrupoProduto			= '$local_IdSubGrupoProduto',
					LoginCriacao				= '$local_Login',
					DataCriacao					= concat(curdate(),' ', curtime());";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$ii++;
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
			$local_Erro = 4;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
	}
?>
