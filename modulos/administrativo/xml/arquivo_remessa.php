<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Arquivo_Remessa(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdArquivoRemessa 			= $_GET['IdArquivoRemessa'];
		$DescricaoLocalCobranca		= $_GET['DescricaoLocalCobranca'];
		$IdLocalCobranca			= $_GET['IdLocalCobranca'];
		$DataRemessa				= $_GET['DataRemessa'];
		$NomeArquivo				= $_GET['EndArquivo'];
		$NomeArquivo				= @end(explode("\\",$NomeArquivo));
		
		$where			= "";
		
		if($DataRetorno != ''){
			$DataRetorno	=	dataConv($DataRetorno,'d/m/Y','Y-m-d');
		}
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdArquivoRemessa != ''){		$where .= " and IdArquivoRemessa = $IdArquivoRemessa";	}
		if($DataRemessa !=''){				$where .= " and DataRemessa = '$DataRemessa'";	}
		if($DescricaoLocalCobranca !=''){	$where .= " and LocalCobranca.DescricaoLocalCobranca like '$DescricaoLocalCobranca%'";	}
		if($IdLocalCobranca !=''){			$where .= " and ArquivoRemessa.IdLocalCobranca = $IdLocalCobranca";	}
		if($NomeArquivo !=''){				$where .= " and ArquivoRemessa.NomeArquivo = '$NomeArquivo'";	}

		$sql	=	"select
					     ArquivoRemessa.IdArquivoRemessa,
						 ArquivoRemessa.IdLocalCobranca,
					     LocalCobranca.DescricaoLocalCobranca,
						 LocalCobranca.IdArquivoRemessaTipo,
						 ArquivoRemessa.IdStatus,
						 ArquivoRemessa.DataRemessa,
						 ArquivoRemessa.QtdRegistro,
						 ArquivoRemessa.FileSize,
						 ArquivoRemessa.EndArquivo,
						 ArquivoRemessa.NomeArquivo,
						 ArquivoRemessa.NumSeqArquivo,
						 ArquivoRemessa.LogRemessa,
						 ArquivoRemessa.ValorTotal,
					     ArquivoRemessa.DataCriacao,
					 	 ArquivoRemessa.LoginCriacao,
 						 ArquivoRemessa.DataProcessamento,
						 ArquivoRemessa.LoginProcessamento,
						 ArquivoRemessa.DataAlteracao,
						 ArquivoRemessa.LoginAlteracao,
						 ArquivoRemessa.DataConfirmacao,
						 ArquivoRemessa.LoginConfirmacao
					from 
					     ArquivoRemessa,
					     LocalCobranca
					where
					     ArquivoRemessa.IdLoja=$IdLoja and
					     ArquivoRemessa.IdLoja = LocalCobranca.IdLoja and
						 ArquivoRemessa.IdLocalCobranca = LocalCobranca.IdLocalCobranca $where $Limit";					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=80 and IdParametroSistema=$lin[IdStatus]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$sql3 = "select DescricaoArquivoRemessaTipo from ArquivoRemessaTipo where IdArquivoRemessaTipo=$lin[IdArquivoRemessaTipo]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			$sql4 = "SELECT IdArquivoRemessa IdArquivoRemessaFila FROM ArquivoRemessa WHERE IdLoja = '$IdLoja' AND IdStatus = '3' AND IdLocalCobranca = '$lin[IdLocalCobranca]' ORDER BY IdArquivoRemessa DESC LIMIT 1;";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);
			
			if($lin4[IdArquivoRemessaFila] == ''){
				$lin4[IdArquivoRemessaFila] = 0;
			}
			
			$Color	  = getCodigoInterno(21,$lin[IdStatus]);			
			$QtdLimitContaReceber = getCodigoInterno(3,106);
			
			$dados	.=	"\n<IdArquivoRemessa>$lin[IdArquivoRemessa]</IdArquivoRemessa>";
			$dados	.=	"\n<IdArquivoRemessaFila>$lin4[IdArquivoRemessaFila]</IdArquivoRemessaFila>";
			$dados	.=	"\n<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";
			$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
			$dados	.=	"\n<IdArquivoRemessaTipo><![CDATA[$lin[IdArquivoRemessaTipo]]]></IdArquivoRemessaTipo>";
			$dados	.=	"\n<DescricaoArquivoRemessaTipo><![CDATA[$lin3[DescricaoArquivoRemessaTipo]]]></DescricaoArquivoRemessaTipo>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<DataRemessa><![CDATA[$lin[DataRemessa]]]></DataRemessa>";
			$dados	.=	"\n<QtdRegistro><![CDATA[$lin[QtdRegistro]]]></QtdRegistro>";
			$dados	.=	"\n<FileSize><![CDATA[$lin[FileSize]]]></FileSize>";
			$dados	.=	"\n<EndArquivo><![CDATA[$lin[EndArquivo]]]></EndArquivo>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<NomeArquivo><![CDATA[$lin[NomeArquivo]]]></NomeArquivo>";
			$dados	.=	"\n<NumSeqArquivo><![CDATA[$lin[NumSeqArquivo]]]></NumSeqArquivo>";
			$dados	.=	"\n<LogRemessa><![CDATA[$lin[LogRemessa]]]></LogRemessa>";			
			$dados	.=	"\n<QtdLimitContaReceber><![CDATA[$QtdLimitContaReceber]]></QtdLimitContaReceber>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
			$dados	.=	"\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";
			$dados	.=	"\n<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";
			$dados	.=	"\n<LoginConfirmacao><![CDATA[$lin[LoginConfirmacao]]]></LoginConfirmacao>";
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Arquivo_Remessa();
?>
