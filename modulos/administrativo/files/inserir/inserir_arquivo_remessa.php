<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql_vr = "select 
						IdLocalCobranca
					from
						LocalCobranca 
					where 
						IdLoja = $local_IdLoja and 
						IdLocalCobranca = $local_IdLocalCobranca and 
						IdArquivoRemessaTipo is not null;";
		$res_vr = mysql_query($sql_vr, $con);
		
		if(mysql_num_rows($res_vr) > 0){
			$sql	=	"select (max(IdArquivoRemessa)+1) IdArquivoRemessa from ArquivoRemessa where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
				
			if($lin[IdArquivoRemessa]!=NULL){
				$local_IdArquivoRemessa	=	$lin[IdArquivoRemessa];
			}else{
				$local_IdArquivoRemessa	=	1;
			}
			
			if($local_IdArquivoRemessaTipo == ''){
				$local_IdArquivoRemessaTipo = 'NULL';
			}
			
			$sql	=	"INSERT INTO ArquivoRemessa SET 
							IdLoja					= $local_IdLoja, 
							IdArquivoRemessa		= $local_IdArquivoRemessa, 
							IdLocalCobranca			= $local_IdLocalCobranca, 
							IdStatus				= 1,
							IdArquivoRemessaTipo	= $local_IdArquivoRemessaTipo,
							LogRemessa				= '',
							LoginCriacao			= '$local_Login',	 
							DataCriacao				= (concat(curdate(),' ',curtime()));";
			if(@mysql_query($sql,$con) == true){
				$local_Erro = 3;			// Mensagem de Inserção Positiva
				$local_Acao = 'alterar';						
			}else{
				$local_Erro = 8;			# Erro de Ao inserir Registro
				$local_Acao	= 'inserir';	
			}
		} else{
			$local_Erro = 178;
		}
	}
?>
