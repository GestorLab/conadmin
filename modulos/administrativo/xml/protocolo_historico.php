<?
	$localModulo = 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ProtocoloHistorico(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdProtocolo	= $_GET['IdProtocolo'];
		$where				= '';
		
		if($local_IdProtocolo != ''){
			$where .= " and ProtocoloHistorico.IdProtocolo = $local_IdProtocolo";
		}
		
		$sql = "select 
					ProtocoloHistorico.IdProtocoloHistorico,
					ProtocoloHistorico.Mensagem, 
					ProtocoloHistorico.IdStatus, 
					ProtocoloHistorico.LoginCriacao, 
					ProtocoloHistorico.DataCriacao
				from 
					ProtocoloHistorico
				where 
					ProtocoloHistorico.IdLoja = '$local_IdLoja'
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[Status] = getParametroSistema(239,$lin[IdStatus]);
				
				$dados .= "\n<IdProtocoloHistorico>$lin[IdProtocoloHistorico]</IdProtocoloHistorico>";
				$dados .= "\n<Mensagem><![CDATA[$lin[Mensagem]]]></Mensagem>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_ProtocoloHistorico();
?>