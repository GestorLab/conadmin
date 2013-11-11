<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	} else{
		if($local_IdGrupo == '-1'){
			$local_IdGrupo = $local_NovoGrupo;
		}
		
		if($local_Atributo == '-1'){
			$local_Atributo	= $local_NovoAtributo;
			
			$sql = "select 
						(max(IdCodigoInterno)+1) IdCodigoInterno 
					from 
						CodigoInterno 
					where 
						IdLoja = $local_IdLoja and 
						IdGrupoCodigoInterno = '10001'";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[IdCodigoInterno] != NULL){
				$local_IdCodigoInterno = $lin[IdCodigoInterno];
			} else{
				$local_IdCodigoInterno = 1;
			}
			
			$local_DescricaoCodigoInterno = "Servidor Radius - Atributo - ".$local_NovoAtributo;
			
			$sql = "insert into CodigoInterno set 
						IdLoja						= $local_IdLoja,
						IdGrupoCodigoInterno		= '10001', 
						IdCodigoInterno				= $local_IdCodigoInterno, 
						DescricaoCodigoInterno		= '$local_DescricaoCodigoInterno', 
						ValorCodigoInterno			= '$local_NovoAtributo',
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login';";
			// Executa a Sql de Inserчуo de Codigo Interno
			mysql_query($sql,$con);
		}
		
		$sql = "select 
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdLoja = '$local_IdLoja' and 
					IdGrupoCodigoInterno = 10000 and 
					IdCodigoInterno = '$local_IdServidor'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB		
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		mysql_select_db($bd[banco],$conRadius);		
		
		$table	=	"";
		
		if($local_Tipo == 'C'){
			$table = "radgroupcheck";
		}
		
		if($local_Tipo == 'R'){
			$table = "radgroupreply";
		}
		
		$sqlRadiusVerifica ="select id from $table where GroupName = '$local_IdGrupo' and Attribute = '$local_Atributo'";
		$resRadiusVerifica = mysql_query($sqlRadiusVerifica,$conRadius);
		$linRadiusVerifica = mysql_fetch_array($resRadiusVerifica);
		if(mysql_num_rows($resRadiusVerifica) == 0){
		
			$sqlRadiusAux = "select 
								(max(id)+1) id 
							from 
								$table";
			$resRadiusAux = @mysql_query($sqlRadiusAux,$conRadius);
			$linRadiusAux = @mysql_fetch_array($resRadiusAux);
				
			if($linRadiusAux[id] != NULL){
				$local_id = $linRadiusAux[id];
			} else{
				$local_id = 1;
			}
			
			$sqlRadius = "insert into $table set 
							id					= '$local_id',
							GroupName			= '$local_IdGrupo',
							Attribute			= '$local_Atributo',
							op					= '$local_Operador',
							Value				= '$local_Valor';";
			$error = 8;
		}else{
			$error = 188;
		}
		if(mysql_query($sqlRadius,$conRadius) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			if($error != ""){
				$local_IdServidor = $local_IdServidor;
				$local_Tipo = $local_Tipo;
				$local_id = $linRadiusVerifica[id];
				$local_Erro = $error; // Mensagem de Inserчуo Negativa/ Registro duplicado!
				$local_Acao = 'inserir';
			}else{
				$local_Erro = 8; // Mensagem de Inserчуo Negativa
			}
		}
		
		@mysql_close($conRadius);
	}
?>