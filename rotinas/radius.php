<?
	// Rotina 1 - Alimenta a tabela Jornal	
	$sqlServidores = "select
							ValorCodigoInterno
						from
							CodigoInterno
						where
							IdGrupoCodigoInterno = 10000 and
							IdCodigoInterno <= 19 and
							ValorCodigoInterno != ''";
	$resServidores = mysql_query($sqlServidores,$con);
	while($linServidores = mysql_fetch_array($resServidores)){

		$Servidor = explode("\n",$linServidores[ValorCodigoInterno]);

		# [0] local
		# [1] usr
		# [2] pass
		# [3] bd

		# Dados de Conexão com o Banco de dados
		$con_radius	=	@mysql_connect(trim($Servidor[0]),trim($Servidor[1]),trim($Servidor[2]));
		@mysql_select_db(trim($Servidor[3]),$con_radius);

		$sql = "SELECT
					*
				FROM 
					radacct 
				WHERE 
					acctstoptime != '0000-00-00 00:00:00' OR acctstarttime <= DATE_ADD(NOW(), INTERVAL -30 DAY)
				LIMIT 
					0,100000";
		$res = @mysql_query($sql,$con_radius);
		$row = @mysql_num_rows($res);

		if($row > 0){

			$tr_i = 0;

			while($lin = @mysql_fetch_array($res)){

				$arrayKeys = array_keys($lin);

				# Radius 2
				$sqlInsert = "insert into radacctJornal set "; // Ele vai ser gerado abaixo...

				$ArrayCount = count($arrayKeys);

				for($iArray=1; $iArray<$ArrayCount; $iArray++){
					if(($iArray/2) != $arrayKeys[$iArray]){
						if($lin[$arrayKeys[$iArray]] == ''){
							$lin[$arrayKeys[$iArray]] = 'NULL';
						}
						$sqlInsert .= "$arrayKeys[$iArray] = '".$lin[$arrayKeys[$iArray]]."'";

						if($iArray < (count($arrayKeys)-1)){
							$sqlInsert .= ", \n";
						}

					}
				}
				$sqlInsert .= "\n";

				$res2 = mysql_query($sqlInsert,$con_radius);

				if($res2){
					# Apaga Registro
					$sqlDelete = "delete from radacct where radacctid = '$lin[radacctid]'";
					mysql_query($sqlDelete,$con_radius);
				}else{
					echo "ERRO -> ".mysql_error();
				}
			}
		}
		@mysql_close($con_radius);
	}

	@mysql_close($con);
	
	include($Path.'files/conecta.php');

	// Fim - Rotina 1

	// Rotina 2 - Divide o arquivo radius.log
	$FiltraCurrent = false;

	$sql = "select
				IdLoja,
				ValorCodigoInterno Url
			from
				CodigoInterno
			where
				IdGrupoCodigoInterno = 10000 and
				IdCodigoInterno = 20";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$PatchOriginal		= $lin[Url];
		$PatchAlternativo	= substr($PatchOriginal,0,strlen($PatchOriginal)-4);

		$sqlAno = "select
					ValorCodigoInterno Ano
				from
					CodigoInterno
				where
					IdGrupoCodigoInterno = 10000 and
					IdLoja = $lin[IdLoja] and
					IdCodigoInterno = 21";
		$resAno = mysql_query($sqlAno,$con);
		$linAno = mysql_fetch_array($resAno);

		$AnoIni = $linAno[Ano];

		$sqlAno = "select
					max(ValorCodigoInterno) AnoIni
				from
					CodigoInterno
				where
					IdGrupoCodigoInterno = 10000 and
					IdLoja = $lin[IdLoja] and
					IdCodigoInterno > 2000 and
					IdCodigoInterno <= 2999";
		$resAno = mysql_query($sqlAno,$con);
		$linAno = mysql_fetch_array($resAno);

		if($linAno[AnoIni] > $AnoIni){
			$AnoIni = $linAno[AnoIni];
		}

		$AnoIni++;

		for($Ano = $AnoIni; $Ano < date('Y'); $Ano++){

			system("cat $PatchOriginal | grep ' $Ano :' > $PatchAlternativo"."_$Ano.log");

			if(@file_exists("$PatchAlternativo"."_$Ano.log")){
				
				@mysql_close($con);
				include($Path.'files/conecta.php');

				$sqlInsert = "insert into CodigoInterno set 
								IdGrupoCodigoInterno = 10000,
								IdLoja = $lin[IdLoja],
								IdCodigoInterno = $Ano,
								DescricaoCodigoInterno = 'Log Radius ($Ano)',
								ValorCodigoInterno = $Ano";
				@mysql_query($sqlInsert,$con);

				$sqlUpdate = "update CodigoInterno set 
									ValorCodigoInterno='$Ano'
							  where 
									IdGrupoCodigoInterno = '10000' and 
									IdLoja = $lin[IdLoja] and 
									IdCodigoInterno = '21'";
				@mysql_query($sqlUpdate,$con);

				$FiltraCurrent = true;

			}else{
				@unlink("$PatchAlternativo"."_$Ano.log");
			}
		}
	}

	if($FiltraCurrent == true){
		system("cat $PatchOriginal | grep ' $Ano :' > $PatchAlternativo"."_current.log");
		system("mv $PatchAlternativo"."_current.log $PatchOriginal");
		system("chown radius:radius $PatchOriginal");
		system("chown radius:radius $PatchOriginal");
	}
?>