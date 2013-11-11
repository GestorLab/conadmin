<?php
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Conta_Receber_Posicao_Cobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja							= $_SESSION['IdLoja'];
		$IdGrupoParametroSistema		= $_GET['IdGrupoParametroSistema'];
		$IdParametroSistema 			= $_GET['IdParametroSistema'];
		$IdContaReceber		 			= $_GET['IdContaReceber'];
		
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdContaReceber != ""){
			$where .= " AND IdContaReceber = ".$IdContaReceber;
		}
		
		$sql = "SELECT
					IdLoja,
					IdContaReceber,
					IdMovimentacao,
					IdPosicaoCobranca,
					DataRemessa,
					IdLojaRemessa,
					IdLocalCobrancaRemessa,
					IdArquivoRemessa,
					IdPessoa,
					IdContaDebito,
					IdCartao,
					DataAlteracao,
					LoginAlteracao
				FROM 
					ContaReceberPosicaoCobranca
				WHERE
					IdLoja = $IdLoja
					$where";
		
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin = mysql_fetch_array($res)){
			$sqlParametroSistema = "SELECT
									  IdParametroSistema,
									  ValorParametroSistema PosicaoCobranca
									FROM 
										ParametroSistema
									WHERE
										IdGrupoParametroSistema = 81 AND
										IdParametroSistema = '$lin[IdPosicaoCobranca]'";
			$resParametroSistema = mysql_query($sqlParametroSistema,$con);
			$linParametroSistema = mysql_fetch_array($resParametroSistema);
			
			$lin[DataRemessa] = dataConv($lin[DataRemessa],'Y-m-d','d/m/Y');
			
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<IdPosicaoCobranca><![CDATA[$lin[IdPosicaoCobranca]]]></IdPosicaoCobranca>";
			$dados	.=	"\n<PosicaoCobranca><![CDATA[$linParametroSistema[PosicaoCobranca]]]></PosicaoCobranca>";
			$dados	.=	"\n<IdArquivoRemessa><![CDATA[$lin[IdArquivoRemessa]]]></IdArquivoRemessa>";
			$dados	.=	"\n<DataRemessa><![CDATA[$lin[DataRemessa]]]></DataRemessa>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Conta_Receber_Posicao_Cobranca();
?>