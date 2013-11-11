<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		
		// Sql de Inserção de Produto
		$sql	=	"select (max(IdProduto)+1) IdProduto from Produto where IdLoja = $local_IdLoja ";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdProduto]!=NULL){ 
			$local_IdProduto	=	$lin[IdProduto];
		}else{
			$local_IdProduto	=	1;
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		if($local_Garantia == 0)		   			$local_IdUnidadeGarantia		=	'NULL';
		if($local_IdUnidadeGarantia == '') 	 		$local_IdUnidadeGarantia		=	'NULL';	
		if($local_NumeroSerieObrigatorio == '')  	$local_NumeroSerieObrigatorio	=	'NULL';	
		if($local_IdTipoGarantia == '')				$local_IdTipoGarantia 			= 	'NULL';
		
		$local_Valor		=	str_replace(".", "", $local_Valor);	
		$local_Valor		= 	str_replace(",", ".", $local_Valor);
		
		$local_Desconto		=	str_replace(".", "", $local_Desconto);	
		$local_Desconto		= 	str_replace(",", ".", $local_Desconto);
		
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
		
		
		
		############ PRoduto ############
	    $sql	=	"
			INSERT INTO Produto SET
				IdLoja						= $local_IdLoja,
				IdProduto					= $local_IdProduto,
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
				ObsProduto					='$local_ObsProduto',			
				LoginCriacao				='$local_Login',
				DataCriacao					=concat(curdate(),' ', curtime());";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		############ SubGrupo Produto ############
		
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
