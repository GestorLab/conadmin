<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ContratoConexaoAtiva(){
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION["IdLoja"];
		$IdContrato			= $_GET[IdContrato];
		$ConexaoAtiva		= $_GET[ConexaoAtiva];
		$Limit				= $_GET[Limit];
		
		$sql = "SELECT
					Valor UserName
				FROM
					ContratoParametro
				WHERE
					IdLoja = $IdLoja AND
					IdContrato = $IdContrato AND
					IdParametroServico = 1";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$UserName = $lin[UserName];
		
		$sql = "SELECT 
					ValorCodigoInterno 
				FROM 
					CodigoInterno 
				WHERE 
					IdLoja = '$IdLoja' AND 
					IdGrupoCodigoInterno = 10000 AND 
					IdCodigoInterno = '1';";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[ValorCodigoInterno] != '' && $UserName != ''){
			$aux = explode("\n",$lin[ValorCodigoInterno]);
			
			$bd[server]	= trim($aux[0]); //Host
			$bd[login]	= trim($aux[1]); //Login
			$bd[senha]	= trim($aux[2]); //Senha
			$bd[banco]	= trim($aux[3]); //DB

			$conRadius	= @mysql_connect($bd[server],$bd[login],$bd[senha]);
			
			mysql_select_db($bd[banco],$conRadius);
			
			$Where = "";
			
			if($Limit != ''){
				$Limit = " LIMIT $Limit";
			} else{
				$Limit = "";
			}
			
			if($ConexaoAtiva != 1){
				$Where = " AND acctstoptime IS NOT NULL";
			}
			
			$sql = "SELECT
						acctstarttime DataInicio,
						acctstoptime DataTermino,
						acctinputoctets Upload,
						acctoutputoctets Download,
						calledstationid NAS,
						framedipaddress IP,
						callingstationid MAC,
						IF(framedprotocol = 'PPP', 
							'PPPoE',
							'hotspot'
						) TipoConexao,
						IF(acctstoptime IS NULL OR acctstoptime = '0000-00-00 00:00:00', 
							1, 
							0
						) Ativa
					FROM
						(
							(
								SELECT 
									acctstarttime,
									acctstoptime,
									acctinputoctets,
									acctoutputoctets,
									calledstationid,
									framedipaddress,
									callingstationid,
									framedprotocol,
									radacctid
								FROM
									radacct
								WHERE
									username = '$UserName'
									$Where
							) UNION (
								SELECT
									acctstarttime,
									acctstoptime,
									acctinputoctets,
									acctoutputoctets,
									calledstationid,
									framedipaddress,
									callingstationid,
									framedprotocol,
									radacctid
								FROM
									radacctJornal
								WHERE
									username = '$UserName'
									$Where
							)
						) radacct
					ORDER BY
						acctstarttime DESC
					$Limit";
			$res = mysql_query($sql,$conRadius);
			
			if(@mysql_num_rows($res) > 0){
				header("content-type: text/xml");
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.=	"\n<reg>";
				
				while($lin = @mysql_fetch_array($res)){
					$lin[Upload] = byte_convert($lin[Upload],2);
					$lin[Download] = byte_convert($lin[Download],2);
					
					$dados	.=	"\n<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
					$dados	.=	"\n<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
					$dados	.=	"\n<Upload><![CDATA[$lin[Upload]]]></Upload>";
					$dados	.=	"\n<Download><![CDATA[$lin[Download]]]></Download>";
					$dados	.=	"\n<NAS><![CDATA[$lin[NAS]]]></NAS>";
					$dados	.=	"\n<IP><![CDATA[$lin[IP]]]></IP>";
					$dados	.=	"\n<MAC><![CDATA[$lin[MAC]]]></MAC>";
					$dados	.=	"\n<TipoConexao><![CDATA[$lin[TipoConexao]]]></TipoConexao>";
					$dados	.=	"\n<Ativa><![CDATA[$lin[Ativa]]]></Ativa>";
				}
				
				$dados	.=	"\n</reg>";
				return $dados;
			}
		}
		
		return "false";
	}
	
	echo get_ContratoConexaoAtiva();
?>
