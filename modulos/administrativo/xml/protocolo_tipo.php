<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ProtocoloTipo(){
		global $con;
		global $_GET;
		
		$local_IdLoja				= $_SESSION['IdLoja'];
		$local_IdProtocoloTipo		= $_GET['IdProtocoloTipo'];
		$local_IdStatus				= $_GET['IdStatus'];
		$local_IdProtocoloTipoADD	= $_GET['IdProtocoloTipoADD'];
		$where						= '';
		
		if($local_IdProtocoloTipo != ''){
			$where .= " and ProtocoloTipo.IdProtocoloTipo = $local_IdProtocoloTipo";
		}
		
		if($local_IdStatus != ''){
			$where .= " and ProtocoloTipo.IdStatus = $local_IdStatus";
		}
		
		if($local_IdProtocoloTipoADD != ''){
			$where .= " or ProtocoloTipo.IdProtocoloTipo = $local_IdProtocoloTipoADD";
		}
		
		$sql = "select 
					ProtocoloTipo.IdProtocoloTipo, 
					ProtocoloTipo.DescricaoProtocoloTipo, 
					ProtocoloTipo.AberturaCDA,
					ProtocoloTipo.IdGrupoUsuarioAbertura,
					ProtocoloTipo.LoginAbertura,
					ProtocoloTipo.IdStatus,
					ProtocoloTipo.LoginCriacao, 
					ProtocoloTipo.DataCriacao, 
					ProtocoloTipo.LoginAlteracao, 
					ProtocoloTipo.DataAlteracao
				from 
					ProtocoloTipo
				where 
					ProtocoloTipo.IdLoja = '$local_IdLoja'
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdProtocoloTipo>$lin[IdProtocoloTipo]</IdProtocoloTipo>";
				$dados .= "\n<DescricaoProtocoloTipo><![CDATA[$lin[DescricaoProtocoloTipo]]]></DescricaoProtocoloTipo>";
				$dados .= "\n<AberturaCDA><![CDATA[$lin[AberturaCDA]]]></AberturaCDA>";
				$dados .= "\n<IdGrupoUsuarioAbertura><![CDATA[$lin[IdGrupoUsuarioAbertura]]]></IdGrupoUsuarioAbertura>";
				$dados .= "\n<LoginAbertura><![CDATA[$lin[LoginAbertura]]]></LoginAbertura>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_ProtocoloTipo();
?>