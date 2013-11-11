 <?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
			$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdContaSMS)+1) IdContaSMS from ContaSMS";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdContaSMS]!=NULL){
			$local_IdContaSMS	=	$lin[IdContaSMS];
		}else{
			$local_IdContaSMS	=	1;
		}
		
		$sql2	=	"INSERT INTO ContaSMS SET 
						IdLoja					= '$local_IdLoja',
						IdContaSMS				= '$local_IdContaSMS',
						DescricaoContaSMS		= '$local_Descricao',
						IdOperadora				= '$local_IdOperadora',
						IdStatus				= '$local_IdStatus',
						LoginCriacao			= '$local_Login',
						DataCriacao				= concat(curdate(),' ',curtime());";
					
		if(mysql_query($sql2,$con) == true){
			$sql3 = "SELECT 
					IdParametroOperadoraSMS
				FROM
					OperadoraSMSParametro
				WHERE
					IdOperadora = $local_IdOperadora";
			$res3 = mysql_query($sql3, $con);
			while($lin3 = mysql_fetch_array($res3)){		
				$ValorParametroSistema = $_POST["ParametroOperadoraSMS_".$lin3[IdParametroOperadoraSMS]];
				if($ValorParametroSistema != ""){
					$sql =	"INSERT INTO ContaSMSParametro SET 
								IdLoja					= '$local_IdLoja',
								IdContaSMS				= '$local_IdContaSMS',
								IdOperadora				= '$local_IdOperadora',
								IdParametroOperadoraSMS	= '$lin3[IdParametroOperadoraSMS]',
								ValorParametroSMS		= '$ValorParametroSistema'";
					$res = mysql_query($sql,$con);
				}
			}
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
		
	}
?>