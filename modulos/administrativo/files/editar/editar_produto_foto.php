<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$pathHost = "../../";
		
		$temp_EndFoto		=	$_FILES['Foto']['name'];
		$temp_ExtFoto		=	strtolower(endArray(explode(".",$temp_EndFoto)));
		
		if($temp_ExtFoto != ''){
			if($temp_EndFoto != ""){
				$sql	=	"select  
									ProdutoFoto.ExtFoto 
								from 
									Produto, 
									ProdutoFoto						    
								where 
									Produto.IdLoja = $local_IdLoja and
									Produto.IdLoja = ProdutoFoto.IdLoja and 
									ProdutoFoto.IdProduto = Produto.IdProduto and 
									ProdutoFoto.IdProduto=$local_IdProduto and 
									ProdutoFoto.IdProdutoFoto=$local_IdProdutoFoto";
				$res	=	mysql_query($sql,$con);
				if($lin	=	mysql_fetch_array($res)){
					if(file_exists($pathHost."img/produtos/".$local_IdLoja."/".$local_IdProduto."/".$local_IdProdutoFoto.".".$lin[ExtFoto])){
						unlink($pathHost."img/produtos/".$local_IdLoja."/".$local_IdProduto."/".$local_IdProdutoFoto.".".$lin[ExtFoto]);
					}
					if(file_exists($pathHost."img/produtos/".$local_IdLoja."/".$local_IdProduto."/tumb/".$local_IdProdutoFoto.".".$lin[ExtFoto])){
						unlink($pathHost."img/produtos/".$local_IdLoja."/".$local_IdProduto."/tumb/".$local_IdProdutoFoto.".".$lin[ExtFoto]);
					}
				}
			}
			$temp_ExtPermitido	=	array("gif","jpg","jpeg","png");
			if(in_array($temp_ExtFoto, $temp_ExtPermitido)){
				$tempCaminhoFotos = $pathHost."img/produtos/$local_IdLoja/$local_IdProduto/";		
				$tempCaminhoFotosP = $pathHost."img/produtos/$local_IdLoja/$local_IdProduto/tumb/";
				$temp_NomeDoArquivo = $local_IdProdutoFoto.".".$temp_ExtFoto;
				$temp_EndUpload = $tempCaminhoFotos.$temp_NomeDoArquivo;
				$temp_EndUploadP = $tempCaminhoFotosP.$temp_NomeDoArquivo;
				
				$widthMax	=	getCodigoInterno(3,45);
					
				if(@move_uploaded_file($_FILES['Foto']['tmp_name'],$temp_EndUpload)){
						if($local_Redimensionar == 1){
							# G	
							@resizeImage($temp_EndUpload,'',$widthMax,NULL,$temp_EndUpload);	
						}
						
						# P	
						@resizeImage($temp_EndUpload,'',50,NULL,$temp_EndUploadP);							
				}else{
					# Erro de Cópia
					$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
					//$local_Erro = 9;
				}							
			}else{
				# Erro de Extenaso nao permitida
				$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
				//$local_Erro = 10;
			}
		
			$sql	=	"UPDATE ProdutoFoto SET 
							DescricaoFoto 	= '$local_DescricaoFoto',
							ExtFoto			= '$temp_ExtFoto',
							DataAlteracao 	= concat(curdate(),' ',curtime()),
							LoginAlteracao  = '$local_Login'		
						WHERE 
							IdLoja			= $local_IdLoja and	
							IdProduto 		= $local_IdProduto and 
							IdProdutoFoto 	= $local_IdProdutoFoto;";
			if(mysql_query($sql,$con) == true){
				// Muda a ação para Inserir
				$local_Acao = 'alterar';
				//$local_Erro = 4;			// Mensagem de Inserção Positiva
			}else{
				// Muda a ação para Inserir
				$local_Acao = 'inserir';
				//$local_Erro = 5;			// Mensagem de Inserção Negativa
			}
		}
	}
?>
