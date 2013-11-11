<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Arquivo_Remessa_Tipo(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$Nome						= $_GET['Nome'];
		$IdContrato					= $_GET['IdContrato'];
		$where			= "";
		
		if($IdArquivoRemessaTipo != '')		$where	=	" and ArquivoRemessaTipo.IdArquivoRemessaTipo = $IdArquivoRemessaTipo";
		if($Nome != '')						$where	=	" and ArquivoRemessaTipo.DescricaoArquivoRemessaTipo like '$Nome%'";
		if($IdLocalCobranca != '')			$where	=	" and LocalCobranca.IdLocalCobranca = $IdLocalCobranca";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql = "SELECT
					IdPessoa
				FROM
					Contrato
				WHERE
					IdContrato = $IdContrato";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		echo $sql = "SELECT
					OrdemServico.IdOrdemServico,
					OrdemServico.IdTipoOrdemServico,
					OrdemServico.IdSubTipoOrdemServico,
					OrdemServico.LoginAtendimento,
					OrdemServico.DataCriacao,
					OrdemServico.IdStatus
				FROM
					OrdemServico
				WHERE
					OrdemServico.IdPessoa = $lin[IdPessoa] AND 
					OrdemServico.IdStatus = 200";
		
		$res = mysql_query($sql,$con);
		if(mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			while($lin = mysql_fetch_array($res)){
				$dados .= "\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";	
				$dados .= "\n<IdTipoOrdemServico>$lin[IdTipoOrdemServico]</IdTipoOrdemServico>";	
				$dados .= "\n<IdSubTipoOrdemServico>$lin[IdSubTipoOrdemServico]</IdSubTipoOrdemServico>";	
				$dados .= "\n<LoginAtendimento>$lin[LoginAtendimento]</LoginAtendimento>";	
				$dados .= "\n<DataCriacao>$lin[DataCriacao]</DataCriacao>";	
				$dados .= "\n<IdStatus>$lin[IdStatus]</IdStatus>";	
			}
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}