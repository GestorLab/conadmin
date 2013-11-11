<?
	$local_IdLoja			= $_SESSION[IdLoja];
	$local_IdStatusContrato = $_GET[IdStatusContrato];

	$sql = "select
				*
			from
				ContratoParametro
			where
				ContratoParametro.IdLoja = $local_IdLoja and
				ContratoParametro.IdContrato in (select
													IdContrato
									 			from
													Contrato
												where
													Contrato.IdLoja = $local_IdLoja and
													Contrato.IdStatus in ($local_IdStatusContrato)";	
	$res = mysql_query($sql, $con);
	while($lin = mysql_fetch_array($res)){

	}

	echo '/interface wireless access-list add ap-tx-limit=0 authentication=yes client-tx-limit=0 comment="COMENTRIO DO MAC" disabled=no forwarding=yes interface="NOME DA TORRE" mac-address="MAC-ADDRESS" management-protection-key="" private-algo=none private-key="" private-pre-shared-key="CHAVEDECRIPTOGRAFIA" signal-range=-120.120';


	//GRUPOS DE BANDA HOTSPOT
	echo '/ip hotspot user profile add advertise=no idle-timeout="VALOR" keepalive-timeout=2m name="NOME DO GRUPO" open-status-page=always rate-limit="BANDA" shared-users=1 status-autorefresh=1m transparent-proxy=yes';


	//USUÁRIO HOTSPOT
	echo '/ip hotspot user add comment="COMENTÁRIO" disabled=no name="NOME DO USUÁRIO" password="SENHA" profile="NOME DO GRUPO CRIANDO ANTERIORMENTE"';


	//GRUPOS PPPOE
	echo '/ppp profile add change-tcp-mss=default comment="COMENTARIO" idle-timeout="" name="NOME GRUPO" only-one=default rate-limit="BANDA" session-timeout="" use-compression=default use-encryption=default use-vj-compression=default';


	//USUÁRIO PPPOE
	echo '/ppp secret add caller-id="MAC DO CLIENTE" comment="COMENTÁRIO" disabled=no limit-bytes-in=0 limit-bytes-out=0 name="USUARIO" password="SENHA" profile="NOME-GRUPO-PPPOE" routes="" service=pppoe';
?>