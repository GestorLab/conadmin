<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$pathHost = "../../";
		if(!file_exists($pathHost."img/produtos/$local_IdLoja")){
			@mkdir($pathHost."img/produtos/$local_IdLoja",0777);
		}
		
		@mkdir($pathHost."img/produtos/$local_IdLoja/$local_IdProduto",0777);
		@mkdir($pathHost."img/produtos/$local_IdLoja/$local_IdProduto/tumb/",0777);
		
		############ Foto ############
		
		$temp_EndFoto		=	$_FILES['Foto']['name'];
		$temp_ExtFoto		=	strtolower(endArray(explode(".",$temp_EndFoto)));
		
		if($temp_ExtFoto != ''){			
			
			$sql	=	"select (max(IdProdutoFoto)+1) IdProdutoFoto from ProdutoFoto WHERE IdLoja=$local_IdLoja and IdProduto = '$local_IdProduto';";
			$res	=	mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
				
			if($lin[IdProdutoFoto]!=NULL){
				$local_IdProdutoFoto  =	$lin[IdProdutoFoto];
			}else{
				$local_IdProdutoFoto = 1;
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
					$local_Erro = 9;
				}							
			}else{
				# Erro de Extenaso nao permitida
				$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
				$local_Erro = 10;
			}
		}
		
		
		if($temp_ExtFoto != ''){
			$sql	=	"
			INSERT INTO ProdutoFoto SET
				IdLoja					= $local_IdLoja,
				IdProduto				= $local_IdProduto,
				IdProdutoFoto			= $local_IdProdutoFoto,
				DescricaoFoto			= '$local_DescricaoFoto',
				ExtFoto					= '$temp_ExtFoto',
				DataCriacao				= concat(curdate(),' ',curtime()),
				LoginCriacao			= '$local_Login';";
			if(mysql_query($sql,$con) == true){
	 			// Muda a ação para Inserir
				$local_Acao = 'alterar';
				$local_Erro = 3;			// Mensagem de Inserção Positiva
			}else{
				// Muda a ação para Inserir
				$local_Acao = 'inserir';
				$local_Erro = 1;			// Mensagem de Inserção Negativa
			}
		}
	}
?>
