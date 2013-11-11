<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserзгo de Arquivo Retorno
		$arquivos_Suportados	=	array('ret','RET','dat','DAT','crt','CRT','txt','TXT');
		$temp_EndArquivo		=	$_FILES['EndArquivo']['name'];
		$temp_SizeArquivo		=	$_FILES['EndArquivo']['size'];		
		$temp_tempEndArquivo	=	$_POST['tempEndArquivo'];
		$temp_ExtArquivo		=	@strtolower(end(explode(".",$temp_tempEndArquivo)));
		$local_NomeArquivo		=	@end(explode("\\",$temp_tempEndArquivo));
				
		if(array_search($temp_ExtArquivo, $arquivos_Suportados) === false){
			$local_Erro = 192;
		}else{
			if($temp_ExtArquivo != ''){			
				$sql	=	"select (max(IdArquivoRetorno)+1) IdArquivoRetorno from ArquivoRetorno where IdLoja=$local_IdLoja and IdLocalCobranca = $local_IdLocalRecebimento";
				$res	=	@mysql_query($sql,$con);
				$lin	=	@mysql_fetch_array($res);
				
				if($lin[IdArquivoRetorno]!=NULL){
					$local_IdArquivoRetorno	=	$lin[IdArquivoRetorno];
				}else{
					$local_IdArquivoRetorno	=	1;
					@mkdir ("retorno/".$local_IdLoja, 0770);
				}
			
				$tempCaminhoArquivos = "retorno/$local_IdLoja/$local_IdLocalRecebimento/$local_IdArquivoRetorno".".".$temp_ExtArquivo;
				
				$temp_SizeArquivo = $temp_SizeArquivo / 1024;
				
				$sql = "INSERT INTO ArquivoRetorno SET 
							IdLoja					= $local_IdLoja, 
							IdArquivoRetorno		= $local_IdArquivoRetorno, 
							IdLocalCobranca			= $local_IdLocalRecebimento, 
							IdStatus				= 1,
							FileSize				= '$temp_SizeArquivo',
							EndArquivo				= '$tempCaminhoArquivos',
							NomeArquivo				= '$local_NomeArquivo', 
							LoginCriacao			= '$local_Login',
							IdArquivoRetornoTipo	= '$local_IdArquivoRetornoTipo',	 
							DataCriacao				=(concat(curdate(),' ',curtime()));";
				if(@mysql_query($sql,$con) == true){
					@mkdir("retorno/".$local_IdLoja, 0770);
					@mkdir("retorno/".$local_IdLoja."/".$local_IdLocalRecebimento, 0770);					
					if(@copy($_FILES['EndArquivo']['tmp_name'],$tempCaminhoArquivos)){
						$local_Acao = 'processar';
						$local_Erro = 3;	
					}else{
						$sql	=	"DELETE FROM ArquivoRetorno WHERE IdLoja=$local_IdLoja and IdArquivoRetorno=$local_IdArquivoRetorno and IdLocalCobranca = $local_IdLocalRecebimento";
						@mysql_query($sql,$con);
						
						$sql3	=	"select count(*) quant from ArquivoRetorno where IdLoja=$local_IdLoja and IdLocalCobranca = $local_IdLocalRecebimento";
						$res3	=	mysql_query($sql3,$con);
						$lin3	=	mysql_fetch_array($res3);
						if($lin3[quant] == NULL){
							@rmdir("retorno/$local_IdLoja/$local_IdLocalRecebimento");
						}
						
						$sql3	=	"select count(*) quant from ArquivoRetorno where IdLoja=$local_IdLoja";
						$res3	=	mysql_query($sql3,$con);
						$lin3	=	mysql_fetch_array($res3);
						if($lin3[quant] == NULL || $lin3[quant] < 1){
							@rmdir("retorno/$local_IdLoja");
						}
						# Erro de Cуpia
						$local_Erro = 9;
						$local_Acao	= 'inserir';	
					}							
				}else{
					# Erro de Ao inserir Registro
					$local_Erro = 8;
					$local_Acao	= 'inserir';	
				}
			}else{
				# Erro de Ao inserir Registro
				$local_Erro = 8;
				$local_Acao	= 'inserir';	
			}
		}
	}
?>