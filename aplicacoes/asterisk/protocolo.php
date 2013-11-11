<?
	include("../../files/conecta.php");

	$local_IdLoja	= $_GET[IdLoja];
	$local_Assunto	= $_GET[Assunto];
	$local_IdPessoa	= $_GET[IdPessoa];
	$local_Mensagem = $_GET[Msg];

	$sql = "start transaction;";
	@mysql_query($sql,$con);
	$tr_i = 0;
		
	$sql = "select (max(IdProtocolo) + 1) IdProtocolo from Protocolo;";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
		
	if($lin[IdProtocolo] != NULL){ 
		$local_IdProtocolo = $lin[IdProtocolo];
	} else{
		$local_IdProtocolo = 1;
	}

	$sql = "insert into Protocolo set
				IdLoja			= $local_IdLoja,
				IdProtocolo		= $local_IdProtocolo, 
				LocalAbertura	= 100000, 
				IdProtocoloTipo	= 1, 
				Assunto			= '$local_Assunto', 
				IdPessoa		= $local_IdPessoa, 
				IdStatus		= 101, 
				LoginCriacao	= 'automatico',
				DataCriacao		= (concat(curdate(),' ',curtime()))";
	$local_transaction[$tr_i] = @mysql_query($sql,$con);
	$tr_i++;

	$sql = "insert into ProtocoloHistorico set
				IdLoja					= $local_IdLoja,
				IdProtocolo				= $local_IdProtocolo,
				IdProtocoloHistorico	= 1,
				Mensagem				= '$local_Mensagem',
				IdStatus				= 101,
				LoginCriacao			= 'automatico',
				DataCriacao				= (concat(curdate(),' ',curtime()));";
	$local_transaction[$tr_i] = @mysql_query($sql,$con);
	$tr_i++;

	for($i = 0; $i < $tr_i; $i++){
		if(!$local_transaction[$i]){
			$local_transaction = false;
		}
	}
	
	if($local_transaction == true){
		$sql = "commit";
		echo $local_IdProtocolo;
	} else{
		$sql = "rollback";
	}
	@mysql_query($sql,$con);
?>