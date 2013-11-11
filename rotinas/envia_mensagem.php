<?
	if($Path == ''){
		$EndFile 	= "rotinas/envia_mensagem.php";
		$Vars 		= $_SERVER['argv'];
		$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));
	}

	include($Path.'files/conecta.php');
	include($Path.'files/funcoes.php');
	include($Path.'classes/envia_mensagem/envia_mensagem.php');

	$sql = "select
				TipoMensagem.IdContaEmail,
				TipoMensagem.IdContaSMS
			from
				HistoricoMensagem,
				TipoMensagem
			where
				HistoricoMensagem.IdStatus IN (1,3,5) and
				HistoricoMensagem.IdLoja = $Vars[1] and
				HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
				HistoricoMensagem.IdHistoricoMensagem = $Vars[2] and
				HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){
		if($lin[IdContaEmail] != ''){			
			enviaEmail($Vars[1], $Vars[2]);
		}

		if($lin[IdContaSMS] != ''){
			enviaSMS($Vars[1], $Vars[2]);
		}
	}
?>