<?
	set_time_limit(0);	
	include ('../files/conecta.php');

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$IdLoja = 1;

	$sql = "select
				IdCodigoInterno,
				ValorCodigoInterno
			from
				CodigoInterno
			where
				IdLoja = $IdLoja and
				IdGrupoCodigoInterno = 2";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	

		switch($lin[IdCodigoInterno]){
			case 1:
				$EmailRemente = $lin[ValorCodigoInterno];
				break;
			case 2:
				$DescricaoContaEmail = $lin[ValorCodigoInterno];
				$NomeRemetente		 = $lin[ValorCodigoInterno];				
				break;
			case 4:
				$ServidorSMTP = $lin[ValorCodigoInterno];
				break;
			case 5:
				$Porta = $lin[ValorCodigoInterno];
				break;
			case 6:
				if($lin[ValorCodigoInterno]){
					$RequerAutenticacao = 0;
				}
				break;
			case 7:
				$Usuario = $lin[ValorCodigoInterno];
				break;
			case 8:
				$Senha = $lin[ValorCodigoInterno];
				break;
			case 10:
				$NomeResposta = $lin[ValorCodigoInterno];
				break;
			case 9:	
				$EmailResposta = $lin[ValorCodigoInterno];
				break;
			case 12:	
				$IntervaloEnvio = $lin[ValorCodigoInterno];
				break;
			case 13:	
				$QtdTentativaEnvio = $lin[ValorCodigoInterno];
				break;
			case 27:	
				if($lin[ValorCodigoInterno] == 'ssl'){
					$SMTPSeguro = 1;
				}else{
					$SMTPSeguro = 0;	
				}
				
				break;
		}		
	}

	$sql	=	"select (max(IdContaEmail)+1) IdContaEmail from ContaEmail";
	$res	=	mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
		
	if($lin[IdContaEmail]!=NULL){
		$local_IdContaEmail	=	$lin[IdContaEmail];
	}else{
		$local_IdContaEmail	=	1;
	}
		
	$sql = "insert into ContaEmail set
				IdLoja				= $IdLoja,					
				IdContaEmail		= $local_IdContaEmail,
				DescricaoContaEmail	= '$DescricaoContaEmail',
				NomeRemetente		= '$NomeRemetente',	
				EmailRemetente		= '$EmailRemente',	
				ServidorSMTP		= '$ServidorSMTP',
				Porta				= '$Porta',
				RequerAutenticacao	= '$RequerAutenticacao',
				Usuario				= '$Usuario',
				Senha				= '$Senha',
				NomeResposta		= '$NomeResposta',
				EmailResposta		= '$EmailResposta',
				IntervaloEnvio		= '$IntervaloEnvio',
				QtdTentativaEnvio	= '$QtdTentativaEnvio',
				SMTPSeguro			= '$SMTPSeguro',
				LoginCriacao		= 'root',
				DataCriacao			= '0000-00-00 00:00:00'";
	$transaction[$tr_i]	=	mysql_query($sql,$con);	
	if($transaction[$tr_i] == false){
		echo "....".$sql." -- ".$transaction[$tr_i].mysql_error();	
		break;
	}		

	for($i=0; $i<$tr_i; $i++){
		if($transaction[$i] == false){
			$transaction = false;
		}
	}
	
	if($transaction == true){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}

	echo $sql;
	//$sql = "ROLLBACK;";
	mysql_query($sql,$con);
?>