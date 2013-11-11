<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	set_time_limit(0);
	
	function get_ContratoInformacaoConexao(){
		global $con;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdContrato		= $_GET[IdContrato];
		$DiaCobranca	= $_GET[DiaCobranca];
		$Limit			= $_GET[Limit];
		
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
		# BUSCAR CONEXÃO
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
			# MONTAR CONEXÃO
			$bd[server]	= trim($aux[0]); //Host
			$bd[login]	= trim($aux[1]); //Login
			$bd[senha]	= trim($aux[2]); //Senha
			$bd[banco]	= trim($aux[3]); //DB

			$conRadius	= @mysql_connect($bd[server],$bd[login],$bd[senha]);
			
			mysql_select_db($bd[banco],$conRadius);
			
			if($Limit != ''){
				$Limit = " LIMIT $Limit";
			} else{
				$Limit = "";
			}
			
			$Dia = date("d");
			$Mes = date("m");
			$Ano = date("Y");
			$Increment = 0;
			
			if((int) $DiaCobranca > (int) date("d") || empty($DiaCobranca)){
				$Increment--;
			}
			
			$MesAno = incrementaMesReferencia($Mes."-".$Ano, $Increment);
			
			if(empty($DiaCobranca)) {
				# MÊS ATUAL
				$DiaTemp = ultimoDiaMes(substr($MesAno, 0, 2), substr($Ano, 3, 4));
				$MesAtual = $DiaTemp."/".$MesAno;
				$MesAtualTemp = dataConv($MesAtual, "d/m/Y", "Y-m-d");
				# MÊS ANTERIOR
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 1));
				$DiaTemp = ultimoDiaMes(substr($MesAno, 0, 2), substr($Ano, 3, 4));
				$MesAnterior = $DiaTemp."/".$MesAno;
				$MesAnteriorTemp = dataConv($MesAnterior, "d/m/Y", "Y-m-d");
				# ULTIMOS 6 MÊS
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 6));
				$DiaTemp = ultimoDiaMes(substr($MesAno, 0, 2), substr($Ano, 3, 4));
				$Ultimos6Meses = $DiaTemp."/".$MesAno;
				$Ultimos6MesesTemp = dataConv($Ultimos6Meses, "d/m/Y", "Y-m-d");
				# ULTIMOS 12 MÊS
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 12));
				$DiaTemp = ultimoDiaMes(substr($MesAno, 0, 2), substr($Ano, 3, 4));
				$Ultimos12Meses = $DiaTemp."/".$MesAno;
				$Ultimos12MesesTemp = dataConv($Ultimos12Meses, "d/m/Y", "Y-m-d");
			} else {
				$DiaCobranca = str_pad($DiaCobranca, 2, "0", STR_PAD_LEFT);
				# MÊS ATUAL
				$MesAtual = $DiaCobranca."/".$MesAno;
				$MesAtualTemp = dataConv($MesAtual, "d/m/Y", "Y-m-d");
				$MesAtualTemp = incrementaData($MesAtualTemp, -1);
				$MesAtual = dataConv($MesAtualTemp, "Y-m-d", "d/m/Y");
				# MÊS ANTERIOR
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 1));
				$MesAnterior = $DiaCobranca."/".$MesAno;
				$MesAnteriorTemp = dataConv($MesAnterior, "d/m/Y", "Y-m-d");
				$MesAnteriorTemp = incrementaData($MesAnteriorTemp, -1);
				$MesAnterior = dataConv($MesAnteriorTemp, "Y-m-d", "d/m/Y");
				# ULTIMOS 6 MÊS
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 6));
				$Ultimos6Meses = $DiaCobranca."/".$MesAno;
				$Ultimos6MesesTemp = dataConv($Ultimos6Meses, "d/m/Y", "Y-m-d");
				$Ultimos6MesesTemp = incrementaData($Ultimos6MesesTemp, -1);
				$Ultimos6Meses = dataConv($Ultimos6MesesTemp, "Y-m-d", "d/m/Y");
				# ULTIMOS 12 MÊS
				$MesAno = incrementaMesReferencia($Mes."-".$Ano, ($Increment - 12));
				$Ultimos12Meses = $DiaCobranca."/".$MesAno;
				$Ultimos12MesesTemp = dataConv($Ultimos12Meses, "d/m/Y", "Y-m-d");
				$Ultimos12MesesTemp = incrementaData($Ultimos12MesesTemp, -1);
				$Ultimos12Meses = dataConv($Ultimos12MesesTemp, "Y-m-d", "d/m/Y");
			}
			
			$sql = "SELECT
						MesAtual.MesAtualUpload,
						MesAtual.MesAtualDownload,
						MesAnterior.MesAnteriorUpload,
						MesAnterior.MesAnteriorDownload,
						Ultimos6Meses.Ultimos6MesesUpload,
						Ultimos6Meses.Ultimos6MesesDownload,
						Ultimos12Meses.Ultimos12MesesUpload,
						Ultimos12Meses.Ultimos12MesesDownload
					FROM
						(
							SELECT
								SUM(acctinputoctets) MesAtualUpload,
								SUM(acctoutputoctets) MesAtualDownload
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacct
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) > '$MesAtualTemp'
									) UNION (
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacctJornal
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) > '$MesAtualTemp'
									)
								) Temp
						) MesAtual,
						(
							SELECT
								SUM(acctinputoctets) MesAnteriorUpload,
								SUM(acctoutputoctets) MesAnteriorDownload
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacct
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$MesAnteriorTemp'
									) UNION (
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacctJornal
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$MesAnteriorTemp'
									)
								) Temp
						) MesAnterior,
						(
							SELECT
								SUM(acctinputoctets) Ultimos6MesesUpload,
								SUM(acctoutputoctets) Ultimos6MesesDownload
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacct
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$Ultimos6MesesTemp'
									) UNION (
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacctJornal
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$Ultimos6MesesTemp'
									)
								) Temp
						) Ultimos6Meses,
						(
							SELECT
								SUM(acctinputoctets) Ultimos12MesesUpload,
								SUM(acctoutputoctets) Ultimos12MesesDownload
							FROM
								(
									( 
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacct
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$Ultimos12MesesTemp'
									) UNION (
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radacctJornal
										WHERE
											username = '$UserName' AND
											SUBSTRING(acctstarttime, 1, 10) <= '$MesAtualTemp' AND
											SUBSTRING(acctstarttime, 1, 10) > '$Ultimos12MesesTemp'
									)
								) Temp
						) Ultimos12Meses
					$Limit";
			$res = mysql_query($sql,$conRadius);
			# VERIFICAR SE POSSUI RESPOSTA, MYSQL
			if(@mysql_num_rows($res) > 0){
				header("content-type: text/xml");
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.=	"\n<reg>";
				# LOOP DE RESPOSTA, MYSQL
				while($lin = @mysql_fetch_array($res)){
					$lin[MesAtualUpload] = byte_convert($lin[MesAtualUpload],2);
					$lin[MesAtualDownload] = byte_convert($lin[MesAtualDownload],2);
					$lin[MesAnteriorUpload] = byte_convert($lin[MesAnteriorUpload],2);
					$lin[MesAnteriorDownload] = byte_convert($lin[MesAnteriorDownload],2);
					$lin[Ultimos6MesesUpload] = byte_convert($lin[Ultimos6MesesUpload],2);
					$lin[Ultimos6MesesDownload] = byte_convert($lin[Ultimos6MesesDownload],2);
					$lin[Ultimos12MesesUpload] = byte_convert($lin[Ultimos12MesesUpload],2);
					$lin[Ultimos12MesesDownload] = byte_convert($lin[Ultimos12MesesDownload],2);
					# MONTAR DATA REFERÊNCIA
					#$MesAnteriorTemp = $MesAnterior;
					$STRTemp = " a $MesAtual";
					$MesAtual = dataConv(incrementaData($MesAtualTemp, 1), "Y-m-d", "d/m/Y")." a ".$Dia."/".$Mes."/".$Ano;
					$MesAnterior = dataConv(incrementaData($MesAnteriorTemp, 1), "Y-m-d", "d/m/Y").$STRTemp;
					$Ultimos6Meses = dataConv(incrementaData($Ultimos6MesesTemp, 1), "Y-m-d", "d/m/Y").$STRTemp;
					$Ultimos12Meses = dataConv(incrementaData($Ultimos12MesesTemp, 1), "Y-m-d", "d/m/Y").$STRTemp;
					# TAG'S DO XML
					$dados	.=	"\n<MesAtual><![CDATA[$MesAtual]]></MesAtual>";
					$dados	.=	"\n<MesAtualUpload><![CDATA[$lin[MesAtualUpload]]]></MesAtualUpload>";
					$dados	.=	"\n<MesAtualDownload><![CDATA[$lin[MesAtualDownload]]]></MesAtualDownload>";
					
					$dados	.=	"\n<MesAnterior><![CDATA[$MesAnterior]]></MesAnterior>";
					$dados	.=	"\n<MesAnteriorUpload><![CDATA[$lin[MesAnteriorUpload]]]></MesAnteriorUpload>";
					$dados	.=	"\n<MesAnteriorDownload><![CDATA[$lin[MesAnteriorDownload]]]></MesAnteriorDownload>";
					
					$dados	.=	"\n<Ultimos6Meses><![CDATA[$Ultimos6Meses]]></Ultimos6Meses>";
					$dados	.=	"\n<Ultimos6MesesUpload><![CDATA[$lin[Ultimos6MesesUpload]]]></Ultimos6MesesUpload>";
					$dados	.=	"\n<Ultimos6MesesDownload><![CDATA[$lin[Ultimos6MesesDownload]]]></Ultimos6MesesDownload>";
					
					$dados	.=	"\n<Ultimos12Meses><![CDATA[$Ultimos12Meses]]></Ultimos12Meses>";
					$dados	.=	"\n<Ultimos12MesesUpload><![CDATA[$lin[Ultimos12MesesUpload]]]></Ultimos12MesesUpload>";
					$dados	.=	"\n<Ultimos12MesesDownload><![CDATA[$lin[Ultimos12MesesDownload]]]></Ultimos12MesesDownload>";
				}
				
				$dados	.=	"\n</reg>";
				return $dados;
			}
		}
		
		return "false";
	}
	# CHAMADA DA FUNÇÃO QUE RETORNA O XML MONTADO
	echo get_ContratoInformacaoConexao();
?>